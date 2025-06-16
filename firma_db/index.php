<?php
require 'db.php';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serwis Samochodowy</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'includes/header.php'; 

// łączenie się z bazą
$database = new Database();
$conn = $database->connect();

// sprawdznie czy działa zapytanko
try {
    $query = $conn->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    $tables = $query->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>Lista tabel w bazie:</h3><ul>";
    foreach ($tables as $table) {
        echo "<li>" . htmlspecialchars($table['table_name']) . "</li>";
    }
    echo "</ul>";
} catch (PDOException $e) {
    echo "Błąd zapytania: " . $e->getMessage();
}
?>

<main>
    <h2>Witamy w systemie zarządzania serwisem</h2>
    <p>Wybierz sekcję z menu, aby kontynuować.</p>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>