<?php

namespace App\Util;

class Validadores
{
    public static function validaCPF(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) != 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($i = 9; $i < 11; $i++) {
            $soma = 0;
            for ($j = 0; $j < $i; $j++) {
                $soma += $cpf[$j] * (($i + 1) - $j);
            }
            $digito = (10 * $soma) % 11 % 10;
            if ($cpf[$i] != $digito) return false;
        }

        return true;
    }
}
