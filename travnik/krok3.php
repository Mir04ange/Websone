<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['d_sirka'] = (float)$_POST['d_sirka'];
    $_SESSION['d_delka'] = (float)$_POST['d_delka'];
    $_SESSION['has_garage'] = isset($_POST['has_garage']);
}

if (!isset($_SESSION['has_garage']) || !$_SESSION['has_garage']) {
    header("Location: vysledek.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head><meta charset="UTF-8"><title>Trávník - Krok 3</title></head>
<body>
    <h1>Trávník - Krok 3: Garáž</h1>
    <form action="vysledek.php" method="post">
        <p>Zadejte rozměry obdélníkové garáže:</p>
        Šířka garáže (m): <input type="number" name="g_sirka" step="0.1" required><br>
        Délka garáže (m): <input type="number" name="g_delka" step="0.1" required><br><br>
        <button type="submit">Zobrazit výsledek</button>
    </form>
</body>
</html>
