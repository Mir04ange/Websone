<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['g_sirka'])) {
    $_SESSION['g_sirka'] = (float)$_POST['g_sirka'];
    $_SESSION['g_delka'] = (float)$_POST['g_delka'];
}

$p_sirka = isset($_SESSION['p_sirka']) ? $_SESSION['p_sirka'] : 0;
$p_delka = isset($_SESSION['p_delka']) ? $_SESSION['p_delka'] : 0;
$d_sirka = isset($_SESSION['d_sirka']) ? $_SESSION['d_sirka'] : 0;
$d_delka = isset($_SESSION['d_delka']) ? $_SESSION['d_delka'] : 0;
$has_garage = isset($_SESSION['has_garage']) ? $_SESSION['has_garage'] : false;
$g_sirka = isset($_SESSION['g_sirka']) ? $_SESSION['g_sirka'] : 0;
$g_delka = isset($_SESSION['g_delka']) ? $_SESSION['g_delka'] : 0;

$plocha_pozemku = $p_sirka * $p_delka;
$plocha_domu = $d_sirka * $d_delka;
$plocha_garaze = $has_garage ? ($g_sirka * $g_delka) : 0;
$plocha_travniku = $plocha_pozemku - $plocha_domu - $plocha_garaze;
?>
<!DOCTYPE html>
<html lang="cs">
<head><meta charset="UTF-8"><title>Trávník - Výsledek</title></head>
<body>
    <h1>Trávník - Výsledek</h1>
    <p>Plocha pozemku: <?php echo $plocha_pozemku; ?> m²</p>
    <p>Plocha domu: <?php echo $plocha_domu; ?> m²</p>
    <?php if ($has_garage): ?>
        <p>Plocha garáže: <?php echo $plocha_garaze; ?> m²</p>
    <?php endif; ?>
    <hr>
    <h2>Plocha k sekání (trávník): <?php echo max(0, $plocha_travniku); ?> m²</h2>
    <p><a href="krok1.php">Začít znovu</a></p>
</body>
</html>
