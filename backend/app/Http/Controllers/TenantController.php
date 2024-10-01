<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use http\Client\Request;

class TenantController
{

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'administrator' => 'required|exists:users,id',
            'address' => 'required|string|max:255',
            'size' => 'required|integer',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|email|max:255',
        ]);

        // Crear nuevo tenant
        Tenant::create($request->all());

        return redirect()->route('filament.auth.login');
    }
}
