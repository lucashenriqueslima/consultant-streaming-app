<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function upload(Request $request)
    {
        dd($request->all());

        // Verificar se o campo `filepond` está presente no request
        if (!$request->hasFile('filepond')) {
            return response()->json(['status' => 'error', 'message' => 'No file uploaded.'], 422);
        }

        // Obter o arquivo do request pelo campo `filepond`
        $file = $request->file('filepond');
        if (!$file || !$file->isValid()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid file upload.'], 422);
        }

        // Continuar o processamento com o arquivo correto...
        // Recuperar metadados do FilePond para chunks (se aplicável)
        $filepondMetadata = json_decode($request->get('filepond'), true);

        // Verificar se os metadados contêm um ID para uploads em chunks
        $fileId = $filepondMetadata['id'] ?? Str::uuid()->toString();
        $chunkIndex = $filepondMetadata['chunk']['index'] ?? 0;
        $totalChunks = $filepondMetadata['chunk']['count'] ?? 1;
        $originalFileName = $filepondMetadata['name'] ?? $file->getClientOriginalName();

        // Definir o diretório de upload temporário no S3
        $s3ChunkPath = "uploads/chunks/{$fileId}/chunk_{$chunkIndex}.part";

        // Fazer upload do chunk atual para o S3
        Storage::disk('s3')->put($s3ChunkPath, file_get_contents($file->getPathname()));

        // Verificar se todos os chunks foram enviados
        $uploadedChunks = collect(Storage::disk('s3')->files("uploads/chunks/{$fileId}"))
            ->filter(function ($file) {
                return Str::endsWith($file, '.part');
            })
            ->count();

        if ($uploadedChunks == $totalChunks) {
            return $this->mergeChunks($fileId, $totalChunks, $originalFileName);
        }

        return response()->json(['status' => 'partial', 'uploaded_chunks' => $uploadedChunks]);
    }
    public function patch(Request $request)
    {
        // Recuperar informações do chunk
        $file = $request->file('file');
        $patchMetadata = json_decode($request->get('filepond'), true);

        $fileId = $patchMetadata['id'];
        $chunkName = $patchMetadata['chunk']['index'];
        $chunkSize = $patchMetadata['chunk']['size'];
        $totalChunks = $patchMetadata['chunk']['count'];
        $totalSize = $patchMetadata['totalSize'];

        $s3ChunkPath = "uploads/chunks/{$fileId}/{$chunkName}.part";

        // Armazenar cada chunk no S3
        Storage::disk('s3')->put($s3ChunkPath, file_get_contents($file->getPathname()));

        // Verificar se todos os chunks foram enviados
        $uploadedChunks = collect(Storage::disk('s3')->files("uploads/chunks/{$fileId}"))
            ->filter(function ($file) {
                return Str::endsWith($file, '.part');
            })
            ->count();

        if ($uploadedChunks == $totalChunks) {
            return $this->mergeChunks($fileId, $totalChunks, $patchMetadata['file_name']);
        }

        return response()->json(['status' => 'partial']);
    }

    public function mergeChunks($fileId, $totalChunks, $fileName)
    {
        $disk = Storage::disk('s3');
        $targetFilePath = "uploads/complete/{$fileName}";

        // Abrir o arquivo final para escrita
        $targetFile = tmpfile();

        // Iterar por cada chunk e combiná-los
        for ($chunk = 0; $chunk < $totalChunks; $chunk++) {
            $chunkPath = "uploads/chunks/{$fileId}/{$chunk}.part";
            fwrite($targetFile, $disk->get($chunkPath));
        }

        // Carregar o arquivo final para o S3
        $targetFileStream = stream_get_meta_data($targetFile)['uri'];
        $disk->put($targetFilePath, file_get_contents($targetFileStream));

        // Remover os chunks após a fusão
        Storage::disk('s3')->deleteDirectory("uploads/chunks/{$fileId}");

        return response()->json(['file_path' => $targetFilePath]);
    }

    public function revert(Request $request)
    {
        $fileId = $request->getContent();
        Storage::disk('s3')->deleteDirectory("uploads/chunks/{$fileId}");

        return response()->json(['status' => 'success']);
    }
}
