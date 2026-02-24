<?php
session_start();
session_unset();
?>
<!DOCTYPE html>
<html lang="cs">
<head><meta charset="UTF-8"><title>Trojúhelník - Krok 1</title></head>
<body>
    <h1>Trojúhelník - Krok 1: Operace</h1>
    <form action="krok2.php" method="post">
        <p>Vyberte operaci, kterou chcete určit:</p>
        <label><input type="checkbox" name="operations[]" value="obvod"> Obvod</label><br>
        <label><input type="checkbox" name="operations[]" value="obsah"> Obsah</label><br><br>
        <button type="submit">Další</button>
    </form>
</body>
</html>
