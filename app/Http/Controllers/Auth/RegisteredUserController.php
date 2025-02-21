<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Pago;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'tipo_inscripcion' => ['required', 'string', 'in:presencial,virtual,gratuita'], // Validación para tipo_inscripcion
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tipo_inscripcion' => $request->tipo_inscripcion,
        ]);

        $plan = match ($request->tipo_inscripcion) {
            'presencial' => 10.00,
            'virtual' => 5.00,
            'gratuita' => 0.00,
        };

        // Si el usuario es estudiante, el pago se marca como 'completado'
        $estadoPago = str_ends_with($user->email, '@franciscoayala.es') ? 'completado' : 'pendiente';

        Pago::create([
            'user_id' => $user->id,
            'plan' => $plan,
            'estado' => $estadoPago,
            'transaction_id' => '', 
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
