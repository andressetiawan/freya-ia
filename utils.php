<?php

define("HOST", "http://localhost/freya-ia/api/");

function component(string $path, array $props = [])
{
    extract($props);
    include __DIR__ . "/components/{$path}.php";
}

function formatDateShort($datetime)
{
    $date = new DateTime($datetime);
    return $date->format('j M y H:i');
}
