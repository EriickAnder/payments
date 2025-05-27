<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Services\CheckoutService;

class CheckoutServiceTest extends TestCase
{
    public function test_storeCheckout_returns_back_on_invalid_cpf()
    {
        $request = Request::create('/checkout', 'POST', [
            'identification_number' => '00000000000',
            'payment_method' => 'BOLETO',
            'uuid' => 'any-uuid'
        ]);

        $checkoutService = new CheckoutService();

        $response = $checkoutService->storeCheckout($request);
        $this->assertStringContainsString('CPF Invalido', session('errors')->first());
    }

    public function test_storeCheckout_processes_payment_successfully()
    {
        $request = Request::create('/checkout', 'POST', [
            'identification_number' => '12345678909',
            'payment_method' => 'BOLETO',
            'uuid' => 'valid-product-uuid'
        ]);

        $checkoutService = new CheckoutService();

        $response = $checkoutService->storeCheckout($request);

        $this->assertStringContainsString('checkout.done', method_exists($response, 'name') ? $response->name() : '');
    }
}
