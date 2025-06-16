<?php
require 'db.php';
$db = new Database();
$conn = $db->connect();

$klienci = $conn->query("SELECT id_klienta, imie, nazwisko FROM klient")->fetchAll(PDO::FETCH_ASSOC);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        // Usuwanie samochodu
        $stmt = $conn->prepare("DELETE FROM samochod WHERE id_samochodu = ?");
        $stmt->execute([$_POST['delete_id']]);
        header("Location: samochody.php");
        exit;
    }
    // Pobierz i przygotuj zmienne
    $nr_rejestracyjny = trim($_POST['nr_rejestracyjny']);
    $rok = (int)$_POST['rok_produkcji'];

    // Sprawdź, czy taki nr rejestracyjny już istnieje
    $check = $conn->prepare("SELECT COUNT(*) FROM samochod WHERE nr_rejestracyjny = ?");
    $check->execute([$nr_rejestracyjny]);
    $exists = $check->fetchColumn();

    if ($exists > 0) {
        $error = 'Samochód z tym numerem rejestracyjnym już istnieje.';
    } elseif ($rok < 1900 || $rok > 2100) {
        $error = 'Błędny rok produkcji. Dozwolony zakres to 1900–2100.';
    } else {
        // Wstaw do bazy
        $stmt = $conn->prepare("INSERT INTO samochod (id_klienta, marka, model, nr_rejestracyjny, rok_produkcji)
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['id_klienta'],
            trim($_POST['marka']),
            trim($_POST['model']),
            $nr_rejestracyjny,
            $rok
        ]);
        header("Location: samochody.php");
        exit;
    }
}

$samochody = $conn->query("SELECT s.*, k.imie, k.nazwisko
                           FROM samochod s JOIN klient k ON s.id_klienta = k.id_klienta
                           ORDER BY id_samochodu")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>
<main>
    <h2>Samochody</h2>
    
    <?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

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
        <input name="rok_produkcji" type="number" placeholder="Rok produkcji" min="1900" max="2100" required>
        <button type="submit">Dodaj samochód</button>
    </form>

    <h3>Lista samochodów:</h3>
    <ul>
        <?php foreach ($samochody as $s): ?>
            <li>
                <?= $s['marka'] ?> <?= $s['model'] ?> (<?= $s['nr_rejestracyjny'] ?>), <?= $s['rok_produkcji'] ?> - <?= $s['imie'] ?> <?= $s['nazwisko'] ?>
                <form method="post" style="display:inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć ten samochód?');">
                <input type="hidden" name="delete_id" value="<?= $s['id_samochodu'] ?>">
                <button type="submit">Usuń</button>
            </form>
            </li>
        <?php endforeach; ?>
    </ul>
</main>
<?php include 'includes/footer.php'; ?>

