<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\EncryptionService;
use App\Http\Requests\HistoryRequest;
use App\Models\Encriptados;

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
    $id = $request->input("id");
    if(is_null($id)){
        return json_encode(["error" => "Inicia sesion para encriptar"]);
    }
    
    $request->validate([
        'user_file' => 'required|file|mimes:txt',
    ]);
    $file = $request->file('user_file');
    $content = file_get_contents($file->getRealPath());
    $encryptionService = new EncryptionService();

    $key = $this->generarKey();
    $encriptado = $encryptionService->encriptar($content, implode($key));

    $response = $this->createAndDownloadFile($encriptado, $key, $id);

    return response()->json($response);

    //return $this->createAndDownloadFile($encriptado, $key);
    }

    //Envio de un .txt encriptado y un .key
    public function createAndDownloadFile($texto, $key, $id)
    {

    $registro = new Encriptados();
    $registro->user_id = $id;
    $registro->content = $texto;
    $registro->key = implode($key);
    try{
        $registro->save();

        Log::debug($registro->id);
    } catch(Exception $e){
        Log::debug($e);
    }
    // En lugar de escribir archivos en storage, ya guardamos el contenido y la key en el modelo
    // Devolvemos el id del registro para que el frontend solicite la descarga por id
    return [
        'id' => $registro->id,
    ];
    }

    //Desencriptacion de un archivo (se recibe un .txt encriptado y su .key)
    public function desencriptarArchivo(Request $request)
    {
    $request->validate([
        'user_file' => 'required|file|mimes:txt',
        'user_key'  => 'required|file',
    ]);
    Log::debug("potaxio");
    $file = $request->file('user_file');
    $content = file_get_contents($file->getRealPath());
    $key = $request->file('user_key');
    $contentKey = file_get_contents($key->getRealPath());
    $encryptionService = new EncryptionService();
    
    $desencriptado = $encryptionService->desencriptar($content, $contentKey);
    Log::debug($desencriptado);

    // Guardar el contenido desencriptado en el modelo y devolver el id
    $response = $this->createAndDownloadFileDecrypted($desencriptado);

    return response()->json($response);

    //return $this->createAndDownloadFileDecrypted($desencriptado);
    }

    //Envio de un .txt desencriptado
    public function createAndDownloadFileDecrypted($texto)
    {
    // Guardar el texto desencriptado en la tabla `encriptados` y devolver el id
    $registro = new Encriptados();
    // Si la ruta está protegida por middleware 'auth', podemos obtener el id del usuario autenticado
    $registro->user_id = auth()->id();
    $registro->content = $texto;
    $registro->key = null;
    try {
        $registro->save();
        Log::debug('Decrypted saved id: ' . $registro->id);
    } catch (Exception $e) {
        Log::debug($e);
    }

    return [
        'id' => $registro->id,
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

    // Descargar el contenido encriptado desde la base de datos y crear el archivo al vuelo
    public function downloadContent($id)
    {
        $registro = Encriptados::findOrFail($id);
        $content = $registro->content;
        $filename = 'encriptado_' . $registro->id . '.txt';

        return response()->streamDownload(function() use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type' => 'text/plain'
        ]);
    }

    // Descargar la key desde la base de datos y crear el archivo al vuelo
    public function downloadKey($id)
    {
        $registro = Encriptados::findOrFail($id);
        $key = $registro->key;
        $filename = 'key_' . $registro->id . '.key';

        return response()->streamDownload(function() use ($key) {
            echo $key;
        }, $filename, [
            'Content-Type' => 'application/octet-stream'
        ]);
    }

    // Recibe un archivo encriptado y su key, desencripta y devuelve el archivo resultante como descarga
    public function downloadDecryptedFromUpload(Request $request)
    {
        $request->validate([
            'user_file' => 'required|file|mimes:txt',
            'user_key'  => 'required|file',
        ]);
        try {
            $file = $request->file('user_file');
            $keyFile = $request->file('user_key');

            $content = file_get_contents($file->getRealPath());
            $contentKey = file_get_contents($keyFile->getRealPath());

            $encryptionService = new EncryptionService();
            $decrypted = $encryptionService->desencriptar($content, $contentKey);

            // Use the original filename (without extension) to build a friendly download name
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = $originalName ? $originalName . '_desencriptado.txt' : 'desencriptado_' . time() . '.txt';

            return response()->streamDownload(function() use ($decrypted) {
                echo $decrypted;
            }, $filename, [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);
        } catch (\Exception $e) {
            Log::error('Error decrypting upload: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Error processing request.'], 500);
        }
    }

}


?>