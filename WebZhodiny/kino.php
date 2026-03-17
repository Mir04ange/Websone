<?php
session_start();

if (!isset($_SESSION['kino_step'])) {
    $_SESSION['kino_step'] = 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['next'])) {
        $_SESSION['kino_step']++;
    } elseif (isset($_POST['prev'])) {
        $_SESSION['kino_step']--;
    }

    if (isset($_POST['film'])) $_SESSION['film'] = $_POST['film'];
    if (isset($_POST['quality'])) $_SESSION['quality'] = $_POST['quality'];
    if (isset($_POST['tickets'])) $_SESSION['tickets'] = (int)$_POST['tickets'];
    if (isset($_POST['seats'])) $_SESSION['seats'] = $_POST['seats'];
    if (isset($_POST['refreshments'])) $_SESSION['refreshments'] = $_POST['refreshments'];
    if (isset($_POST['color'])) $_SESSION['color'] = $_POST['color'];
}

$films = ['Akční' => 150, 'Komedie' => 120, 'Horor' => 130];
$colors = ['zlatá' => '#FFD700', 'černá' => '#000000', 'červená' => '#FF0000'];

function getPrice($film, $quality, $tickets, $seats, $refreshments) {
    global $films;
    $price = $films[$film] ?? 0;
    if ($quality === '3D') $price += 50;
    $price *= $tickets;
    if ($seats === 'VIP') $price += 100 * $tickets;
    if (isset($refreshments)) {
        foreach ($refreshments as $item) {
            $prices = ['Popcorn' => 50, 'Nachos' => 70, 'Nápoj' => 40];
            $price += $prices[$item] ?? 0;
        }
    }
    return $price;
}

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Rezervace Kina</title>
    <style>
        body { font-family: Arial, sans-serif; background: #1a1a1a; color: #fff; padding: 40px; }
        .container { max-width: 600px; margin: 0 auto; background: #333; padding: 30px; border-radius: 10px; }
        h2 { color: #FFD700; }
        label { display: block; margin: 10px 0 5px; }
        input, select { width: 100%; padding: 8px; margin-bottom: 15px; }
        button { background: #FFD700; color: #000; padding: 10px 20px; border: none; cursor: pointer; margin: 10px 5px 10px 0; }
        .step { display: none; }
        .step.active { display: block; }
        .ticket { border: 3px solid; padding: 30px; text-align: center; margin-top: 20px; border-radius: 10px; }
        .ticket-header { font-size: 28px; font-weight: bold; margin-bottom: 20px; }
        .ticket-info { font-size: 16px; margin: 10px 0; }
    </style>
</head>
<body>
<div class="container">
    <h1>🎬 Rezervace Kina</h1>

    <form method="POST">
        <!-- STEP 1: Film -->
        <div class="step <?= $_SESSION['kino_step'] == 1 ? 'active' : '' ?>">
            <h2>Krok 1: Výběr Filmu</h2>
            <label>Vyberte film:</label>
            <select name="film" required>
                <option>-- Vyberte --</option>
                <?php foreach ($films as $name => $price): ?>
                    <option value="<?= $name ?>" <?= $_SESSION['film'] === $name ? 'selected' : '' ?>>
                        <?= $name ?> (<?= $price ?> Kč)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Vyberte kvalitu:</label>
            <input type="radio" name="quality" value="2D" <?= isset($_SESSION['quality']) && $_SESSION['quality'] === '2D' ? 'checked' : '' ?>> 2D
            <input type="radio" name="quality" value="3D" <?= isset($_SESSION['quality']) && $_SESSION['quality'] === '3D' ? 'checked' : '' ?>> 3D (+50 Kč)

            <button type="submit" name="next">Dále →</button>
        </div>

        <!-- STEP 2: Sedadla -->
        <div class="step <?= $_SESSION['kino_step'] == 2 ? 'active' : '' ?>">
            <h2>Krok 2: Výběr Sedadel</h2>
            <label>Počet lístků:</label>
            <input type="number" name="tickets" min="1" max="10" value="<?= $_SESSION['tickets'] ?? 1 ?>" required>

            <label>Typ sedadel:</label>
            <input type="radio" name="seats" value="Standard" <?= isset($_SESSION['seats']) && $_SESSION['seats'] === 'Standard' ? 'checked' : '' ?>> Standard
            <input type="radio" name="seats" value="VIP" <?= isset($_SESSION['seats']) && $_SESSION['seats'] === 'VIP' ? 'checked' : '' ?>> VIP (+100 Kč/osoba)

            <button type="submit" name="prev">← Zpět</button>
            <button type="submit" name="next">Dále →</button>
        </div>

        <!-- STEP 3: Občerstvení -->
        <div class="step <?= $_SESSION['kino_step'] == 3 ? 'active' : '' ?>">
            <h2>Krok 3: Občerstvení</h2>
            <label><input type="checkbox" name="refreshments[]" value="Popcorn"> Popcorn (+50 Kč)</label>
            <label><input type="checkbox" name="refreshments[]" value="Nachos"> Nachos (+70 Kč)</label>
            <label><input type="checkbox" name="refreshments[]" value="Nápoj"> Nápoj (+40 Kč)</label>

            <label>Barva vstupenky:</label>
            <select name="color" required>
                <option>-- Vyberte --</option>
                <?php foreach ($colors as $name => $hex): ?>
                    <option value="<?= $name ?>" <?= $_SESSION['color'] === $name ? 'selected' : '' ?>>
                        <?= ucfirst($name) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="prev">← Zpět</button>
            <button type="submit" name="next">Dále →</button>
        </div>

        <!-- STEP 4: Výstup -->
        <div class="step <?= $_SESSION['kino_step'] == 4 ? 'active' : '' ?>">
            <h2>Krok 4: Vaše Vstupenka</h2>
            <?php if (isset($_SESSION['film'])): 
                $total = getPrice($_SESSION['film'], $_SESSION['quality'] ?? '2D', 
                    $_SESSION['tickets'] ?? 1, $_SESSION['seats'] ?? 'Standard', 
                    $_SESSION['refreshments'] ?? []);
                $bgColor = $colors[$_SESSION['color'] ?? 'zlatá'];
            ?>
            <div class="ticket" style="background: <?= $bgColor ?>; border-color: <?= $bgColor === '#FFD700' ? '#333' : '#FFD700' ?>; color: <?= $bgColor === '#000000' ? '#FFD700' : '#000' ?>;">
                <div class="ticket-header">🎫 KINO VSTUPENKA 🎫</div>
                <div class="ticket-info"><strong>Film:</strong> <?= $_SESSION['film'] ?></div>
                <div class="ticket-info"><strong>Kvalita:</strong> <?= $_SESSION['quality'] ?? '2D' ?></div>
                <div class="ticket-info"><strong>Počet osob:</strong> <?= $_SESSION['tickets'] ?? 1 ?></div>
                <div class="ticket-info"><strong>Sedadla:</strong> <?= $_SESSION['seats'] ?? 'Standard' ?></div>
                <div class="ticket-info"><strong>Občerstvení:</strong> 
                    <?php echo isset($_SESSION['refreshments']) && !empty($_SESSION['refreshments']) ? implode(', ', $_SESSION['refreshments']) : 'Žádné'; ?>
                </div>
                <hr style="margin: 20px 0;">
                <div style="font-size: 24px; font-weight: bold;">CENA: <?= $total ?> Kč</div>
            </div>

            <button type="submit" name="prev" style="margin-top: 20px;">← Zpět</button>
            <button type="reset" onclick="location.href='?';" style="background: #666;">Nová objednávka</button>
            <?php endif; ?>
        </div>
    </form>
</div>
</body>
</html>