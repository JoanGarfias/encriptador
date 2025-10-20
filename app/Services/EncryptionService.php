<?php

namespace App\Services;

class EncryptionService
{
    protected array $char = [
        '1','2','3','4','5','6','7','8','9','0',
        'A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
    ];

    // Generar llave aleatoria
    public function generarKey(): string
    {
        $key = [];
        for ($i = 0; $i < 20; $i++) {
            $key[] = $this->char[rand(0, 9)];
        }
        return implode('', $key);
    }

    // Encriptar texto
    public function encriptar(string $text, string $key): string
    {
        $keyParts = str_split($key);
        $keyLength = count($keyParts);
        $result = '';

        for ($i = 0; $i < mb_strlen($text, 'UTF-8'); $i++) {
            $c = mb_substr($text, $i, 1, 'UTF-8');
            $ascii = mb_ord($c, 'UTF-8');
            $k = (int)$keyParts[$i % $keyLength];

            switch ($k) {
                case 1: $ascii += 9; break;
                case 2: $ascii += 1; break;
                case 3: $ascii += 5; break;
                case 4: $ascii += 2; break;
                case 5: $ascii += 1; break;
                case 6: $ascii += 4; break;
                case 7: $ascii += 3; break;
                case 8: $ascii += 2; break;
                case 9: $ascii += 5; break;
                case 0: $ascii += 10; break;
            }

            $result .= mb_chr($ascii, 'UTF-8');
        }

        return base64_encode($result);
    }

    // Desencriptar texto
    public function desencriptar(string $text, string $key): string
    {
        $keyParts = str_split($key);
        $text = base64_decode($text);
        $keyLength = count($keyParts);
        $result = '';

        for ($i = 0; $i < mb_strlen($text, 'UTF-8'); $i++) {
            $c = mb_substr($text, $i, 1, 'UTF-8');
            $ascii = mb_ord($c, 'UTF-8');
            $k = (int)$keyParts[$i % $keyLength];

            switch ($k) {
                case 1: $ascii -= 9; break;
                case 2: $ascii -= 1; break;
                case 3: $ascii -= 5; break;
                case 4: $ascii -= 2; break;
                case 5: $ascii -= 1; break;
                case 6: $ascii -= 4; break;
                case 7: $ascii -= 3; break;
                case 8: $ascii -= 2; break;
                case 9: $ascii -= 5; break;
                case 0: $ascii -= 10; break;
            }

            $result .= mb_chr($ascii, 'UTF-8');
        }

        return $result;
    }
}
