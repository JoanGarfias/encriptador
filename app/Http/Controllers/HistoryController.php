<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoryRequest;
use App\Models\Encriptados;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth; 

class HistoryController extends Controller
{
   public function getHistory(HistoryRequest $request)
    {
        $inputs = $request->validated();

        $data = Encriptados::select('id', 'user_id', 'content', 'created_at')
                            ->where('user_id', Auth::id())
                            ->paginate($inputs["perPage"]);

        return response()->json($data);
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('perPage', 10);
        $page = (int) $request->query('page', 1);

        $data = Encriptados::select('id', 'user_id', 'content', 'created_at')
                            ->where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage, ['*'], 'page', $page)
                            ->appends(['perPage' => $perPage]);

        return Inertia::render('Historial', [
            'data' => $data,
            'perPage' => $perPage,
        ]);
    }

    public function downloadKey($id)
    {
        $record = Encriptados::find($id);
        if (! $record) {
            abort(404);
        }

        // Verificar que el registro pertenezca al usuario autenticado
        if ($record->user_id !== Auth::id()) {
            abort(403);
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
