<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Aprovado - GrowthFlix</title>
</head>
<body style="background-color: #f3f4f6; padding-top: 2rem; padding-bottom: 2rem; font-family: 'system-ui', sans-serif;">

    <div style="max-width: 32rem; margin-left: auto; margin-right: auto; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06); overflow: hidden;">
        <div style="background-color: #2563eb; color: #ffffff; text-align: center; padding-top: 1.5rem; padding-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; justify-content: flex-start; width: 120px; margin: 0 auto;">
                <img src="{{ asset('assets/img/GrowthFlixLogo.png') }}" alt="Logo Growth" style="max-width: 100%; height: auto;">
            </div>
            <h1 style="font-size: 1.5rem; line-height: 2rem; font-weight: 600; margin: 0;">Bem-vindo ao GrowthFlix!</h1>
        </div>

        <div style="padding-left: 1.5rem; padding-right: 1.5rem; padding-top: 2rem; padding-bottom: 2rem;">
            <p style="color: #1f2937; font-size: 1.125rem; line-height: 1.75rem; margin-bottom: 1rem;">Olá, {{ $candidate->name ?? 'Samuel' }},</p>
            <p style="color: #4b5563; font-size: 1rem; line-height: 1.5rem; margin-bottom: 1.5rem;">É com grande alegria que informamos que seu cadastro foi aprovado! 🎉</p>
            <p style="color: #4b5563; font-size: 1rem; line-height: 1.5rem; margin-bottom: 1.5rem;">Agora você pode acessar nossa plataforma e aproveitar todos os recursos disponíveis para o seu crescimento.</p>
            <a href="{{ url('candidate/login') }}" style="display: inline-block; background-color: #2563eb; color: #ffffff; font-size: 1.125rem; line-height: 1.75rem; padding-top: 0.5rem; padding-bottom: 0.5rem; padding-left: 1.5rem; padding-right: 1.5rem; border-radius: 0.375rem; text-decoration: none; transition: background-color 0.2s ease-in-out;" onmouseover="this.style.backgroundColor='#3b82f6'" onmouseout="this.style.backgroundColor='#2563eb'">Entrar na Plataforma</a>
        </div>

        <div style="background-color: #f3f4f6; text-align: center; padding-top: 1rem; padding-bottom: 1rem;">
            <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.25rem; margin: 0;">Obrigado por escolher o GrowthFlix! Se precisar de ajuda, entre em contato com nosso suporte.</p>
        </div>
    </div>

</body>
</html>
