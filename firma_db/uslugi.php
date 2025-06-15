<?php
require 'db.php';
$db = new Database();
$conn = $db->connect();

$raporty = $conn->query("SELECT id_raportu FROM raport_fiskalny")->fetchAll(PDO::FETCH_ASSOC);
$samochody = $conn->query("SELECT id_samochodu, nr_rejestracyjny FROM samochod")->fetchAll(PDO::FETCH_ASSOC);
$pracownicy = $conn->query("SELECT id_pracownika, imie, nazwisko FROM pracownik")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO usluga (id_raportu, id_samochodu, id_pracownika, rodzaj_uslugi, opis, koszt, cena, data_uslugi)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['id_raportu'],
        $_POST['id_samochodu'],
        $_POST['id_pracownika'],
        $_POST['rodzaj_uslugi'],
        $_POST['opis'],
        $_POST['koszt'],
        $_POST['cena'],
        $_POST['data_uslugi']
    ]);
    header("Location: uslugi.php");
}

$uslugi = $conn->query("SELECT * FROM usluga ORDER BY id_uslugi")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<main>
    <h2>Usługi</h2>
    <form method="post">
        <select name="id_raportu">
            <option value="">Raport (opcjonalnie)</option>
            <?php foreach ($raporty as $r): ?>
                <option value="<?= $r['id_raportu'] ?>"><?= $r['id_raportu'] ?></option>
            <?php endforeach; ?>
        </select>
        <select name="id_samochodu" required>
            <option value="">Wybierz samochód</option>
            <?php foreach ($samochody as $s): ?>
                <option value="<?= $s['id_samochodu'] ?>"><?= $s['nr_rejestracyjny'] ?></option>
            <?php endforeach; ?>
        </select>
        <select name="id_pracownika">
            <option value="">Pracownik (opcjonalnie)</option>
            <?php foreach ($pracownicy as $p): ?>
                <option value="<?= $p['id_pracownika'] ?>"><?= $p['imie'] ?> <?= $p['nazwisko'] ?></option>
            <?php endforeach; ?>
        </select>
        <input name="rodzaj_uslugi" placeholder="Rodzaj usługi" required>
        <textarea name="opis" placeholder="Opis usługi" required></textarea>
        <input name="koszt" type="number" step="0.01" placeholder="Koszt" required>
        <input name="cena" type="number" step="0.01" placeholder="Cena" required>
        <input name="data_uslugi" type="date" required>
        <button type="submit">Dodaj usługę</button>
    </form>

    <h3>Lista usług:</h3>
    <ul>
        <?php foreach ($uslugi as $u): ?>
            <li><?= $u['rodzaj_uslugi'] ?> – <?= $u['opis'] ?> (<?= $u['data_uslugi'] ?>): <?= $u['cena'] ?> zł</li>
        <?php endforeach; ?>
    </ul>
</main>
<?php include 'includes/footer.php'; ?>
