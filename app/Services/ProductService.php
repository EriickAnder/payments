<?php

namespace App\Services;

use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Log;

class ProductService
{


    /**
     * Retorna um produto ativo específico pelo UUID.
     *
     * @param string $uuid UUID do produto a ser buscado.
     * @return Product|null Retorna o produto ativo encontrado ou null se não existir.
     */
    public static function getEspecifcProductActive($uuid)
    {

        try {
            $product = Product::where('status', 1)
                ->where('uuid', $uuid)
                ->first();
            return $product;
        } catch (Exception $e) {
            Log::error('Erro ao buscar produto: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erro ao buscar produto.']);
        }
    }
}
