<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Aprovado - GrowthFlix</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-8">

    <div class="max-w-lg mx-auto bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
        <div class="bg-blue-600 text-white text-center py-6">
            <div class="flex items-center justify-start w-[120px]">
                <x-application-logo />
                <h1 class="text-2xl font-semibold">Bem-vindo ao GrowthFlix!</h1>
            </div>
        </div>

        <div class="px-6 py-8">
            <p class="text-gray-800 text-lg mb-4">Olá, {{ $user->name ?? $name }},</p>
            <p class="text-gray-600 text-base mb-6">É com grande alegria que informamos que seu cadastro foi aprovado! 🎉</p>
            <p class="text-gray-600 text-base mb-6">Agora você pode acessar nossa plataforma e aproveitar todos os recursos disponíveis para o seu crescimento.</p>
            <a href="{{ url('candidate/login') }}" class="inline-block bg-blue-600 text-white text-lg py-2 px-6 rounded-md hover:bg-blue-500 transition">Entrar na Plataforma</a>
        </div>

        <div class="bg-gray-100 text-center py-4">
            <p class="text-gray-500 text-sm">Obrigado por escolher o GrowthFlix! Se precisar de ajuda, entre em contato com nosso suporte.</p>
        </div>
    </div>

</body>
</html>
