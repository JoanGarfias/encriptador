<?php

namespace App\Http\Controllers;
use App\Http\Requests\HistoryRequest;

class HistoryController extends Controller
{
    public function getHistory(HistoryRequest $request)
    {
        $data = $request->validated();

        return response()->json($data);
    }
}
