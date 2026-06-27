<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Átrio Admin — Verificação</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-sm">
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-gray-800">Verificação em duas etapas</h1>
            <p class="text-sm text-gray-500 mt-1">Digite o código de 6 dígitos do seu app autenticador.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.2fa.verify') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-gray-600 mb-1">Código</label>
                <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code"
                       maxlength="6" autofocus required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-center text-lg tracking-[0.4em] focus:outline-none focus:ring-2 focus:ring-gray-800"
                       placeholder="000000">
            </div>

            <button type="submit"
                    class="w-full bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg px-4 py-2 transition">
                Verificar
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('admin.login') }}" class="text-xs text-gray-400 hover:text-gray-600">Cancelar e voltar ao login</a>
        </div>
    </div>

</body>
</html>
