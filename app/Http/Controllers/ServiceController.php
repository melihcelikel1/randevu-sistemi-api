<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    //hizmetleri listele (Diş temizleme, dolgu vb) 
    public function index()
    {
        return Service::all();
    }

    // yeni hizmet ekler
    public function store(Request $request)
    {
        $service = Service::create($request->all());
        return response()->json($service, 201);
    }
}
