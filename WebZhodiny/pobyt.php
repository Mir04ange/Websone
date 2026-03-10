<?php
// Ceny
$ceny_ubytovani = [
    'standard' => 600,
    'balkon' => 800,
    'apartman' => 1200
];
$ceny_sluzeb = [
    'polopenze' => 300, // za osobu/noc
    'wellness' => 250,  // za osobu/den
    'parkovani' => 100  // za pobyt
];

// Výchozí hodnoty
$celkova_cena_pobytu = 0;
$vybrane_ubytovani_nazev = '';
$vybrane_sluzby_rekap = [];
$pocet_dnu = 0;
$pocet_osob = 0;

// Zpracování formuláře
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task']) && $_POST['task'] == 'pobyt') {
    $ubytovani_typ = $_POST['ubytovani'] ?? '';
    $pocet_dnu = (int)($_POST['pocet_dnu'] ?? 0);
    $pocet_osob = (int)($_POST['pocet_osob'] ?? 0);
    $vybrane_sluzby = $_POST['sluzby'] ?? [];

    if (array_key_exists($ubytovani_typ, $ceny_ubytovani) && $pocet_dnu > 0 && $pocet_osob > 0) {
        // Cena za ubytování
        $cena_za_ubytovani = $ceny_ubytovani[$ubytovani_typ] * $pocet_dnu;
        $celkova_cena_pobytu += $cena_za_ubytovani;
        $vybrane_ubytovani_nazev = ucfirst($ubytovani_typ) . ' pokoj';

        // Cena za služby
        if (in_array('polopenze', $vybrane_sluzby)) {
            $cena_polopenze = $ceny_sluzeb['polopenze'] * $pocet_osob * $pocet_dnu;
            $celkova_cena_pobytu += $cena_polopenze;
            $vybrane_sluzby_rekap['Polopenze'] = $cena_polopenze;
        }
        if (in_array('wellness', $vybrane_sluzby)) {
            $cena_wellness = $ceny_sluzeb['wellness'] * $pocet_osob * $pocet_dnu;
            $celkova_cena_pobytu += $cena_wellness;
            $vybrane_sluzby_rekap['Wellness'] = $cena_wellness;
        }
        if (in_array('parkovani', $vybrane_sluzby)) {
            $celkova_cena_pobytu += $ceny_sluzeb['parkovani'];
            $vybrane_sluzby_rekap['Parkování'] = $ceny_sluzeb['parkovani'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Rezervace pobytu</title>
    <!-- Styly jsou stejné jako v prvním příkladu -->
</head>
<body>
    <!-- ÚKOL 11: POBYT -->
    <div class="task">
        <h2>11. Rezervace víkendového pobytu</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="task" value="pobyt">

            <h3>1. Zvolte typ ubytování</h3>
            <input type="radio" name="ubytovani" value="standard" required> Standardní pokoj (600 Kč/noc)  

            <input type="radio" name="ubytovani" value="balkon" required> Pokoj s balkonem (800 Kč/noc)  

            <input type="radio" name="ubytovani" value="apartman" required> Apartmán s vířivkou (1200 Kč/noc)  


            <h3>2. Zvolte doplňkové služby</h3>
            <input type="checkbox" name="sluzby[]" value="polopenze"> Polopenze (300 Kč/osoba/noc)  

            <input type="checkbox" name="sluzby[]" value="wellness"> Wellness vstup (250 Kč/osoba/den)  

            <input type="checkbox" name="sluzby[]" value="parkovani"> Parkování (100 Kč/pobyt)  


            <h3>3. Zadejte délku pobytu a počet osob</h3>
            Počet nocí: <input type="text" name="pocet_dnu" required>  

            Počet osob: <input type="text" name="pocet_osob" required>  
  


            <button type="submit">Rezervovat pobyt</button>
        </form>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task']) && $_POST['task'] == 'pobyt' && $celkova_cena_pobytu > 0): ?>
        <div class="result">
            <h3>Rekapitulace rezervace</h3>
            <p><strong>Typ ubytování:</strong> <?php echo $vybrane_ubytovani_nazev; ?> (<?php echo $ceny_ubytovani[$ubytovani_typ]; ?> Kč/noc)</p>
            <p><strong>Počet nocí:</strong> <?php echo $pocet_dnu; ?></p>
            <p><strong>Počet osob:</strong> <?php echo $pocet_osob; ?></p>
            
            <?php if (!empty($vybrane_sluzby_rekap)): ?>
            <p><strong>Doplňkové služby:</strong></p>
            <ul>
                <?php foreach ($vybrane_sluzby_rekap as $nazev => $cena): ?>
                    <li><?php echo $nazev; ?> (celkem <?php echo $cena; ?> Kč)</li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <hr>
            <p><strong>Celková cena pobytu: <?php echo $celkova_cena_pobytu; ?> Kč</strong></p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
