<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Tourist Site'))</title>

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-…"
      crossorigin="anonymous"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/review-modal.js'])
    @stack('styles')
</head>
<body class="bg-light font-sans">

    <div id="app">
        @yield('content')
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-…"
      crossorigin="anonymous"
    ></script>

    @stack('scripts')
</body>
</html>
