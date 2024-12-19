<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['subject'] }}</title>
</head>
<body>
    <p><strong>Nome:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['from'] }}</p>
    <p><strong>Mensagem:</strong></p>
    <p>{{ $data['message'] }}</p>
</body>
</html>
