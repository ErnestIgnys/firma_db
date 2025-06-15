<?php
require 'db.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("INSERT INTO klient (imie, nazwisko, telefon, email) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['imie'], $_POST['nazwisko'], $_POST['telefon'], $_POST['email']]);
    header('Location: klienci.php');
}

$klienci = $conn->query("SELECT * FROM klient ORDER BY id_klienta")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<main>
    <h2>Klienci</h2>
    <form method="post">
        <input name="imie" placeholder="Imię" required>
        <input name="nazwisko" placeholder="Nazwisko" required>
        <input name="telefon" placeholder="Telefon" required>
        <input name="email" placeholder="Email" required>
        <button type="submit">Dodaj klienta</button>
    </form>

    <h3>Lista klientów:</h3>
    <ul>
        <?php foreach ($klienci as $klient): ?>
            <li><?= htmlspecialchars($klient['imie']) ?> <?= htmlspecialchars($klient['nazwisko']) ?> - <?= $klient['telefon'] ?> (<?= $klient['email'] ?>)</li>
        <?php endforeach; ?>
    </ul>
</main>
<?php include 'includes/footer.php'; ?>
