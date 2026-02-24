<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['celkem'] = (float)$_POST['celkem'];
    $_SESSION['prumer'] = (float)$_POST['prumer'];
}

$obor = isset($_SESSION['obor']) ? $_SESSION['obor'] : '';
$typ = isset($_SESSION['typ']) ? $_SESSION['typ'] : '';
$celkem = isset($_SESSION['celkem']) ? $_SESSION['celkem'] : 0;
$prumer = isset($_SESSION['prumer']) ? $_SESSION['prumer'] : 0;
?>
<!DOCTYPE html>
<html lang="cs">
<head><link rel="stylesheet" href="../index.css">
<meta charset="UTF-8"><title>Studium - Výsledek</title></head>
<body >
    <h1>Studium - Výsledek</h1>
    <p>Studijní obor: <?php echo htmlspecialchars($obor); ?></p>
    <p>Typ studijního plánu: <?php echo htmlspecialchars($typ); ?></p>
    <p>Celkový počet kreditů: <?php echo $celkem; ?></p>
    <p>Průměrný počet kreditů za semestr: <?php echo $prumer; ?></p>
    <?php
    if ($prumer > 0) {
        $doba = $celkem / $prumer;
        echo "<p><strong>Očekávaná doba dokončení studia: " . round($doba, 2) . " semestrů</strong></p>";
    } else {
        echo "<p>Chyba: Průměrný počet kreditů musí být větší než 0.</p>";
    }
    ?>
    <p><a href="krok1.php">Začít znovu</a></p>
</body>
</html>
