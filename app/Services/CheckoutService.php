<?php

namespace App\Services;

use App\Models\Product;
use App\Util\Validadores;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class CheckoutService
{

    /**
     * Exibe a página de checkout para um produto específico.
     *
     * @param array $params Parâmetros contendo o UUID do produto.
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     *         Retorna a view de pagamento se o produto for encontrado e estiver ativo,
     *         caso contrário, redireciona de volta com mensagem de erro.
     */
    public function showCheckout(array $params)
    {

        try {
            $product = ProductService::getEspecifcProductActive($params['uuid']);
            if (!$product) {
                return ['error' => 'Produto não encontrado ou inativo.'];
            }
            return view('checkout.payment', compact('product'));
        } catch (Exception $e) {
            Log::error('Erro ao buscar produto: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erro ao buscar produto.']);
        }
    }


    /**
     * Prepara os parâmetros necessários para o pagamento com cartão de crédito.
     *
     * @param Request $params Objeto contendo os dados do cartão e do comprador.
     * @return array Retorna um array com os parâmetros formatados para o processamento do pagamento.
     */
    private function prepareCreditCardParams(Request $params): array
    {
        return [
            'cardNumber' => $params['card_number'],
            'holderName' => $params['card_holder_name'],
            'expirationMonth' => $params['card_expiry_month'],
            'expirationYear' => $params['card_expiry_year'],
            'cvv' => $params['card_ccv'],
            'first_name' => $params['first_name'],
            'last_name' => $params['last_name'],
            'email' => $params['email'],
            'identification_number' => $params['identification_number'],
            'zipCode' => $params['zipCode'],
            'address_number' => $params['address_number'] ?? 'SN',
            'address_complement' => $params['address_complement'] ?? '',
            'phone' => $params['phone'] ?? '',
        ];
    }
    /**
     * Processa o checkout de um produto.
     *
     * Este método valida o CPF, obtém o token de acesso do serviço Asaas, verifica se o produto está ativo,
     * cria o cliente no Asaas, prepara os parâmetros de pagamento e realiza a criação do pagamento.
     * Dependendo do método de pagamento selecionado (cartão de crédito, boleto ou PIX), retorna a view apropriada
     * ou uma resposta JSON.
     *
     * @param Request $params Dados da requisição contendo informações do cliente e do pagamento.
     * @return \Illuminate\Http\Response|\Illuminate\View\View Retorna uma view de confirmação ou uma resposta JSON.
     */
    public function storeCheckout(Request $params)
    {
        try {

            $formData = $params->all();
            $validaCpf = Validadores::validaCPF($params['identification_number']);
            if (!$validaCpf) {
                return back()->withErrors(['error' => 'CPF Invalido.']);
            }
            $asaasService = new AsaasService();
            $accessToken = $asaasService->accessToken();

            if ($accessToken->getStatusCode() !== 200) {
                return back()->withErrors(['error' => 'Erro ao obter token.'])->withInput();
            }

            $product = ProductService::getEspecifcProductActive($params->uuid);
            if (!$product) {
                return back()->withErrors(['error' => 'Produto não encontrado ou inativo.'])->withInput();
            }

            $costumer = $asaasService->createCostumer($params);

            $paramsInformation = [
                'customerId' => $costumer->json()['id'],
                'billingType' => $params->payment_method,
                'value' => $product->price,
                'dueDate' => now()->addDays(7)->format('Y-m-d'),
            ];

            if ($params->payment_method == 'CREDIT_CARD') {
                $paramsInformation = array_merge($paramsInformation, $this->prepareCreditCardParams($params));
            }

            $createPayment = $asaasService->createPayment($paramsInformation)->json();

            #vericia se teve erro na requisição
            if (isset($createPayment['errors']) && is_array($createPayment['errors']) && count($createPayment['errors']) > 0) {
                $messages = array_map(fn($error) => $error['description'], $createPayment['errors']);
                return back()->withErrors($messages)->withInput();
            }



            #Valida o meio de pagamento
            if (in_array($params->payment_method, ['CREDIT_CARD', 'BOLETO'])) {
                return  view('checkout.done', compact('product', 'createPayment'));
            }

            if ($params->payment_method == 'PIX') {
                #Usar ess apenas quando for pix para recuperar a imagem do QRCODE
                $paymentInformation = $asaasService->getPayment($createPayment['id'])->json();
                return  view('checkout.done', compact('product', 'createPayment', 'paymentInformation'));
            }


            return response()->json(['message' => 'Checkout processado com sucesso.'], 200);
        } catch (Exception $e) {
            Log::error('Erro ao processar checkout: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao processar checkout.' . $e->getMessage() . $e->getLine() . $e->getFile()], 500);
        }
    }
}
