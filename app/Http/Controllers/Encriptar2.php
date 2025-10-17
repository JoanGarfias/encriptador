<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Encriptar2 extends Controller
{
    protected array $char = [
        '1','2','3','4','5','6','7','8','9','0',
        'A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
    ];

    // Generar llave aleatoria
    public function generarKey(): array
    {
        $key = [];
        for ($i = 0; $i < 20; $i++) {
            $key[] = $this->char[rand(0, 9)];
        }
        return $key;
    }

    // Encriptar texto
    public function encriptar2($texto)
    {
        $text = $texto;
        $key = $this->generarKey();
        $keyLength = count($key);
        $result = '';

        for ($i = 0; $i < mb_strlen($text, 'UTF-8'); $i++) {
            $c = mb_substr($text, $i, 1, 'UTF-8');
            $ascii = mb_ord($c, 'UTF-8');
            $k = (int)$key[$i % $keyLength];

            switch ($k) {
                case 1: $ascii += 9; break;
                case 2: $ascii -= 1; break;
                case 3: $ascii += 5; break;
                case 4: $ascii -= 2; break;
                case 5: $ascii += 1; break;
                case 6: $ascii += 4; break;
                case 7: $ascii -= 3; break;
                case 8: $ascii += 2; break;
                case 9: $ascii -= 5; break;
                case 0: $ascii += 10; break;
            }

            $result .= mb_chr($ascii, 'UTF-8');
        }

         $fileData = $this->createAndDownloadFile($result, $key);

        return response()->json([
            'filename' => basename($fileData['filename']), 
            'key' => $fileData['key']
        ]);
    }
    /*
    // Desencriptar texto
    public function desencriptar(Request $request)
    {
        $key = str_split($request->input('key')); // string → array
        $text = base64_decode($request->input('texto')); // decodifica Base64
        $keyLength = count($key);
        $result = '';

        for ($i = 0; $i < mb_strlen($text, 'UTF-8'); $i++) {
            $c = mb_substr($text, $i, 1, 'UTF-8');
            $ascii = mb_ord($c, 'UTF-8');
            $k = (int)$key[$i % $keyLength];

            switch ($k) {
                case 1: $ascii -= 9; break;
                case 2: $ascii += 1; break;
                case 3: $ascii -= 5; break;
                case 4: $ascii += 2; break;
                case 5: $ascii -= 1; break;
                case 6: $ascii -= 4; break;
                case 7: $ascii += 3; break;
                case 8: $ascii -= 2; break;
                case 9: $ascii += 5; break;
                case 0: $ascii -= 10; break;
            }

            $result .= mb_chr($ascii, 'UTF-8');
        }

        return response()->json([
            'resultados' => base64_encode($result)
        ]);
    }*/

    public function createAndDownloadFile($texto, $key)
    {
    $fileName = 'my_file_' . time() . '.txt';

    $filePath = 'downloads/' . $fileName;

    Storage::disk('public')->put($filePath, $texto);

    $fileNameKey = $fileName . '_key_' . time() . '.key';

    $filePathKey = 'downloads/' . $fileNameKey;

    
    Storage::disk('public')->put($filePathKey, implode($key));

    return [
        'filename' => $fileName,
        'key' => $fileNameKey,
    ];
    }

    public function uploadFile(Request $request)
    {
    $request->validate([
        'user_file' => 'required|file|mimes:txt',
    ]);

    $file = $request->file('user_file');
    $content = file_get_contents($file->getRealPath());

    return $this->encriptar2($content);
    }

    public function downloadFile($filename)
    {
    $filePath = storage_path('app/public/downloads/' . $filename);

    if (!file_exists($filePath)) {
        abort(404, 'File not found.');
    }

    return response()->download($filePath);
    }

}


?>