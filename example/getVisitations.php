<?php

require __DIR__ . "/../vendor/autoload.php";

use Produtil\Guestbook;

$result = Guestbook::getVisitations();

header('Content-Type: application/json');

echo json_encode($result);