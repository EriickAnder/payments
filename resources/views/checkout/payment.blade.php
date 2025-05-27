@extends('template.main')



@section('title', 'Checkout')

@section('content')

    <form method="POST" action="{{ route('checkout.store') }}">
        <input type="hidden" name="uuid" value="{{ $product->uuid }}">
        <div class="container">
            @csrf
            <div class="order-section">
                <h2>Dados Pessoais</h2>
                <div class="row">
                    <input type="text" name="first_name" placeholder="Nome" required>
                    <input type="text" name="last_name" placeholder="Sobrenome" required>
                </div>
                <div class="row">
                    <input type="e-mail" name="email" placeholder="email" required>
                    <input type="text" name="identification_number" id='identification_number' placeholder="CPF"
                        required>
                </div>
                <h2>Endereço de Entrega</h2>
                <div class="row">
                    <select name="country" required>
                        <option value="brasil">Brasil</option>
                    </select>
                    <select name="state" required>
                        <option value="sp">São Paulo</option>
                    </select>
                </div>
                <div class="row">
                    <input type="text" name="city" placeholder="Cidade" required>
                    <input type="text" name="zipCode" placeholder="CEP" required>
                </div>
                <div class="row">
                    <input type="text" name="address" placeholder="Endereço" required>
                </div>

            </div>
            <div class="order-section">
                <h2>Pagamento</h2>
                @if (isset($product))
                    <div class="product">
                        <div>
                            <p><b>Produto:</b> {{ $product->product_name }}<br>
                                <small><b>Descrição:</b> {{ $product->description }}</small>
                            </p>
                        </div>
                    </div>
                    <div class="summary">

                        <h3>Total: R$ {{ $product->price }}</h3>
                    </div>
                    <div class="payment">
                        <p>Selecione uma forma de Pagamento:</p>
                        <select name="payment_method" required>
                            <option value="" disabled selected>Selecione</option>
                            <option value="CREDIT_CARD">Cartão</option>
                            <option value="BOLETO">Boleto</option>
                            <option value="PIX">Pix</option>
                        </select>
                        <div id="credit-card-form" style="display: none; margin-top: 20px;">
                            <h3>Dados do Cartão de Crédito</h3>
                            <div class="row">
                                <input type="text" name="card_holder_name" placeholder="Nome no cartão" required>
                                <input type="text" name="card_number" id="card_number" placeholder="Número do cartão"
                                    required>
                            </div>
                            <div class="row">
                                <input type="text" name="card_expiry_month" id="card_expiry_month"
                                    placeholder="Mês de expiração (MM)" required>
                                <input type="text" name="card_expiry_year" id="card_expiry_year"
                                    placeholder="Ano de expiração (AAAA)" required>
                                <input type="text" name="card_ccv" id="card_ccv" placeholder="CVV" required>
                            </div>
                        </div>


                    </div>
                    <button class="place-order">Realizar Pagamento</button>
                @endif
            </div>
        </div>
    </form>
@endsection
@section('js')
    <script>
        $('#identification_number').mask('000.000.000-00');
        $('#card_number').mask('0000000000000000');
        $('#card_expiry_month').mask('00');
        $('#card_ccv').mask('0000');
        $('#card_expiry_year').mask('0000');
    </script>
    <script src="{{ asset('js/done.js') }}"></script>
@endsection
