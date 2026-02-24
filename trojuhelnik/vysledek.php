<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['test_type'] = $_POST['test_type'];
}

$a = isset($_SESSION['a']) ? $_SESSION['a'] : 0;
$b = isset($_SESSION['b']) ? $_SESSION['b'] : 0;
$c = isset($_SESSION['c']) ? $_SESSION['c'] : 0;
$ops = isset($_SESSION['operations']) ? $_SESSION['operations'] : [];
$test = isset($_SESSION['test_type']) ? $_SESSION['test_type'] : '';

function is_triangle($a, $b, $c) {
    return ($a > 0 && $b > 0 && $c > 0 && ($a + $b > $c) && ($a + $c > $b) && ($b + $c > $a));
}
?>
<!DOCTYPE html>
<html lang="cs">
<head><link rel="stylesheet" href="../index.css">
<meta charset="UTF-8"><title>Trojúhelník - Výsledek</title></head>
<body>
    <h1>Trojúhelník - Výsledek</h1>
    <p>Zadané strany: a=<?php echo $a; ?>, b=<?php echo $b; ?>, c=<?php echo $c; ?></p>
    <?php
    if (is_triangle($a, $b, $c)) {
        echo "<p>Jedná se o trojúhelník.</p>";
        if (in_array('obvod', $ops)) {
            echo "<p>Obvod: " . ($a + $b + $c) . "</p>";
        }
        if (in_array('obsah', $ops)) {
            $s = ($a + $b + $c) / 2;
            $obsah = sqrt($s * ($s - $a) * ($s - $b) * ($s - $c));
            echo "<p>Obsah: " . round($obsah, 2) . "</p>";
        }
        if ($test == 'pravoúhlý') {
            $strany = [$a, $b, $c]; sort($strany);
            $is_right = (pow($strany[0], 2) + pow($strany[1], 2) == pow($strany[2], 2));
            echo "<p>Test na pravoúhlost: " . ($is_right ? "Ano, je pravoúhlý." : "Ne, není pravoúhlý.") . "</p>";
        } else {
            $is_equi = ($a == $b && $b == $c);
            echo "<p>Test na rovnostrannost: " . ($is_equi ? "Ano, je rovnostranný." : "Ne, není rovnostranný.") . "</p>";
        }
    } else {
        echo "<p>Zadané strany netvoří trojúhelník.</p>";
    }
    ?>
    <p><a href="krok1.php">Začít znovu</a></p>
</body>
</html>
