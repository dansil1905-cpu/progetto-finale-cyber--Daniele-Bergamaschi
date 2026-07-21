<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class FinancialController extends Controller
{

    public function index(Request $request)
    {
        $records = FinancialRecord::where('user_id', $request->user()->id)->get();
        
        return response()->json([
            'success' => true,
            'data' => $records
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'card_number' => 'required|string|size:16',
            'balance' => 'required|numeric',
            'sensitive_info' => 'required|string',
        ]);

        $encryptedData = Crypt::encryptString($request->sensitive_info);
        $maskedCard = '****-****-****-' . substr($request->card_number, -4);

        $record = FinancialRecord::create([
            'user_id' => $request->user()->id,
            'card_number_masked' => $maskedCard,
            'balance' => $request->balance,
            'sensitive_data' => $encryptedData,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dato finanziario registrato e cifrato con successo.',
            'data' => $record
        ], 201);
    }
}