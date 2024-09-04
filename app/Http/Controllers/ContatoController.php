<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\Contact;
use App\Models\Configs;
use Exception;

class ContatoController extends Controller
{
    public function send(Request $request)
    {
        // Validação dos campos
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        // Verifica se há erros de validação
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            Mail::to(Configs::where('key', 'MAIL_FROM_ADDRESS')->first()->value, Configs::where('key', 'MAIL_FROM_NAME')->first()->value)->send(new Contact([
                'from' => Configs::where('key', 'MAIL_USERNAME')->first()->value,
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message
            ]));

            return response()->json([
                "status" => 'success',
                "message" => "Email enviado com sucesso!"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => 'error',
                "message" => "Falha ao enviar o e-mail. Erro: " . $e->getMessage(),
            ]);
        }
    }
}
