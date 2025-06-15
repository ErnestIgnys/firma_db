<?php
require 'db.php';
$db = new Database();
$conn = $db->connect();

$klienci = $conn->query("SELECT id_klienta, imie, nazwisko FROM klient")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO samochod (id_klienta, marka, model, nr_rejestracyjny, rok_produkcji)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['id_klienta'],
        $_POST['marka'],
        $_POST['model'],
        $_POST['nr_rejestracyjny'],
        $_POST['rok_produkcji']
    ]);
    header("Location: samochody.php");
}

$samochody = $conn->query("SELECT s.*, k.imie, k.nazwisko
                           FROM samochod s JOIN klient k ON s.id_klienta = k.id_klienta
                           ORDER BY id_samochodu")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<main>
    <h2>Samochody</h2>
    <form method="post">
        <select name="id_klienta" required>
            <option value="">Wybierz klienta</option>
            <?php foreach ($klienci as $k): ?>
                <option value="<?= $k['id_klienta'] ?>"><?= htmlspecialchars($k['imie'] . ' ' . $k['nazwisko']) ?></option>
            <?php endforeach; ?>
        </select>
        <input name="marka" placeholder="Marka" required>
        <input name="model" placeholder="Model" required>
        <input name="nr_rejestracyjny" placeholder="Nr rejestracyjny" maxlength="7" required>
        <input name="rok_produkcji" type="number" placeholder="Rok produkcji" required>
        <button type="submit">Dodaj samochód</button>
    </form>

    <h3>Lista samochodów:</h3>
    <ul>
        <?php foreach ($samochody as $s): ?>
            <li><?= $s['marka'] ?> <?= $s['model'] ?> (<?= $s['nr_rejestracyjny'] ?>), <?= $s['rok_produkcji'] ?> - <?= $s['imie'] ?> <?= $s['nazwisko'] ?></li>
        <?php endforeach; ?>
    </ul>
</main>
<?php include 'includes/footer.php'; ?>

