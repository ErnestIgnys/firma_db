<?php
require 'db.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // robimy nowy pusty raport z datą
    $stmt = $conn->prepare("INSERT INTO raport_fiskalny (data_raportu) VALUES (?)");
    $stmt->execute([date('Y-m-d')]);

    // pobieramy id do nowo utworzonego raportu
    $raportId = $conn->lastInsertId();

    // przypisujemt to id do wszystkich płatności i usług (które nie miały jeszcze raportu)
    $conn->prepare("UPDATE platnosc SET id_raportu = ? WHERE id_raportu IS NULL")->execute([$raportId]);
    $conn->prepare("UPDATE usluga SET id_raportu = ? WHERE id_raportu IS NULL")->execute([$raportId]);

    // Teraz trigger zaktualizuje suma_przychodow i suma_kosztow automatycznie

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
            <li>
                Raport #<?= $r['id_raportu'] ?> – <?= $r['data_raportu'] ?><br>
                Suma przychodów: <?= $r['suma_przychodow'] ?? 'brak danych' ?> zł<br>
                Suma kosztów: <?= $r['suma_kosztow'] ?? 'brak danych' ?> zł<br>
                Bilans: <?= $r['bilans_finansowy'] ?? 'brak danych' ?> zł
            </li>
        <?php endforeach; ?>
    </ul>
</main>
<?php include 'includes/footer.php'; ?>
