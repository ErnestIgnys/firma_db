<?php
require 'db.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suma = $conn->query("SELECT SUM(kwota) AS laczna_kwota FROM platnosc")->fetch(PDO::FETCH_ASSOC);
    $stmt = $conn->prepare("INSERT INTO raport_fiskalny (data_raportu, suma)
                            VALUES (?, ?)");
    $stmt->execute([
        date('Y-m-d'),
        $suma['laczna_kwota']
    ]);
    header("Location: raporty.php");
}

$raporty = $conn->query("SELECT * FROM raport_fiskalny ORDER BY id_raportu DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<main>
    <h2>Raporty fiskalne</h2>
    <form method="post">
        <button type="submit">Wygeneruj nowy raport</button>
    </form>

    <h3>Lista raportów:</h3>
    <ul>
        <?php foreach ($raporty as $r): ?>
            <li>Raport #<?= $r['id_raportu'] ?> – <?= $r['data_raportu'] ?> – Suma: <?= $r['suma'] ?> zł</li>
        <?php endforeach; ?>
    </ul>
</main>
<?php include 'includes/footer.php'; ?>
