@extends('template.main')



@section('title', 'Finalização do Pedido')

@section('content')

    <div class="done-order-section">
        @if (isset($product))
            <div class="done-product-info">
                <h2 class="done-product-title">{{ $product->product_name }}</h2>
                <p class="done-product-description"><small><b>Descrição:</b> {{ $product->description }}</small></p>
            </div>
            <div class="done-order-summary">
                <h3 class="done-total-price">Total: <span class="done-price">R$
                        {{ number_format($product->price, 2, ',', '.') }}</span></h3>
            </div>
            @if ($createPayment['billingType'] == 'BOLETO')
                <a href="{{ $createPayment['bankSlipUrl'] }}" class="done-payment-button done-boleto">Realizar Pagamento via
                    Boleto</a>
            @elseif ($createPayment['billingType'] == 'CREDIT_CARD')
                <p class="alert alert-success text-center">Pagamento realizado com sucesso via Cartão de Crédito.</p>
            @elseif ($createPayment['billingType'] == 'PIX')
                <div class="done-pix-section">
                    <img src="data:image/png;base64,{{ $paymentInformation['pix']['encodedImage'] }}" alt="QR Code PIX"
                        class="done-pix-qr">
                    <p class="done-pix-key">
                        <b>Chave PIX:</b>
                        <span id="pix-key">{{ $paymentInformation['pix']['payload'] }}</span>
                        <button id="copy-pix-btn" class="done-copy-btn" title="Copiar chave PIX">Copiar</button>
                    </p>
                    <small class="done-pix-expiration">Expira em: {{ $paymentInformation['pix']['expirationDate'] }}</small>
                </div>
            @endif
        @else
            <div class="done-product-not-found">
                Produto não encontrado.
            </div>
        @endif
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/done.js') }}"></script>
@endsection
