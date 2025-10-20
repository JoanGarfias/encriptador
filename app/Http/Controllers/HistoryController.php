<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoryRequest;
use App\Models\Encriptados;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class HistoryController extends Controller
{
    public function getHistory(HistoryRequest $request)
{
    $inputs = $request->validated();

    $query = Encriptados::select('id', 'user_id', 'content', 'created_at')
                ->where('user_id', auth()->id());

    if (!empty($inputs['search'])) {
        $query->where('content', 'like', '%' . $inputs['search'] . '%');
    }

    $data = $query->orderBy('created_at', 'desc')
                  ->paginate($inputs['perPage'] ?? 10);

    return response()->json($data);
}

    public function index(Request $request)
    {
        $perPage = (int) $request->query('perPage', 10);
        $page = (int) $request->query('page', 1);

        $data = Encriptados::select('user_id', 'content', 'created_at')
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage, ['*'], 'page', $page)
                            ->appends(['perPage' => $perPage]);

        //Aquí mandamos los datos al componente Vue
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

        $key = $record->key;
        $filename = "key_{$record->id}.key";

        return response()->streamDownload(function() use ($key) {
            echo $key;
        }, $filename, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function downloadContent($id)
    {
        Log::debug($id);
        $record = Encriptados::find($id);
        if (! $record) {
            abort(404);
        }

        $content = $record->content;
        $filename = "encriptado_{$record->id}.txt";

        return response()->streamDownload(function() use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
