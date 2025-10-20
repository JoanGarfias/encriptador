<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\EncryptionService;

class Encriptar extends Controller
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

    //Encriptacion de un archivo (se recibe un .txt normal)
    public function encriptarArchivo(Request $request)
    {
    $request->validate([
        'user_file' => 'required|file|mimes:txt',
    ]);
    $file = $request->file('user_file');
    $content = file_get_contents($file->getRealPath());
    $encryptionService = new EncryptionService();

    $key = $this->generarKey();
    $encriptado = $encryptionService->encriptar($content, implode($key));

    $response = $this->createAndDownloadFile($encriptado, $key);

    return response()->json($response);

    //return $this->createAndDownloadFile($encriptado, $key);
    }

    //Envio de un .txt encriptado y un .key
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

    //Desencriptacion de un archivo (se recibe un .txt encriptado y su .key)
    public function desencriptarArchivo(Request $request)
    {
    $request->validate([
        'user_file' => 'required|file|mimes:txt',
        'user_key'  => 'required|file|mimes:key',
    ]);

    $file = $request->file('user_file');
    $content = file_get_contents($file->getRealPath());
    $key = $request->file('user_key');
    $contentKey = file_get_contents($key->getRealPath());
    $encryptionService = new EncryptionService();

    $desencriptado = $encryptionService->desencriptar($content, $contentKey);

    $response = $this->createAndDownloadFileDecrypted($desencriptado);

    return response()->json($response);

    //return $this->createAndDownloadFileDecrypted($desencriptado);
    }

    //Envio de un .txt desencriptado
    public function createAndDownloadFileDecrypted($texto)
    {
    $fileName = 'my_decrypted_file_' . time() . '.txt';
    $filePath = 'downloads/' . $fileName;
    Storage::disk('public')->put($filePath, $texto);
    Log::debug($fileName);

    return [
        'filename' => $fileName,
    ];
    }

    //Funcion para descargar archivos
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