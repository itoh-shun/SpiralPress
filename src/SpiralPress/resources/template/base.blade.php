<!DOCTYPE html>
<html lang="ja" data-theme="light">
<head>
    <title>{{ $title }}</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.x/dist/alpine.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/inline-code@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/simple-image@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/warning@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/link@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/text-variant-tune@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/raw@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/editorjs-alert@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@calumk/editorjs-columns@latest" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/editorjs-undo@latest" defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        @include('css/output.css.php');
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
