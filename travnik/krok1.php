<?php
session_start();
session_unset();
?>
<!DOCTYPE html>
<html lang="cs">
<head><link rel="stylesheet" href="../index.css">
<meta charset="UTF-8"><title>Trávník - Krok 1</title></head>
<body>
    <h1>Trávník - Krok 1: Pozemek</h1>
    <form action="krok2.php" method="post">
        <p>Zadejte rozměry obdélníkového pozemku:</p>
        Šířka pozemku (m): <input type="number" name="p_sirka" step="0.1" required><br>
        Délka pozemku (m): <input type="number" name="p_delka" step="0.1" required><br><br>
        <button type="submit">Další</button>
    </form>
</body>
</html>
