<!DOCTYPE html>
<html lang="ja" data-theme="{{ empty($datatheme) ? 'light' : $datatheme}}">
<head>
    <title>{{ $title }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    @php
        echo "<style>";
        require_once('SpiralPress/resources/css/bundle/bundle.php');
        echo "</style>";
    @endphp
    <script type="module" defer>
        @php
            require_once('SpiralPress/resources/script/bundle/bundle.php');
        @endphp
    </script>
</head>
<body>
    @include('components/alert')
    @yield('content')
</body>
</html>
