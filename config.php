<?php
if (file_exists(__DIR__ . '/.env')) {
    $apiKey = trim(file_get_contents(__DIR__ . '/.env'));
} else {
    $apiKey = '';
}