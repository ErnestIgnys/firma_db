<?php
require_once 'db.php';

$database = new Database();
$conn = $database->connect();

if ($conn) {
    echo "Połączono z bazą danych!";
} else {
    echo "Nadal problem z połączeniem.";
}
?>