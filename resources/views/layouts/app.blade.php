<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZoneTwo</title>
    <link rel="icon" href="{{ asset('images/logo-last.png') }}" type="image/png">
    <link rel="preload" href="{{ asset('fronts/CRONDERegular.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fronts/CRONDEItalic.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @livewireStyles
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        @font-face {
            font-family: 'Cronde';
            src: url('{{ asset("fronts/CRONDERegular.woff2") }}') format('woff2'),
                 url('{{ asset("fronts/CRONDERegular.woff") }}') format('woff'),
                 url('{{ asset("fronts/CRONDERegular.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: block;
        }
        @font-face {
            font-family: 'Cronde';
            src: url('{{ asset("fronts/CRONDEItalic.woff2") }}') format('woff2'),
                 url('{{ asset("fronts/CRONDEItalic.woff") }}') format('woff'),
                 url('{{ asset("fronts/CRONDEItalic.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: italic;
            font-display: block;
        }
        .font-aesthetic {
            font-family: 'Cronde', serif;
            font-weight: 900;
            letter-spacing: 0.05em;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
    </style>
</head>
<body class="antialiased bg-white text-black">
    @yield('content')
    @livewireScripts
</body>
</html>
