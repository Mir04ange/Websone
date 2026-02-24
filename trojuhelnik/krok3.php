<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['a'] = (int)$_POST['a'];
    $_SESSION['b'] = (int)$_POST['b'];
    $_SESSION['c'] = (int)$_POST['c'];
}
?>
<!DOCTYPE html>
<html lang="cs">
<head><link rel="stylesheet" href="../index.css">
<meta charset="UTF-8"><title>Trojúhelník - Krok 3</title></head>
<body>
    <h1>Trojúhelník - Krok 3: Test</h1>
    <form action="vysledek.php" method="post">
        <p>Vyberte test pro trojúhelník:</p>
        <label><input type="radio" name="test_type" value="pravoúhlý" checked> Je pravoúhlý?</label><br>
        <label><input type="radio" name="test_type" value="rovnostranný"> Je rovnostranný?</label><br><br>
        <button type="submit">Zobrazit výsledek</button>
    </form>
</body>
</html>
