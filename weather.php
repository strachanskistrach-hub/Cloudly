<?php
require 'config.php';

function fetchWeatherForecast($city) {
    global $apiKey;
    $city = urlencode(trim($city));
    $url = "https://api.openweathermap.org/data/2.5/forecast?q={$city}&units=metric&appid={$apiKey}&lang=pl";

    $response = @file_get_contents($url);
    if (!$response) return false;

    $data = json_decode($response, true);
    if (isset($data['cod']) && $data['cod'] != "200") return $data;

    $days = [];
    foreach ($data['list'] as $item) {
        $date = substr($item['dt_txt'], 0, 10);
        if (!isset($days[$date])) {
            $days[$date] = [];
        }
        $days[$date][] = $item;
    }

    $forecast = [];
    $count = 0;
    foreach ($days as $date => $items) {
        if ($count > 2) break;

        $sumTemp = 0;
        $sumPressure = 0;
        $sumWind = 0;

        foreach ($items as $i) {
            $sumTemp += $i['main']['temp'];
            $sumPressure += $i['main']['pressure'];
            $sumWind += $i['wind']['speed'];
        }

        $n = count($items);
        $forecast[$date] = [
            'temp' => round($sumTemp / $n, 1),
            'pressure' => round($sumPressure / $n, 1),
            'wind' => round($sumWind / $n, 1),
            'icon' => $items[0]['weather'][0]['icon'],
            'description' => $items[0]['weather'][0]['description'],
        ];

        $count++;
    }

    return [
        'city' => $data['city']['name'],
        'country' => $data['city']['country'],
        'forecast' => $forecast
    ];
}

if (empty($_GET['city'])) {
    echo '<p class="error">Nie wpisano miasta.</p>';
    exit;
}

$city = $_GET['city'];
$weather = fetchWeatherForecast($city);

if (!$weather || isset($weather['cod'])) {
    echo '<p class="error">Nie znaleziono miasta lub błąd API.</p>';
    exit;
}

echo "<div class='weather'>
    <h2>{$weather['city']}, {$weather['country']}</h2>
    <div class='forecast-row'>";

foreach ($weather['forecast'] as $date => $day) {
    $iconUrl = "http://openweathermap.org/img/wn/{$day['icon']}@2x.png";
    echo "<div class='forecast-day'>
        <h3>{$date}</h3>
        <img src='{$iconUrl}' alt='Pogoda'>
        <p>Średnia temperatura: {$day['temp']}°C</p>
        <p>Pogoda: {$day['description']}</p>
        <p>Ciśnienie: {$day['pressure']} hPa</p>
        <p>Wiatr: {$day['wind']} m/s</p>
    </div>";
}

echo "</div></div>";
?>