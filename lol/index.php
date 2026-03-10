<?php
session_start();

if (!isset($_SESSION['page'])) {
    $_SESSION['page'] = 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bank'])) {
        $_SESSION['bank'] = $_POST['bank'];
        $_SESSION['page'] = 2;
    } elseif (isset($_POST['cardType'])) {
        $_SESSION['cardType'] = $_POST['cardType'];
        $_SESSION['page'] = 3;
    } elseif (isset($_POST['income'])) {
        $_SESSION['income'] = $_POST['income'];
        $_SESSION['expenses'] = $_POST['expenses'];
        $_SESSION['savings'] = $_POST['savings'];
        $_SESSION['page'] = 4;
    } elseif (isset($_POST['back'])) {
        $_SESSION['page'] = max(1, $_SESSION['page'] - 1);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 50px auto; padding: 20px; }
        form { background: #f5f5f5; padding: 20px; border-radius: 5px; }
        h2 { color: #333; }
        label { display: block; margin: 10px 0; }
        input, select { padding: 8px; width: 100%; margin: 5px 0; }
        button { padding: 10px 20px; margin: 10px 5px 10px 0; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>

<?php
if ($_SESSION['page'] === 1) { ?>
    <form method="POST">
        <h2>Výběr banky</h2>
        <select name="bank" required>
            <option value="">Vyberte banku</option>
            <option value="Česká spořitelna">Česká spořitelna</option>
            <option value="Komerční banka">Komerční banka</option>
            <option value="Moneta">Moneta</option>
        </select>
        <button type="submit">Další</button>
    </form>
<?php } elseif ($_SESSION['page'] === 2) { ?>
    <form method="POST">
        <h2>Typ kreditní karty</h2>
        <label><input type="radio" name="cardType" value="Standardní" required> Standardní</label>
        <label><input type="radio" name="cardType" value="Zlatá"> Zlatá</label>
        <label><input type="radio" name="cardType" value="Platinová"> Platinová</label>
        <button type="submit">Další</button>
        <button type="submit" name="back">Zpět</button>
    </form>
<?php } elseif ($_SESSION['page'] === 3) { ?>
    <form method="POST">
        <h2>Vaše finanční údaje</h2>
        <label>Měsíční příjem: <input type="number" name="income" required></label>
        <label>Měsíční výdaje: <input type="number" name="expenses" required></label>
        <label>Aktuální úspory: <input type="number" name="savings" required></label>
        <button type="submit">Odeslat</button>
        <button type="submit" name="back">Zpět</button>
    </form>
<?php } elseif ($_SESSION['page'] === 4) {
    $creditLimit = ($_SESSION['income'] - $_SESSION['expenses']) * 2; ?>
    <h2>Shrnutí vaší žádosti</h2>
    <p><strong>Banka:</strong> <?php echo $_SESSION['bank']; ?></p>
    <p><strong>Typ karty:</strong> <?php echo $_SESSION['cardType']; ?></p>
    <p><strong>Měsíční příjem:</strong> <?php echo $_SESSION['income']; ?> Kč</p>
    <p><strong>Měsíční výdaje:</strong> <?php echo $_SESSION['expenses']; ?> Kč</p>
    <p><strong>Aktuální úspory:</strong> <?php echo $_SESSION['savings']; ?> Kč</p>
    <p><strong>Limit kreditní karty:</strong> <?php echo $creditLimit; ?> Kč</p>
    <form method="POST">
        <button type="submit" name="back">Zpět</button>
        <button type="submit" onclick="window.location='index.php'; return false;">Nová žádost</button>
    </form>
<?php } ?>

</body>
</html>
