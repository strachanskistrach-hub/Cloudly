# Cloudly – Aplikacja pogodowa

Cloudly to aplikacja webowa napisana w języku PHP oraz JavaScript, która wyświetla 3-dniową prognozę pogody dla wybranego miasta.
Dane pobierane są z API serwisu OpenWeatherMap. 

Użytkownik wpisuje nazwę miasta, a aplikacja wyświetla:
 - Średnią temperaturę w °C
 - Opis pogody
 - Ciśnienie atmosferyczne
 - Prędkość wiatru
 - Ikonę pogodową

## Jak uruchomić projekt

1. Pobierz repozytorium z GitHub.
2. Skopiuj plik config.example.php do config.php.
3. Wklej swój klucz API z OpenWeatherMap do pliku config.php: 
    $apiKey = 'TWOJ_KLUCZ_API';
4. Umieść projekt w folderze htdocs (np. XAMPP).
5. Uruchom serwer i otwórz w przeglądarce:
    http://localhost/cloudly/weather.php
