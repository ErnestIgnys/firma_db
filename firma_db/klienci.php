<?php
require 'db.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_id'])) {
        // Usuwanie klienta
        $stmt = $conn->prepare("DELETE FROM klient WHERE id_klienta = ?");
        $stmt->execute([$_POST['delete_id']]);
        header('Location: klienci.php');
        exit;
    } else {

        // Dodawnie
    $stmt = $conn->prepare("INSERT INTO klient (imie, nazwisko, telefon, email) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['imie'], $_POST['nazwisko'], $_POST['telefon'], $_POST['email']]);
    header('Location: klienci.php');
    exit;
    }
}

$klienci = $conn->query("SELECT * FROM klient ORDER BY id_klienta")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<main>
    <h2>Klienci</h2>
    <form method="post">
        <input name="imie" placeholder="Imię" required>
        <input name="nazwisko" placeholder="Nazwisko" required>
        <input name="telefon" placeholder="Telefon" required maxlength='15' pattern="\+?[0-9]{8,14}" title="Dozwolone tylko cyfry i opcjonalny znak + na początku. Numer musi mieć miedzy 9-15 znaków">
        <input type="email" name='email' placeholder="Email" required>
        <button type="submit">Dodaj klienta</button>
    </form>

    <h3>Lista klientów:</h3>
    <ul>
        <?php foreach ($klienci as $klient): ?>
            <li>
                <?= htmlspecialchars($klient['imie']) ?> <?= htmlspecialchars($klient['nazwisko']) ?> - <?= $klient['telefon'] ?> (<?= $klient['email'] ?>)
        
                <!-- Formularz usuwanie klienta-->
                 <form method="post" style="display:inline;" onsubmit="return confirm('Czy na pewno chesz usunąć tego kleinta?');">
                    <input type="hidden" name ="delete_id" value="<?= $klient['id_klienta'] ?>">
                    <button type="submit">Usuń</button>
                 </form>
            </li>
        <?php endforeach; ?>
    </ul>
</main>
<?php include 'includes/footer.php'; ?>
