<?php
session_start();

$step = isset($_POST['step']) ? (int)$_POST['step'] : 1;
$data = isset($_SESSION['plan']) ? $_SESSION['plan'] : [];

$subjects = [
    'Programování' => 5,
    'Databáze' => 4,
    'Design' => 3,
    'Web Development' => 4,
    'UI/UX' => 3
];

$difficulties = ['Nízká' => 'green', 'Střední' => 'orange', 'Vysoká' => 'red'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 1) {
        $data['obor'] = $_POST['obor'] ?? '';
        $data['jmeno'] = $_POST['jmeno'] ?? '';
        $step = 2;
    } elseif ($step === 2) {
        $data['predmety'] = $_POST['predmety'] ?? [];
        $step = 3;
    } elseif ($step === 3) {
        $data['narocnost'] = $_POST['narocnost'] ?? '';
        $data['hodiny'] = (int)($_POST['hodiny'] ?? 0);
        $step = 4;
    }
    $_SESSION['plan'] = $data;
}

$colorMap = ['green' => '#90EE90', 'orange' => '#FFA500', 'red' => '#FF6B6B'];
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Generátor Studijního Plánu</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 50px auto; }
        .step { margin: 20px 0; }
        input, select { margin: 5px 0; padding: 5px; }
        button { padding: 8px 15px; background: #007bff; color: white; border: none; cursor: pointer; }
        .warning { color: red; font-weight: bold; }
        .plan-item { padding: 10px; margin: 5px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Generátor Studijního Plánu</h1>

    <?php if ($step === 1): ?>
        <form method="POST">
            <div class="step">
                <label>Zvolte obor:</label>
                <select name="obor" required>
                    <option value="">-- Vyberte --</option>
                    <option value="IT">IT</option>
                    <option value="Ekonomika">Ekonomika</option>
                    <option value="Grafika">Grafika</option>
                </select>
            </div>
            <div class="step">
                <label>Zadejte jméno:</label>
                <input type="text" name="jmeno" required>
            </div>
            <input type="hidden" name="step" value="1">
            <button type="submit">Pokračovat</button>
        </form>

    <?php elseif ($step === 2): ?>
        <form method="POST">
            <h3>Obor: <?= htmlspecialchars($data['obor']) ?>, Jméno: <?= htmlspecialchars($data['jmeno']) ?></h3>
            <div class="step">
                <label>Vyberte předměty:</label><br>
                <?php foreach ($subjects as $subject => $hours): ?>
                    <label><input type="checkbox" name="predmety[]" value="<?= $subject ?>"> <?= $subject ?> (<?= $hours ?> h/týdně)</label><br>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="step" value="2">
            <button type="submit">Pokračovat</button>
        </form>

    <?php elseif ($step === 3): ?>
        <form method="POST">
            <h3>Vybrané předměty: <?= implode(', ', $data['predmety']) ?></h3>
            <div class="step">
                <label>Zvolte náročnost:</label><br>
                <?php foreach ($difficulties as $diff => $color): ?>
                    <label><input type="radio" name="narocnost" value="<?= $diff ?>" required> <?= $diff ?></label><br>
                <?php endforeach; ?>
            </div>
            <div class="step">
                <label>Počet hodin týdně:</label>
                <input type="number" name="hodiny" required>
            </div>
            <input type="hidden" name="step" value="3">
            <button type="submit">Vygenerovat Plán</button>
        </form>

    <?php elseif ($step === 4): ?>
        <h2>Váš studijní plán</h2>
        <p><strong>Obor:</strong> <?= htmlspecialchars($data['obor']) ?></p>
        <p><strong>Jméno:</strong> <?= htmlspecialchars($data['jmeno']) ?></p>

        <?php
        $totalHours = array_sum(array_map(fn($s) => $subjects[$s] ?? 0, $data['predmety'])) + $data['hodiny'];
        $color = $colorMap[$difficulties[$data['narocnost']] ?? ''];
        ?>

        <h3>Předměty:</h3>
        <?php foreach ($data['predmety'] as $subject): ?>
            <div class="plan-item" style="background-color: <?= $color ?>;">
                <?= htmlspecialchars($subject) ?> (<?= $subjects[$subject] ?> h/týdně)
            </div>
        <?php endforeach; ?>

        <h3>Náročnost: <span style="color: <?= $color === '#90EE90' ? 'green' : ($color === '#FFA500' ? 'orange' : 'red') ?>;"><?= $data['narocnost'] ?></span></h3>

        <h3>Celkový počet hodin týdně: <?= $totalHours ?></h3>
        <?php if ($totalHours > 20): ?>
            <p class="warning">⚠️ VAROVÁNÍ: Počet hodin přesahuje 20! To je velmi náročné!</p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="step" value="1">
            <button type="submit">Začít znovu</button>
        </form>
    <?php endif; ?>
</body>
</html>