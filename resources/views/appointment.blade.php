@extends('layouts.app')

@section('title', 'Randevu Talebi Oluştur | LorienDent')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-calendar {
        background: #fff;
        border-radius: 1.5rem !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid #f1f5f9 !important;
    }
</style>

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-2xl mb-6 font-bold text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-slate-50 relative">
        <div class="p-8 md:p-14">
            <h2 class="text-3xl font-black text-slate-900 mb-2">Randevu Formu</h2>
            <p class="text-slate-400 mb-10">Lütfen bilgilerinizi eksiksiz doldurun.</p>

            <form action="{{ route('appointments.store') }}" method="POST" class="space-y-8">
                @csrf
                @php
                    $selectedService = old('service');
                    $selectedDoctor = old('doctor_id', $selectedDoctorId);
                    $selectedDateValue = old('date', $selectedDate);
                    $canSelectTime = !empty($selectedService) && !empty($selectedDoctor) && !empty($selectedDateValue);
                @endphp

                <div class="grid md:grid-cols-3 gap-6 pt-6 border-t border-slate-100">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-500 uppercase ml-1">Doktor</label>
                        <select id="doctor-select" name="doctor_id" class="w-full p-4 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer">
                            <option value="">Doktor seçiniz</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ (string) old('doctor_id', $selectedDoctorId) === (string) $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-500 uppercase ml-1">Tedavi Türü</label>
                        <select id="service-select" name="service" class="w-full p-4 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer">
                            <option value="" {{ empty($selectedService) ? 'selected' : '' }}>Tedavi türünü seçiniz</option>
                            @forelse($services as $service)
                                <option value="{{ $service->id }}" {{ (string) $selectedService === (string) $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @empty
                                <option value="">Aktif hizmet bulunamadı</option>
                            @endforelse
                        </select>
                        @error('service') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-slate-500 uppercase ml-1">Randevu Tarihi</label>
                        <input type="text" id="date-picker" name="date" value="{{ old('date', $selectedDate) }}" required placeholder="Tarih seçiniz..."
                                class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer">
                        @error('date') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2 pt-6 border-t border-slate-100">
                    <label class="text-xs font-black text-slate-500 uppercase ml-1">Müsait Saatler</label>
                    <div class="grid grid-cols-3 md:grid-cols-6 gap-2">
                        @foreach($timeSlots as $time)
                        @php
                            $isUnavailable = in_array($time, $unavailableTimes, true);
                            $isDisabled = !$canSelectTime || $isUnavailable;
                        @endphp
                        <label class="{{ $isDisabled ? 'cursor-not-allowed' : 'cursor-pointer' }}" data-slot-label>
                            <input type="radio" name="time" value="{{ $time }}" {{ old('time') == $time ? 'checked' : '' }} class="peer sr-only" {{ $isDisabled ? 'disabled' : '' }} data-time-slot>
                            <div class="py-2 text-center text-xs font-bold border rounded-xl transition
                                {{ !$canSelectTime ? 'bg-slate-100 text-slate-400 border-slate-200' : '' }}
                                {{ $isUnavailable ? 'bg-red-100 text-red-700 border-red-200 line-through' : '' }}
                                {{ $canSelectTime && !$isUnavailable ? 'border-slate-200 peer-checked:bg-indigo-600 peer-checked:text-white' : '' }}" data-slot-box
                                data-default-class="border-slate-200 peer-checked:bg-indigo-600 peer-checked:text-white"
                                data-unavailable-class="bg-red-100 text-red-700 border-red-200 line-through"
                                data-disabled-class="bg-slate-100 text-slate-400 border-slate-200">
                                {{ $time }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @if(!$canSelectTime)
                        <p class="text-xs text-slate-500 font-semibold mt-2">Saat seçimi için doktor, tedavi türü ve tarih seçin.</p>
                    @else
                        <p class="text-xs text-slate-500 font-semibold mt-2">Lütfen müsait bir saat seçiniz.</p>
                    @endif
                    @error('time') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black text-lg hover:bg-indigo-600 transition shadow-2xl">
                    RANDEVUYU TAMAMLA <i class="fas fa-check-circle ml-2"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/tr.js"></script>
<script>
    const doctorSelect = document.getElementById("doctor-select");
    const serviceSelect = document.getElementById("service-select");
    const datePicker = document.getElementById("date-picker");
    const timeInputs = Array.from(document.querySelectorAll("[data-time-slot]"));
    const timeLabels = Array.from(document.querySelectorAll("[data-slot-label]"));
    const timeBoxes = Array.from(document.querySelectorAll("[data-slot-box]"));

    const resetSelectionIfDisabled = () => {
        const selectedTime = timeInputs.find((input) => input.checked);
        if (selectedTime && selectedTime.disabled) {
            selectedTime.checked = false;
        }
    };

    const applySlotState = (unavailableTimes = []) => {
        const doctorId = doctorSelect?.value;
        const serviceId = serviceSelect?.value;
        const dateValue = datePicker?.value;
        const canSelect = Boolean(doctorId && serviceId && dateValue);
        const unavailableSet = new Set(unavailableTimes);

        timeInputs.forEach((input, index) => {
            const label = timeLabels[index];
            const box = timeBoxes[index];
            const time = input.value;
            const isUnavailable = unavailableSet.has(time);
            const isDisabled = !canSelect || isUnavailable;

            input.disabled = isDisabled;

            if (label) {
                label.classList.toggle("cursor-not-allowed", isDisabled);
                label.classList.toggle("cursor-pointer", !isDisabled);
            }

            if (box) {
                const defaultClass = box.dataset.defaultClass || "";
                const unavailableClass = box.dataset.unavailableClass || "";
                const disabledClass = box.dataset.disabledClass || "";

                box.className = "py-2 text-center text-xs font-bold border rounded-xl transition";
                if (isUnavailable) {
                    box.className += ` ${unavailableClass}`;
                } else if (!canSelect) {
                    box.className += ` ${disabledClass}`;
                } else {
                    box.className += ` ${defaultClass}`;
                }
            }
        });

        resetSelectionIfDisabled();
    };

    const refreshAvailability = async () => {
        const doctorId = doctorSelect?.value;
        const dateValue = datePicker?.value;
        const serviceId = serviceSelect?.value;

        if (!doctorId || !dateValue || !serviceId) {
            applySlotState([]);
            return;
        }

        const url = new URL("{{ route('appointments.availability') }}", window.location.origin);
        url.searchParams.set("doctor_id", doctorId);
        url.searchParams.set("date", dateValue);

        try {
            const response = await fetch(url.toString(), {
                headers: { "Accept": "application/json" },
            });

            if (!response.ok) {
                applySlotState([]);
                return;
            }

            const data = await response.json();
            applySlotState(data.unavailable_times || []);
        } catch (error) {
            applySlotState([]);
        }
    };

    if (doctorSelect) {
        doctorSelect.addEventListener("change", refreshAvailability);
    }
    if (serviceSelect) {
        serviceSelect.addEventListener("change", refreshAvailability);
    }

    flatpickr(datePicker, {
        locale: "tr",
        dateFormat: "d.m.Y",
        minDate: "today",
        disableMobile: true,
        onChange: refreshAvailability,
    });

    refreshAvailability();
</script>
@endsection