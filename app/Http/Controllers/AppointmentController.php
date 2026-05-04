<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    private const TIME_SLOTS = ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'];

    // Formu görüntüle
    public function create(Request $request): View
    {
        $services = Service::orderBy('name')->get();
        $doctors = Doctor::orderBy('name')->get();

        $selectedDoctorId = (int) ($request->query('doctor_id') ?: $request->old('doctor_id'));
        $selectedDate = $request->query('date') ?: $request->old('date');

        $unavailableTimes = [];

        if ($selectedDoctorId > 0 && ! empty($selectedDate)) {
            $unavailableTimes = $this->getUnavailableTimes($selectedDoctorId, $selectedDate);
        }

        return view('appointment', [
            'services' => $services,
            'doctors' => $doctors,
            'timeSlots' => self::TIME_SLOTS,
            'unavailableTimes' => $unavailableTimes,
            'selectedDoctorId' => $selectedDoctorId,
            'selectedDate' => $selectedDate,
        ]);
    }

    public function availability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date_format:d.m.Y',
        ]);

        return response()->json([
            'unavailable_times' => $this->getUnavailableTimes((int) $validated['doctor_id'], $validated['date']),
        ]);
    }

    public function myAppointments(): View
    {
        $appointments = Appointment::with(['service', 'doctor'])
            ->where('user_id', Auth::id())
            ->orderByDesc('start_time')
            ->get();

        return view('my-appointments', compact('appointments'));
    }

    // Randevuyu kaydet
    public function store(Request $request): RedirectResponse
    {
        // 1. Gelen verileri doğrula (Validation)
        $validated = $request->validate([
            'service' => 'required|exists:services,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date'    => 'required|date_format:d.m.Y',
            'time'    => 'required|in:' . implode(',', self::TIME_SLOTS),
        ], [
            'doctor_id.required' => 'Lutfen bir doktor seciniz.',
            'date.required' => 'Lütfen bir tarih seçiniz.',
            'time.required' => 'Lütfen müsait bir saat seçiniz.',
        ]);

        $user = $request->user();

        // 3. Tarih ve Saati birleştir
        $startTime = Carbon::createFromFormat('d.m.Y H:i', $validated['date'] . ' ' . $validated['time']);
        
        // Hizmet süresini al ve bitişi hesapla
        $service = Service::findOrFail($validated['service']);
        $endTime = $startTime->copy()->addMinutes($service->duration);

        $lockName = sprintf('appointment_doctor_%d_%s', $validated['doctor_id'], str_replace('.', '_', $validated['date']));
        $lockResult = DB::selectOne('SELECT GET_LOCK(?, 5) AS l', [$lockName]);
        $lockAcquired = (int) ($lockResult->l ?? 0) === 1;

        if (! $lockAcquired) {
            return back()->withInput()->withErrors(['time' => 'Sistem şu anda yoğun, lütfen tekrar deneyin.']);
        }

        try {
            // Aynı doktor ve tarihte eşzamanlı talepleri seri hale getirir.
            $exists = Appointment::where('status', '!=', 'cancelled')
                ->where('doctor_id', $validated['doctor_id'])
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                })->exists();

            if ($exists) {
                return back()->withInput()->withErrors(['time' => 'Bu saat dilimi maalesef dolu.']);
            }

            Appointment::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'doctor_id' => $validated['doctor_id'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'confirmed'
            ]);
        } finally {
            DB::selectOne('SELECT RELEASE_LOCK(?)', [$lockName]);
        }

        return back()->with('success', 'Randevunuz başarıyla oluşturuldu!');
    }

    public function cancel(Appointment $appointment): RedirectResponse
    {
        if ((int) $appointment->user_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($appointment->status === 'cancelled') {
            return back()->with('success', 'Randevu zaten iptal edilmiş.');
        }

        if ($appointment->start_time->isPast()) {
            return back()->with('error', 'Sadece gelecekteki randevular iptal edilebilir.');
        }

        $appointment->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Randevunuz iptal edildi.');
    }

    private function getUnavailableTimes(int $doctorId, string $date): array
    {
        try {
            $startOfDay = Carbon::createFromFormat('d.m.Y', $date)->startOfDay();
            $endOfDay = $startOfDay->copy()->endOfDay();

            return Appointment::query()
                ->where('doctor_id', $doctorId)
                ->where('status', '!=', 'cancelled')
                ->whereBetween('start_time', [$startOfDay, $endOfDay])
                ->pluck('start_time')
                ->map(fn ($dateTime) => Carbon::parse($dateTime)->format('H:i'))
                ->unique()
                ->values()
                ->all();
        } catch (\Throwable $e) {
            return [];
        }
    }
}