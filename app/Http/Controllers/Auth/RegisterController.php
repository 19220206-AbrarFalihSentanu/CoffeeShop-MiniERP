<?php
// File: app/Http/Controllers/Auth/RegisterController.php
// Jalankan: php artisan make:controller Auth/RegisterController

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Show customer registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register-customer');
    }

    /**
     * Handle customer registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
        ]);

        // Get customer role
        $customerRole = Role::where('name', 'customer')->first();

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $customerRole->id,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'is_active' => true,
            'email_verified_at' => now()
        ]);

        // Login user
        auth()->login($user);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Registrasi berhasil! Selamat berbelanja di Mini ERP Kopi.');
    }
}
