<?php
require 'db.php';
$db = new Database();
$conn = $db->connect();

$uslugi = $conn->query("SELECT id_uslugi, rodzaj_uslugi, cena FROM usluga")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO platnosc (id_uslugi, kwota, metoda_platnosci, data_platnosci)
                            VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['id_uslugi'],
        $_POST['kwota'],
        $_POST['metoda_platnosci'],
        $_POST['data_platnosci']
    ]);
    header("Location: platnosci.php");
}

$platnosci = $conn->query("SELECT p.*, u.rodzaj_uslugi FROM platnosc p
                           JOIN usluga u ON p.id_uslugi = u.id_uslugi
                           ORDER BY id_platnosci")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<main>
    <h2>Płatności</h2>
    <form method="post">
        <select name="id_uslugi" required>
            <option value="">Wybierz usługę</option>
            <?php foreach ($uslugi as $u): ?>
                <option value="<?= $u['id_uslugi'] ?>"><?= htmlspecialchars($u['rodzaj_uslugi']) ?> – <?= $u['cena'] ?> zł</option>
            <?php endforeach; ?>
        </select>
        <input name="kwota" type="number" step="0.01" placeholder="Kwota" required>
        <select name="metoda_platnosci" required>
            <option value="">Metoda płatności</option>
            <option value="gotówka">Gotówka</option>
            <option value="karta">Karta</option>
            <option value="przelew">Przelew</option>
        </select>
        <input name="data_platnosci" type="date" required>
        <button type="submit">Dodaj płatność</button>
    </form>

    <h3>Lista płatności:</h3>
    <ul>
        <?php foreach ($platnosci as $p): ?>
            <li><?= $p['rodzaj_uslugi'] ?> – <?= $p['kwota'] ?> zł, <?= $p['metoda_platnosci'] ?> (<?= $p['data_platnosci'] ?>)</li>
        <?php endforeach; ?>
    </ul>
</main>
<?php include 'includes/footer.php'; ?>
