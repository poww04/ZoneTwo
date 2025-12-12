<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZoneTwo</title>
        <link rel="icon" href="{{ asset('images/logo-last.png') }}" type="image/png">
    @livewireStyles

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="antialiased bg-gray-100 text-gray-800">
    @yield('content')
        @livewireScripts

</body>
</html>