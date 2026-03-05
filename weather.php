<?php
require 'config.php';

function fetchWeatherForecast($city, $apiKey) {
    $city = urlencode(trim($city));
    $url = "https://api.openweathermap.org/data/2.5/forecast?q=$city&units=metric&appid=$apiKey&lang=pl";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false || $status != 200) {
        return false;
    }

    $data = json_decode($response, true);
    if (!$data || !isset($data['list'])) {
        return false;
    }

    $days = [];
    foreach ($data['list'] as $item) {
        $date = substr($item['dt_txt'], 0, 10);
        $days[$date][] = $item;
    }

    $forecast = [];
    $count = 0;

    foreach ($days as $date => $items) {
        if ($count >= 3) break;

        $temp = 0;
        $pressure = 0;
        $wind = 0;

        foreach ($items as $i) {
            $temp += $i['main']['temp'];
            $pressure += $i['main']['pressure'];
            $wind += $i['wind']['speed'];
        }

        $n = count($items);

        $forecast[$date] = [
            'temp' => round($temp / $n, 1),
            'pressure' => round($pressure / $n, 1),
            'wind' => round($wind / $n, 1),
            'icon' => $items[0]['weather'][0]['icon'],
            'description' => $items[0]['weather'][0]['description']
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
    echo "<p class='error'>Nie wpisano miasta.</p>";
    exit;
}

$weather = fetchWeatherForecast($_GET['city'], $apiKey);

if (!$weather) {
    echo "<p class='error'>Nie znaleziono miasta lub błąd API.</p>";
    exit;
}

echo "<div class='weather'>";
echo "<h2>{$weather['city']}, {$weather['country']}</h2>";
echo "<div class='forecast-row'>";

foreach ($weather['forecast'] as $date => $day) {
    $icon = "https://openweathermap.org/img/wn/{$day['icon']}@2x.png";

    echo "<div class='forecast-day'>
        <h3>$date</h3>
        <img src='$icon' alt='Pogoda'>
        <p>Średnia temperatura: {$day['temp']}°C</p>
        <p>Pogoda: {$day['description']}</p>
        <p>Ciśnienie: {$day['pressure']} hPa</p>
        <p>Wiatr: {$day['wind']} m/s</p>
    </div>";
}

echo "</div></div>";
?>