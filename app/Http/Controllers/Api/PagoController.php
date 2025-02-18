<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Exception;
use Illuminate\Support\Facades\Log;

class PagoController extends Controller
{
    private $client;

    public function __construct()
    {
        $paypalConfig = Config::get('paypal');
        $environment = new SandboxEnvironment($paypalConfig['sandbox']['client_id'], $paypalConfig['sandbox']['client_secret']);
        $this->client = new PayPalHttpClient($environment);
        Log::info('PayPal client initialized');
    }

    public function payWithPayPal(Request $request, $userId)
    {
        Log::info('payWithPayPal method called for user ID: ' . $userId);
        
        $user = User::findOrFail($userId);
        $pago = Pago::where('user_id', $user->id)->latest()->first();

        if (!$pago) {
            $plan = $this->getPlanAmount($user->tipo_inscripcion);
            $pago = Pago::create([
                'user_id' => $user->id,
                'plan' => $plan,
                'estado' => 'pendiente',
                'transaction_id' => ''
            ]);
            Log::info('New payment created for user ID: ' . $userId . ', Plan: ' . $plan);
        }

        $orderRequest = new OrdersCreateRequest();
        $orderRequest->prefer('return=representation');
        $orderRequest->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "EUR",
                    "value" => $pago->plan
                ]
            ]],
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancel')
            ]
        ];

        try {
            $response = $this->client->execute($orderRequest);
            Log::info('PayPal order created successfully. Order ID: ' . $response->result->id);
            $pago->transaction_id = $response->result->id;
            $pago->save();
            return redirect()->away($response->result->links[1]->href);
        } catch (Exception $ex) {
            Log::error('Error creating PayPal order: ' . $ex->getMessage());
            return redirect()->route('pago.pendiente')->with('error', 'Hubo un error al procesar el pago: ' . $ex->getMessage());
        }
    }

    public function paypalSuccess(Request $request)
    {
        Log::info('PayPal success request: ' . json_encode($request->all()));
        
        $orderId = $request->query('token', $request->query('orderID'));
        Log::info('paypalSuccess method called. Order ID: ' . $orderId);

        if (!$orderId) {
            Log::warning('PayPal success callback received without order ID');
            return redirect()->route('pago.pendiente')->with('error', 'Pago cancelado: No se recibió ID de orden.');
        }

        try {
            $captureRequest = new OrdersCaptureRequest($orderId);
            $response = $this->client->execute($captureRequest);
            Log::info('PayPal API Response: ' . json_encode($response->result));

            if ($response->result->status === 'COMPLETED') {
                $pago = Pago::where('transaction_id', $orderId)->first();
                if ($pago) {
                    $pago->estado = 'completado';
                    $pago->save();
                    Log::info('Payment completed successfully. Order ID: ' . $orderId);
                    return redirect()->route('login')->with('success', 'Pago completado. Ahora puedes iniciar sesión.');
                } else {
                    Log::error('Payment record not found for Order ID: ' . $orderId);
                }
            }
        } catch (Exception $ex) {
            Log::error('Error capturing PayPal order: ' . $ex->getMessage());
        }
        return redirect()->route('pago.pendiente')->with('error', 'Hubo un error al procesar el pago.');
    }

    public function paypalCancel()
    {
        Log::info('PayPal payment cancelled by user');
        return redirect()->route('pago.pendiente')->with('error', 'Pago cancelado.');
    }

    private function getPlanAmount($tipoInscripcion)
    {
        $amount = 5.00;
        switch ($tipoInscripcion) {
            case 'presencial':
                $amount = 10.00;
                break;
            case 'virtual':
                $amount = 5.00;
                break;
            case 'gratuita':
                $amount = 0.00;
                break;
        }
        Log::info("Plan amount for $tipoInscripcion: $amount");
        return $amount;
    }
}