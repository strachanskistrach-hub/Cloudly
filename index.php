<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Cloudly</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <h1>Cloudly – prognoza pogody</h1>
    </header>

    <form id="weatherForm">
        <input type="text" name="city" placeholder="Wpisz miasto..." required id="cityInput" autocomplete="on">
        <button type="submit">Sprawdź</button>
    </form>

    <div id="loading" style="display:none;">
        <div class="spinner"></div> Ładowanie...
    </div>

    <div id="result"></div>

    <script src="assets/js/script.js"></script>
</body>
</html>