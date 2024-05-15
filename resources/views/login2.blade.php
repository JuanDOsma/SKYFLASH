<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Enlaces a estilos CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-blue-900">
<div class="flex justify-center items-center h-screen">
    <div class="bg-blue-500 p-8 rounded shadow-md w-full max-w-sm">
        <h2 class="text-2xl mb-4 text-center text-white">Iniciar sesión</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-semibold mb-2 text-white">Correo electrónico</label>
                <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-300" required autofocus>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold mb-2 text-white">Contraseña</label>
                <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-300" required>
            </div>
            <button type="submit" class="w-full bg-blue-700 text-white rounded py-2 px-4 hover:bg-blue-600 focus:outline-none">Ingresar</button>
        </form>
        @if ($errors->any())
            <div class="text-red-500 mt-4">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>
</div>
</body>
</html>
