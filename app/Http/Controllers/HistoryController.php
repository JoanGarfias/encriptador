<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoryRequest;
use App\Models\Encriptados;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function getHistory(HistoryRequest $request)
    {
        /*
       perPage: indica el número de elementos que se quiere visuaiza
       page: indica la página que se quiere visualizar
        */
        $inputs = $request->validated();

        $data = Encriptados::select('id', 'content', 'created_at')
                            ->paginate($inputs["perPage"]);

        return response()->json($data);
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('perPage', 10);
        $page = (int) $request->query('page', 1);

        $data = Encriptados::select('id', 'content', 'created_at')
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage, ['*'], 'page', $page)
                            ->appends(['perPage' => $perPage]);

        return view('index', compact('data', 'perPage'));
    }

    public function downloadKey($id)
    {
        $record = Encriptados::find($id);
        if (! $record) {
            abort(404);
        }

        $key = $record->key;
        $filename = "key_{$record->id}.key";

        return response()->streamDownload(function() use ($key) {
            echo $key;
        }, $filename, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

}
