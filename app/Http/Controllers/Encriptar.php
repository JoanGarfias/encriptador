<?php

namespace App\Http\Controllers;

use App\Services\EncryptionService;
use Illuminate\Http\Request;

class Encriptar extends Controller
{
    protected $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    // Encriptar texto
    public function encriptar(Request $request)
    {
        $text = $request->input('texto');
        $key = $this->encryptionService->generarKey();
        $encryptedText = $this->encryptionService->encriptar($text, $key);

        return response()->json([
            'key' => $key,
            'texto_encriptado' => $encryptedText
        ]);
    }

    // Desencriptar texto
    public function desencriptar(Request $request)
    {
        $key = $request->input('key');
        $text = $request->input('texto_encriptado');
        $decryptedText = $this->encryptionService->desencriptar($text, $key);

        return response()->json([
            'texto_desencriptado' => $decryptedText
        ]);
    }
}


