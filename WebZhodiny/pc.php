<?php
// Definice komponent a jejich cen
$komponenty = [
    'kancelar' => [
        'Intel i3' => 2500,
        '8 GB RAM' => 1200,
        'SSD 256 GB' => 1000,
        'Integrovaná grafika' => 0
    ],
    'hry' => [
        'AMD Ryzen 5' => 4500,
        '16 GB RAM' => 2400,
        'SSD 1 TB' => 2500,
        'GeForce RTX 4060' => 8500
    ],
    'grafika' => [
        'Intel i7' => 5500,
        '32 GB RAM' => 4500,
        'SSD 2 TB' => 4000,
        'NVIDIA Quadro P2200' => 9600
    ]
];

$vybrany_ucel = '';
$vybrane_komponenty_sestava = [];
$celkova_cena_sestavy = 0;
$jmeno_zakaznika = '';
$nazev_pc = '';

// Zpracování formuláře
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task']) && $_POST['task'] == 'pc') {
    $vybrany_ucel = $_POST['ucel'] ?? '';
    $jmeno_zakaznika = htmlspecialchars($_POST['jmeno_zakaznika'] ?? '');
    $nazev_pc = htmlspecialchars($_POST['nazev_pc'] ?? '');

    if (isset($_POST['komponenty_vyber']) && array_key_exists($vybrany_ucel, $komponenty)) {
        foreach ($_POST['komponenty_vyber'] as $komponenta) {
            if (isset($komponenty[$vybrany_ucel][$komponenta])) {
                $cena = $komponenty[$vybrany_ucel][$komponenta];
                $vybrane_komponenty_sestava[$komponenta] = $cena;
                $celkova_cena_sestavy += $cena;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Stavba PC</title>
    <!-- Styly jsou stejné jako v předchozím příkladu -->
</head>
<body>
    <!-- ÚKOL 10: PC -->
    <div class="task">
        <h2>10. Stavba vlastního počítače</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="task" value="pc">

            <h3>1. Zvolte účel použití</h3>
            <input type="radio" name="ucel" value="kancelar" required onchange="this.form.submit()"> Kancelářská práce  

            <input type="radio" name="ucel" value="hry" required onchange="this.form.submit()"> Hraní her  

            <input type="radio" name="ucel" value="grafika" required onchange="this.form.submit()"> Grafické práce  

            
            <?php if ($vybrany_ucel): ?>
                <h3>2. Vyberte komponenty pro: <?php echo ucfirst($vybrany_ucel); ?></h3>
                <?php foreach ($komponenty[$vybrany_ucel] as $nazev => $cena): ?>
                    <input type="checkbox" name="komponenty_vyber[]" value="<?php echo $nazev; ?>"> <?php echo $nazev; ?> (<?php echo $cena; ?> Kč)  

                <?php endforeach; ?>

                <h3>3. Zadejte jméno a název PC</h3>
                <p>Jméno zákazníka: <input type="text" name="jmeno_zakaznika" required></p>
                <p>Název PC: <input type="text" name="nazev_pc" required></p>
                  

                <button type="submit">Sestavit PC</button>
            <?php endif; ?>
        </form>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task']) && $_POST['task'] == 'pc' && $celkova_cena_sestavy > 0): ?>
        <div class="result">
            <h3>Rekapitulace sestavy</h3>
            <p><strong>Zákazník:</strong> <?php echo $jmeno_zakaznika; ?></p>
            <p><strong>Název PC:</strong> <?php echo $nazev_pc; ?></p>
            <p><strong>Účel:</strong> <?php echo ucfirst($vybrany_ucel); ?></p>
            <p><strong>Komponenty:</strong></p>
            <ul>
                <?php foreach ($vybrane_komponenty_sestava as $nazev => $cena): ?>
                    <li><?php echo $nazev; ?> (<?php echo $cena; ?> Kč)</li>
                <?php endforeach; ?>
            </ul>
            <hr>
            <p><strong>Celková cena sestavy: <?php echo $celkova_cena_sestavy; ?> Kč</strong></p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
