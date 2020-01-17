<?php

require __DIR__ . "/../vendor/autoload.php";


use Produtil\Guestbook;

// Date must be YYYY-mm-dd
// Guestbook::getVisitationsByPeriod(start, end)

$result = Guestbook::getVisitationsByPeriod('2020-01-17', '2020-01-17');

header('Content-Type: application/json');

echo json_encode($result);