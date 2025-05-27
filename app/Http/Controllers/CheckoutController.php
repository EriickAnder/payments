<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutPayment;
use App\Services\CheckoutService;

class CheckoutController
{

    private $checkoutService;
    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * Exibe os detalhes do checkout com base no UUID fornecido.
     *
     * @param string $id UUID do checkout.
     * @return mixed Retorna os dados do checkout correspondente.
     */
    public function show($id)
    {
        return $this->checkoutService->showCheckout(['uuid' => $id]);
    }

    /**
     * Processa o pagamento do checkout utilizando os dados validados da requisição.
     *
     * @param StoreCheckoutPayment $request Requisição contendo os dados do pagamento.
     * @return \Illuminate\Http\Response Retorna a resposta do processamento do checkout.
     */
    public function store(StoreCheckoutPayment $request)
    {
        return $this->checkoutService->storeCheckout($request);
    }
}
