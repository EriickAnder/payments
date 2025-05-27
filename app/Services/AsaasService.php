<?php

namespace App\Services;

use App\Models\Product;
use App\Util\LimpaPontuacao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasService
{

    /**
     * Retorna um access token em formato JSON.
     *
     * @return \Illuminate\Http\JsonResponse Resposta JSON contendo o access token.
     */
    public  function accessToken()
    {
        try {

            // Ideal é pegar isso aqui das configurações do vendedor ou de um arquivo de configuração =)
            $accessToken = ENV('TOKEN_ASAAS');
                        return response()->json([
                'accessToken' => $accessToken,
            ], 200);
        } catch (Exception $e) {
            Log::error('Erro ao obter access token: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao obter access token.'], 500);
        }
    }

    /**
     * Cria um novo cliente na API Asaas.
     *
     * @param Request $request Dados da requisição contendo informações do cliente.
     * @return \Illuminate\Http\Client\Response|\Illuminate\Http\JsonResponse Retorna a resposta da API ou erro em caso de falha.
     */

    public function createCostumer(Request $request)
    {


        try {
            $accessToken = self::accessToken();
            $formData = [
                'name' => $request->first_name . ' ' . $request->last_name,
                'cpfCnpj' => LimpaPontuacao::limpaCPF_CNPJ($request->identification_number),
                'email' => $request->email,
                'address' => $request->address,
                'postalCode' => $request->zipCode,
            ];
            $response = Http::withHeaders([
                "accept" => "application/json",
                "access_token" => $accessToken->getData()->accessToken,
            ])->post("https://sandbox.asaas.com/api/v3/customers", $formData);

            return $response;
        } catch (Exception $e) {
            Log::error('Erro ao criar cliente: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar cliente.'], 500);
        }
    }


    /**
     * Cria um pagamento utilizando a API Asaas.
     *
     * @param array $params Parâmetros necessários para criação do pagamento:
     *                      - customerId: ID do cliente
     *                      - billingType: Tipo de cobrança (ex: 'CREDIT_CARD')
     *                      - value: Valor do pagamento
     *                      - dueDate: Data de vencimento
     *                      - holderName: Nome do titular do cartão (se for cartão)
     *                      - cardNumber: Número do cartão (se for cartão)
     *                      - expirationMonth: Mês de expiração do cartão (se for cartão)
     *                      - expirationYear: Ano de expiração do cartão (se for cartão)
     *                      - cvv: Código de segurança do cartão (se for cartão)
     *                      - first_name: Primeiro nome do titular (se for cartão)
     *                      - last_name: Sobrenome do titular (se for cartão)
     *                      - email: E-mail do titular (se for cartão)
     *                      - identification_number: CPF ou CNPJ do titular (se for cartão)
     *                      - zipCode: CEP do endereço (se for cartão)
     *                      - address_number: Número do endereço (opcional, se for cartão)
     *                      - address_complement: Complemento do endereço (opcional, se for cartão)
     *                      - phone: Telefone do titular (opcional, se for cartão)
     *
     * @return \Illuminate\Http\Client\Response|\Illuminate\Http\JsonResponse Retorna a resposta da API ou erro em caso de exceção.
     */
    public function createPayment(array $params)
    {
        try {

            $accessToken = self::accessToken();

            $formData = [
                'customer'     => $params['customerId'],
                'billingType'  => $params['billingType'],
                'value'        => $params['value'],
                'dueDate'      => $params['dueDate'],
            ];

            if ($params['billingType'] === 'CREDIT_CARD') {
                $formData['creditCard'] = [
                    'holderName'  => $params['holderName'],
                    'number'      => $params['cardNumber'],
                    'expiryMonth' => $params['expirationMonth'],
                    'expiryYear'  => $params['expirationYear'],
                    'ccv'         => $params['cvv'],
                ];

                $formData['creditCardHolderInfo'] = [
                    'name'              => $params['first_name'] . ' ' . $params['last_name'],
                    'email'             => $params['email'],
                    'cpfCnpj'           => $params['identification_number'],
                    'postalCode'        => $params['zipCode'],
                    'addressNumber'     => $params['address_number'] ?? 'SN',
                    'addressComplement' => $params['address_complement'] ?? '',
                    'phone'             => $params['phone'] ?? '',
                ];
            }

            $response = Http::withHeaders([
                "accept" => "application/json",
                "access_token" => $accessToken->getData()->accessToken,
            ])->post("https://api-sandbox.asaas.com/v3/payments", $formData);


            return $response;
        } catch (Exception $e) {
            Log::error('Erro ao gerar pagamento: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao gerar pagamento.', 'exception' => $e->getMessage()], 500);
        }
    }

    /**
     * Busca as informações de cobrança de um pagamento específico na API Asaas.
     *
     * @param string $paymentId O ID do pagamento a ser consultado.
     * @return \Illuminate\Http\Client\Response|\Illuminate\Http\JsonResponse Retorna a resposta da API ou um erro em caso de exceção.
     */
    public function getPayment(String $paymentId)
    {
        try {
            $accessToken = self::accessToken();
            $response = Http::withHeaders([
                "accept" => "application/json",
                "access_token" => $accessToken->getData()->accessToken,
            ])->get("https://api-sandbox.asaas.com/v3/payments/" . $paymentId . '/billingInfo');

            return $response;
        } catch (Exception $e) {
            Log::error('Não foi possível buscar pagamento: ' . $e->getMessage());
            return response()->json(['error' => 'Não foi possível buscar pagamento', 'exception' => $e->getMessage()], 500);
        }
    }
}
