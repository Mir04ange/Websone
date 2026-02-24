<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['p_sirka'] = (float)$_POST['p_sirka'];
    $_SESSION['p_delka'] = (float)$_POST['p_delka'];
}
?>
<!DOCTYPE html>
<html lang="cs">
<head><link rel="stylesheet" href="../index.css">
<meta charset="UTF-8"><title>Trávník - Krok 2</title></head>
<body>
    <h1>Trávník - Krok 2: Dům</h1>
    <form action="krok3.php" method="post">
        <p>Zadejte rozměry obdélníkového domu:</p>
        Šířka domu (m): <input type="number" name="d_sirka" step="0.1" required><br>
        Délka domu (m): <input type="number" name="d_delka" step="0.1" required><br><br>
        <label><input type="checkbox" name="has_garage" value="1"> Garáž</label><br><br>
        <button type="submit">Další</button>
    </form>
</body>
</html>
