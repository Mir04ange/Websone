<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['typ'] = $_POST['typ'];
}
?>
<!DOCTYPE html>
<html lang="cs">
<head><meta charset="UTF-8"><title>Studium - Krok 3</title></head>
<body>
    <h1>Studium - Krok 3: Kredity</h1>
    <form action="vysledek.php" method="post">
        <p>Zadejte údaje o kreditech:</p>
        Počet kreditů nutných pro splnění celého oboru: <input type="number" name="celkem" step="0.1" required><br>
        Průměrný počet kreditů za semestr: <input type="number" name="prumer" step="0.1" required><br><br>
        <button type="submit">Zobrazit výsledek</button>
    </form>
</body>
</html>
