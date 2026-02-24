<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['operations'] = isset($_POST['operations']) ? $_POST['operations'] : [];
}
?>
<!DOCTYPE html>
<html lang="cs">
<head><meta charset="UTF-8"><title>Trojúhelník - Krok 2</title></head>
<body>
    <h1>Trojúhelník - Krok 2: Strany</h1>
    <form action="krok3.php" method="post">
        <p>Zadejte 3 celá čísla představující strany trojúhelníka:</p>
        Strana a: <input type="number" name="a" required><br>
        Strana b: <input type="number" name="b" required><br>
        Strana c: <input type="number" name="c" required><br><br>
        <button type="submit">Další</button>
    </form>
</body>
</html>
