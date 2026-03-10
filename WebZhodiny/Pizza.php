<?php
// Nastavení výchozích hodnot
$velikost_cena = 0;
$ingredience_cena = 0;
$celkova_cena = 0;
$text_na_krabici = '';
$vybrana_velikost_nazev = '';
$vybrane_ingredience = [];

// Definice cen
$ceny_velikosti = [
    'mala' => 150,
    'stredni' => 200,
    'velka' => 250
];
$ceny_ingredienci = [
    'syr' => 30,
    'sunka' => 40,
    'zampiony' => 25,
    'olivy' => 20,
    'feferonky' => 20,
    'ananas' => 25
];

// Zpracování formuláře po odeslání
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task']) && $_POST['task'] == 'pizza') {
    // Zpracování velikosti pizzy
    if (isset($_POST['velikost']) && array_key_exists($_POST['velikost'], $ceny_velikosti)) {
        $vybrana_velikost = $_POST['velikost'];
        $velikost_cena = $ceny_velikosti[$vybrana_velikost];
        $vybrana_velikost_nazev = ucfirst($vybrana_velikost);
    }

    // Zpracování ingrediencí
    if (!empty($_POST['ingredience'])) {
        foreach ($_POST['ingredience'] as $ingredience) {
            if (array_key_exists($ingredience, $ceny_ingredienci)) {
                $ingredience_cena += $ceny_ingredienci[$ingredience];
                $vybrane_ingredience[ucfirst($ingredience)] = $ceny_ingredienci[$ingredience];
            }
        }
    }

    // Zpracování textu na krabici
    if (!empty($_POST['text_na_krabici'])) {
        $text_na_krabici = htmlspecialchars($_POST['text_na_krabici']);
    }

    // Výpočet celkové ceny
    $celkova_cena = $velikost_cena + $ingredience_cena;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Webové aplikace v PHP</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; max-width: 700px; margin: auto; }
        .task { border: 1px solid #ddd; padding: 20px; margin-bottom: 30px; border-radius: 5px; }
        .result { border: 1px solid #ccc; padding: 15px; margin-top: 20px; background-color: #f9f9f9; border-radius: 5px; }
        h2, h3 { color: #333; }
        button { padding: 10px 15px; background-color: #007BFF; color: white; border: none; cursor: pointer; border-radius: 3px; }
        button:hover { background-color: #0056b3; }
        input[type="text"], textarea { width: 95%; padding: 8px; margin-top: 5px; }
    </style>
</head>
<body>

    <h1>Webové aplikace v PHP</h1>

    <!-- ÚKOL 9: PIZZA -->
    <div class="task">
        <h2>9. Pizza na vlastní přání</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="task" value="pizza">
            
            <h3>1. Zvolte velikost pizzy</h3>
            <input type="radio" name="velikost" value="mala" required> Malá (150 Kč)  

            <input type="radio" name="velikost" value="stredni" required> Střední (200 Kč)  

            <input type="radio" name="velikost" value="velka" required> Velká (250 Kč)  


            <h3>2. Vyberte ingredience</h3>
            <input type="checkbox" name="ingredience[]" value="syr"> Sýr navíc (30 Kč)  

            <input type="checkbox" name="ingredience[]" value="sunka"> Šunka (40 Kč)  

            <input type="checkbox" name="ingredience[]" value="zampiony"> Žampiony (25 Kč)  

            <input type="checkbox" name="ingredience[]" value="olivy"> Olivy (20 Kč)  

            <input type="checkbox" name="ingredience[]" value="feferonky"> Feferonky (20 Kč)  

            <input type="checkbox" name="ingredience[]" value="ananas"> Ananas (25 Kč)  


            <h3>3. Text na krabici</h3>
            <textarea name="text_na_krabici" rows="3" placeholder="Např. Všechno nejlepší!"></textarea>  
  


            <button type="submit">Objednat pizzu</button>
        </form>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task']) && $_POST['task'] == 'pizza' && $celkova_cena > 0): ?>
        <div class="result">
            <h3>Rekapitulace objednávky pizzy</h3>
            <p><strong>Velikost:</strong> <?php echo $vybrana_velikost_nazev; ?> (<?php echo $velikost_cena; ?> Kč)</p>
            <?php if (!empty($vybrane_ingredience)): ?>
            <p><strong>Ingredience:</strong></p>
            <ul>
                <?php foreach ($vybrane_ingredience as $nazev => $cena): ?>
                    <li><?php echo $nazev; ?> (<?php echo $cena; ?> Kč)</li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <?php if (!empty($text_na_krabici)): ?>
            <p><strong>Text na krabici:</strong> "<?php echo $text_na_krabici; ?>"</p>
            <?php endif; ?>
            <hr>
            <p><strong>Celková cena: <?php echo $celkova_cena; ?> Kč</strong></p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
