<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['obor'] = $_POST['obor'];
}
?>
<!DOCTYPE html>
<html lang="cs">
<head><meta charset="UTF-8"><title>Studium - Krok 2</title></head>
<body>
    <h1>Studium - Krok 2: Typ plánu</h1>
    <form action="krok3.php" method="post">
        <p>Vyberte typ studijního plánu:</p>
        <select name="typ">
            <option value="Prezenční">Prezenční</option>
            <option value="Kombinovaný">Kombinovaný</option>
            <option value="Dálkový">Dálkový</option>
        </select><br><br>
        <button type="submit">Další</button>
    </form>
</body>
</html>
