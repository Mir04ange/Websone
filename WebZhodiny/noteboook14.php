<?php
session_start();

// Initialize session data
if (!isset($_SESSION['config'])) {
    $_SESSION['config'] = [
        'page' => 1,
        'type' => '',
        'name' => '',
        'processor' => 'i5',
        'ram' => '8GB',
        'addons' => [],
        'color' => 'red'
    ];
}

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['page'])) {
        $page = intval($_POST['page']);
        
        if ($page === 1) {
            $_SESSION['config']['type'] = $_POST['type'] ?? '';
            $_SESSION['config']['name'] = $_POST['name'] ?? '';
            $_SESSION['config']['page'] = 2;
        } elseif ($page === 2) {
            $_SESSION['config']['processor'] = $_POST['processor'] ?? 'i5';
            $_SESSION['config']['ram'] = $_POST['ram'] ?? '8GB';
            $_SESSION['config']['page'] = 3;
        } elseif ($page === 3) {
            $_SESSION['config']['addons'] = $_POST['addons'] ?? [];
            $_SESSION['config']['color'] = $_POST['color'] ?? 'red';
            $_SESSION['config']['page'] = 4;
        } elseif ($page === 0) {
            session_destroy();
            $_SESSION['config'] = [
                'page' => 1,
                'type' => '',
                'name' => '',
                'processor' => 'i5',
                'ram' => '8GB',
                'addons' => [],
                'color' => 'red'
            ];
        }
    }
}

// Price calculations
$typePrices = ['Kancelářský' => 10000, 'Herní' => 20000, 'Grafický' => 25000];
$processorPrices = ['i5' => 0, 'i7' => 3000, 'i9' => 6000];
$ramPrices = ['8GB' => 0, '16GB' => 1500, '32GB' => 3000];
$addonsPrices = ['SSD upgrade' => 2000, 'RGB podsvícení' => 800, 'Rozšířená záruka' => 1500];

function calculateTotal($config, $typePrices, $processorPrices, $ramPrices, $addonsPrices) {
    $total = $typePrices[$config['type']] ?? 0;
    $total += $processorPrices[$config['processor']] ?? 0;
    $total += $ramPrices[$config['ram']] ?? 0;
    foreach ($config['addons'] as $addon) {
        $total += $addonsPrices[$addon] ?? 0;
    }
    return $total;
}

$colorMap = ['red' => '#ff0000', 'blue' => '#0000ff', 'green' => '#00aa00'];
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfigurátor notebooku</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; }
        .form-group { margin: 20px 0; }
        label { display: block; margin: 10px 0 5px; font-weight: bold; }
        input, select { padding: 8px; margin: 5px 0; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 10px 5px 10px 0; }
        button:hover { background: #0056b3; }
        .summary { margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 4px; }
        .price-display { font-size: 28px; font-weight: bold; padding: 15px; margin: 20px 0; border-radius: 4px; text-align: center; }
    </style>
</head>
<body>

<div class="container">
    <?php if ($_SESSION['config']['page'] === 1): ?>
        <h1>Strana 1: Základ</h1>
        <form method="POST">
            <div class="form-group">
                <label>Zvolte typ notebooku:</label>
                <label><input type="radio" name="type" value="Kancelářský" required> Kancelářský (10 000 Kč)</label>
                <label><input type="radio" name="type" value="Herní"> Herní (20 000 Kč)</label>
                <label><input type="radio" name="type" value="Grafický"> Grafický (25 000 Kč)</label>
            </div>
            <div class="form-group">
                <label>Zadejte název konfigurace:</label>
                <input type="text" name="name" required placeholder="Např. Moje konfigurace">
            </div>
            <input type="hidden" name="page" value="1">
            <button type="submit">Pokračovat</button>
        </form>

    <?php elseif ($_SESSION['config']['page'] === 2): ?>
        <h1>Strana 2: Komponenty</h1>
        <div class="summary">
            <strong>Typ:</strong> <?= htmlspecialchars($_SESSION['config']['type']) ?><br>
            <strong>Název:</strong> <?= htmlspecialchars($_SESSION['config']['name']) ?>
        </div>
        <form method="POST">
            <div class="form-group">
                <label>Procesor:</label>
                <select name="processor">
                    <option value="i5">i5 (+0 Kč)</option>
                    <option value="i7">i7 (+3000 Kč)</option>
                    <option value="i9">i9 (+6000 Kč)</option>
                </select>
            </div>
            <div class="form-group">
                <label>RAM:</label>
                <label><input type="radio" name="ram" value="8GB"> 8 GB (+0 Kč)</label>
                <label><input type="radio" name="ram" value="16GB"> 16 GB (+1500 Kč)</label>
                <label><input type="radio" name="ram" value="32GB"> 32 GB (+3000 Kč)</label>
            </div>
            <input type="hidden" name="page" value="2">
            <button type="submit">Pokračovat</button>
        </form>

    <?php elseif ($_SESSION['config']['page'] === 3): ?>
        <h1>Strana 3: Doplňky</h1>
        <div class="summary">
            <strong>Typ:</strong> <?= htmlspecialchars($_SESSION['config']['type']) ?><br>
            <strong>Procesor:</strong> <?= htmlspecialchars($_SESSION['config']['processor']) ?><br>
            <strong>RAM:</strong> <?= htmlspecialchars($_SESSION['config']['ram']) ?>
        </div>
        <form method="POST">
            <div class="form-group">
                <label>Vyberte doplňky:</label>
                <label><input type="checkbox" name="addons[]" value="SSD upgrade"> SSD upgrade (+2000 Kč)</label>
                <label><input type="checkbox" name="addons[]" value="RGB podsvícení"> RGB podsvícení (+800 Kč)</label>
                <label><input type="checkbox" name="addons[]" value="Rozšířená záruka"> Rozšířená záruka (+1500 Kč)</label>
            </div>
            <div class="form-group">
                <label>Zvolte barvu výpisu:</label>
                <select name="color">
                    <option value="red">Červená</option>
                    <option value="blue">Modrá</option>
                    <option value="green">Zelená</option>
                </select>
            </div>
            <input type="hidden" name="page" value="3">
            <button type="submit">Dokončit</button>
        </form>

    <?php elseif ($_SESSION['config']['page'] === 4): ?>
        <h1>Strana 4: Vaše konfigurace</h1>
        <?php
            $total = calculateTotal($_SESSION['config'], $typePrices, $processorPrices, $ramPrices, $addonsPrices);
            $color = $colorMap[$_SESSION['config']['color']];
        ?>
        <div style="color: <?= $color ?>; padding: 20px; border: 2px solid <?= $color ?>; border-radius: 4px;">
            <h2><?= htmlspecialchars($_SESSION['config']['name']) ?></h2>
            <p><strong>Typ notebooku:</strong> <?= htmlspecialchars($_SESSION['config']['type']) ?></p>
            <p><strong>Procesor:</strong> <?= htmlspecialchars($_SESSION['config']['processor']) ?></p>
            <p><strong>RAM:</strong> <?= htmlspecialchars($_SESSION['config']['ram']) ?></p>
            <?php if (!empty($_SESSION['config']['addons'])): ?>
                <p><strong>Doplňky:</strong> <?= htmlspecialchars(implode(', ', $_SESSION['config']['addons'])) ?></p>
            <?php endif; ?>
            <div class="price-display" style="color: <?= $color ?>; border: 2px solid <?= $color ?>;">
                Celková cena: <strong><?= number_format($total, 0, ',', ' ') ?> Kč</strong>
            </div>
        </div>
        <form method="POST">
            <input type="hidden" name="page" value="0">
            <button type="submit">Začít znovu</button>
        </form>

    <?php endif; ?>
</div>

</body>
</html>