<?php
session_start();

// Initialize form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['form_data'] = $_POST;
}

$current_step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$form_data = $_SESSION['form_data'] ?? [];
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plánovač fitness tréninku</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 30px; color: #333; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .radio-group, .checkbox-group { margin-top: 10px; }
        .radio-group label, .checkbox-group label { display: inline-block; margin-right: 20px; font-weight: normal; }
        input[type="radio"], input[type="checkbox"] { margin-right: 5px; }
        .buttons { display: flex; gap: 10px; margin-top: 30px; }
        button { padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: bold; }
        .btn-next { background: #007bff; color: white; flex: 1; }
        .btn-prev { background: #6c757d; color: white; flex: 1; }
        .btn-submit { background: #28a745; color: white; flex: 1; }
        button:hover { opacity: 0.9; }

        .output { margin-top: 30px; padding: 20px; border-radius: 8px; }
        .output.svals { background-color: #d4edda; border-left: 4px solid #28a745; }
        .output.hubnutí { background-color: #d1ecf1; border-left: 4px solid #17a2b8; }
        .output.kondice { background-color: #ffe4cc; border-left: 4px solid #ff9800; }
        .output strong { font-weight: bold; }
        .output em { font-style: italic; }
        .price { margin-top: 20px; font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <?php if ($current_step === 1): ?>
        <h1>Krok 1: Základ</h1>
        <form method="POST" action="?step=2">
            <div class="form-group">
                <label>Vyberte cíl tréninku:</label>
                <div class="radio-group">
                    <label><input type="radio" name="goal" value="hubnutí" required> Hubnutí</label>
                    <label><input type="radio" name="goal" value="svals"> Nabírání svalů</label>
                    <label><input type="radio" name="goal" value="kondice"> Kondice</label>
                </div>
            </div>

            <div class="form-group">
                <label for="age">Věk:</label>
                <input type="number" id="age" name="age" min="16" max="120" required>
            </div>

            <div class="form-group">
                <label for="weight">Váha (kg):</label>
                <input type="number" id="weight" name="weight" min="30" step="0.1" required>
            </div>

            <div class="buttons">
                <button type="submit" class="btn-next">Daleko →</button>
            </div>
        </form>

    <?php elseif ($current_step === 2): ?>
        <h1>Krok 2: Trénink</h1>
        <form method="POST" action="?step=3">
            <div class="form-group">
                <label for="type">Typ tréninku:</label>
                <select id="type" name="type" required>
                    <option value="">-- Vyberte --</option>
                    <option value="kardio">Kardio</option>
                    <option value="silový">Silový</option>
                    <option value="kombinovaný">Kombinovaný</option>
                </select>
            </div>

            <div class="form-group">
                <label>Délka tréninku:</label>
                <div class="radio-group">
                    <label><input type="radio" name="length" value="30" required> 30 min</label>
                    <label><input type="radio" name="length" value="60"> 60 min</label>
                    <label><input type="radio" name="length" value="90"> 90 min</label>
                </div>
            </div>

            <div class="buttons">
                <button type="button" class="btn-prev" onclick="history.back()">← Zpět</button>
                <button type="submit" class="btn-next">Daleko →</button>
            </div>
        </form>

    <?php elseif ($current_step === 3): ?>
        <h1>Krok 3: Doplňky</h1>
        <form method="POST" action="?step=4">
            <div class="form-group">
                <label>Doplňky:</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="addons[]" value="trainer"> Trenér (+200 Kč)</label><br>
                    <label><input type="checkbox" name="addons[]" value="menu"> Jídelníček (+150 Kč)</label><br>
                    <label><input type="checkbox" name="addons[]" value="supps"> Suplementy (+300 Kč)</label>
                </div>
            </div>

            <div class="form-group">
                <label for="style">Styl výstupu:</label>
                <select id="style" name="style" required>
                    <option value="">-- Vyberte --</option>
                    <option value="bold">Tučně</option>
                    <option value="italic">Kurzívou</option>
                </select>
            </div>

            <div class="buttons">
                <button type="button" class="btn-prev" onclick="history.back()">← Zpět</button>
                <button type="submit" class="btn-submit">Zobrazit plán →</button>
            </div>
        </form>

    <?php elseif ($current_step === 4): ?>
        <h1>Krok 4: Váš tréninkový plán</h1>

        <?php
        $goal = $form_data['goal'] ?? '';
        $age = $form_data['age'] ?? '';
        $weight = $form_data['weight'] ?? '';
        $type = $form_data['type'] ?? '';
        $length = $form_data['length'] ?? '';
        $addons = $form_data['addons'] ?? [];
        $style = $form_data['style'] ?? 'bold';

        // Calculate price
        $price = 0;
        $addon_list = [];
        if (in_array('trainer', $addons)) { $price += 200; $addon_list[] = 'Trenér'; }
        if (in_array('menu', $addons)) { $price += 150; $addon_list[] = 'Jídelníček'; }
        if (in_array('supps', $addons)) { $price += 300; $addon_list[] = 'Suplementy'; }

        // Determine output class
        $output_class = '';
        if ($goal === 'svals') $output_class = 'svals';
        elseif ($goal === 'hubnutí') $output_class = 'hubnutí';
        elseif ($goal === 'kondice') $output_class = 'kondice';

        // Apply style
        $open_tag = ($style === 'bold') ? '<strong>' : '<em>';
        $close_tag = ($style === 'bold') ? '</strong>' : '</em>';
        ?>

        <div class="output <?php echo $output_class; ?>">
            <h2>Váš osobní plán:</h2>
            <p><strong>Cíl:</strong> <?php echo $open_tag . ucfirst($goal) . $close_tag; ?></p>
            <p><strong>Věk:</strong> <?php echo $open_tag . $age . ' let' . $close_tag; ?></p>
            <p><strong>Váha:</strong> <?php echo $open_tag . $weight . ' kg' . $close_tag; ?></p>
            <p><strong>Typ tréninku:</strong> <?php echo $open_tag . ucfirst($type) . $close_tag; ?></p>
            <p><strong>Délka tréninku:</strong> <?php echo $open_tag . $length . ' minut' . $close_tag; ?></p>

            <?php if (!empty($addon_list)): ?>
                <p><strong>Vybrané doplňky:</strong> <?php echo $open_tag . implode(', ', $addon_list) . $close_tag; ?></p>
            <?php endif; ?>

            <div class="price">
                Cena doplňků: <?php echo $open_tag . $price . ' Kč' . $close_tag; ?>
            </div>
        </div>

        <div class="buttons" style="margin-top: 30px;">
            <button type="button" class="btn-prev" onclick="location.href='?step=1'; session_destroy();">← Nový plán</button>
        </div>

    <?php endif; ?>
</div>

</body>
</html>