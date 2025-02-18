<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Pago;

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();
        
        // Verificar el estado del pago
        $pago = Pago::where('user_id', $user->id)->latest()->first();
        
        if ($pago && $pago->estado === 'pendiente') {
            Auth::logout(); // Cerrar la sesión
            return redirect()->route('paypal.pay', ['user_id' => $user->id]);
        }

        $request->session()->regenerate();

        $role = $user->role;

        return redirect()->intended(route('dashboard', absolute: false))->with('role', $role);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
