<?php
session_start();
session_unset();
?>
<!DOCTYPE html>
<html lang="cs">
<head><meta charset="UTF-8"><title>Studium - Krok 1</title>
<link rel="stylesheet" href="../index.css">
</head>
<body>
    <h1>Studium - Krok 1: Obor</h1>
    <form action="krok2.php" method="post">
        <p>Vyberte váš studijní obor:</p>
        <label><input type="radio" name="obor" value="Informatika" checked> Informatika</label><br>
        <label><input type="radio" name="obor" value="Ekonomie"> Ekonomie</label><br>
        <label><input type="radio" name="obor" value="Medicína"> Medicína</label><br><br>
        <button type="submit">Další</button>
    </form>
</body>
</html>
