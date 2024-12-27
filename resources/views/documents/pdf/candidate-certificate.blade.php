<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado</title>
    <style>
        /* Garante que o conteúdo ocupe exatamente o tamanho de um A4 em paisagem */
        @page {
            size: 3508px 2480px;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(180deg, #030911 0%, #24405C 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3508px;
            height: 2480px;
            overflow: hidden;
            color: white;
            text-align: center;
        }

        .certificado {
            position: relative;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }

        .logo-fundo {
            position: absolute;
            transform: translate(-40%, -3%);
            opacity: 0.1;
            width: 3300px;
            height: auto;
        }

        .logo-principal {
            margin-top: 100px;
            width: 700px;
            height: auto;
        }

        .titulo {
            font-size: 300px;
            font-weight: bold;
            margin: 150px 0 130px;
        }

        .texto {
            font-size: 96px;
            line-height: 1.5;
            margin: 0 300px;
        }

        .texto span {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="certificado">
        <!-- Logo ao fundo -->
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Fundo" class="logo-fundo">

        <!-- Logo principal -->
        <img src="{{ asset('assets/img/GrowthFlixLogo.png') }}" alt="GrowthFlix Logo" class="logo-principal">

        <!-- Título -->
        <h1 class="titulo">CERTIFICADO</h1>

        <!-- Texto -->
        <p class="texto">
            Certificamos a conclusão de <span> {{ $name }} </span> na GROWTH FLIX, plataforma de cursos online, se
            tornando um especialista em proteção veicular, demonstrando engajamento e interesse no enriquecimento de
            conhecimento.
        </p>
    </div>
</body>

</html>
