<?php
require 'db.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO pracownik (imie, nazwisko, telefon, stanowisko)
                            VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['imie'],
        $_POST['nazwisko'],
        $_POST['telefon'],
        $_POST['stanowisko']
    ]);
    header("Location: pracownicy.php");
}

$pracownicy = $conn->query("SELECT * FROM pracownik ORDER BY id_pracownika")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<main>
    <h2>Pracownicy</h2>
    <form method="post">
        <input name="imie" placeholder="Imię" required>
        <input name="nazwisko" placeholder="Nazwisko" required>
        <input name="telefon" placeholder="Telefon" required pattern="[0-9]{9}" title="Wprowadź 9-cyfrowy numer telefonu">
        <input name="stanowisko" placeholder="Stanowisko" required>
        <button type="submit">Dodaj pracownika</button>
    </form>

    <h3>Lista pracowników:</h3>
    <ul>
        <?php foreach ($pracownicy as $p): ?>
            <li>
                ID: <?= $p['id_pracownika'] ?> |
                <?= htmlspecialchars($p['imie']) ?> <?= htmlspecialchars($p['nazwisko']) ?> |
                tel: <?= htmlspecialchars($p['telefon']) ?> |
                stanowisko: <?= htmlspecialchars($p['stanowisko']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</main>
<?php include 'includes/footer.php'; ?>
