<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoryRequest;
use App\Models\Encriptados;

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
}
