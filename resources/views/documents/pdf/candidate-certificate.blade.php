<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg border-4 border-blue-600 p-10">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-blue-600 uppercase">Certificado de Conclusão</h1>
            <p class="text-lg text-gray-600 mt-2">Este certificado é concedido a</p>
            <h2 class="text-3xl font-semibold mt-4 text-gray-800">{{ $name }}</h2>
            <p class="text-lg text-gray-600 mt-2">
                Por concluir com sucesso todos os cursos</span>
            </p>
        </div>

        <div class="flex justify-between items-center mt-16">
            <div class="text-center">
                <p class="text-sm text-gray-600">Data</p>
                <p class="text-lg font-semibold text-gray-800">{{ date('d/m/Y') }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600">Assinatura</p>
                <div class="mt-4 border-t-2 border-gray-800 w-48 mx-auto"></div>
            </div>
        </div>
    </div>
</body>
</html>
