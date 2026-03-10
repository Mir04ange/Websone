<?php
session_start();

if (isset($_GET['reset'])) {
    unset($_SESSION['pc']);
    header('Location: pc.php');
    exit;
}

$step = isset($_POST['step']) ? (int)$_POST['step'] : 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step == 2) {
        $_SESSION['pc']['purpose'] = $_POST['purpose'];
    } elseif ($step == 3) {
        $_SESSION['pc']['components'] = isset($_POST['components']) ? $_POST['components'] : [];
    } elseif ($step == 4) {
        $_SESSION['pc']['customer_name'] = $_POST['customer_name'];
        $_SESSION['pc']['pc_name'] = $_POST['pc_name'];
    }
}

include 'includes/header.php';

$components_data = [
    'Kancelářská práce' => [
        'Intel i3' => 2500,
        '8 GB RAM' => 1200,
        'SSD 256 GB' => 1000,
        'Integrovaná grafika' => 0
    ],
    'Hraní her' => [
        'AMD Ryzen 5' => 4500,
        '16 GB RAM' => 2400,
        'SSD 1 TB' => 2500,
        'GeForce RTX 4060' => 8500
    ],
    'Grafické práce' => [
        'Intel i7' => 5500,
        '32 GB RAM' => 4500,
        'SSD 2 TB' => 4000,
        'NVIDIA Quadro P2200' => 9600
    ]
];
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h2 class="h5 mb-0">10. Stavba vlastního počítače - Krok <?php echo $step; ?> z 4</h2>
            </div>
            <div class="card-body">
                <form method="post" action="pc.php">
                    <input type="hidden" name="step" value="<?php echo $step + 1; ?>">

                    <?php if ($step == 1): ?>
                        <h3 class="h6 mb-3">Zvolte účel použití počítače:</h3>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="purpose" id="p1" value="Kancelářská práce" required>
                            <label class="form-check-label" for="p1">Kancelářská práce</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="purpose" id="p2" value="Hraní her">
                            <label class="form-check-label" for="p2">Hraní her</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="purpose" id="p3" value="Grafické práce">
                            <label class="form-check-label" for="p3">Grafické práce</label>
                        </div>
                        <button type="submit" class="btn btn-success">Další krok</button>

                    <?php elseif ($step == 2): ?>
                        <h3 class="h6 mb-3">Výběr komponent pro: <?php echo $_SESSION['pc']['purpose']; ?></h3>
                        <?php
                        $purpose = $_SESSION['pc']['purpose'];
                        foreach ($components_data[$purpose] as $name => $price):
                        ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="components[]" value="<?php echo "$name|$price"; ?>" id="comp_<?php echo md5($name); ?>">
                            <label class="form-check-label" for="comp_<?php echo md5($name); ?>">
                                <?php echo "$name ($price Kč)"; ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                        <button type="submit" class="btn btn-success mt-3">Další krok</button>

                    <?php elseif ($step == 3): ?>
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Jméno zákazníka:</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="pc_name" class="form-label">Název PC:</label>
                            <input type="text" class="form-control" id="pc_name" name="pc_name" required>
                        </div>
                        <button type="submit" class="btn btn-success">Dokončit sestavu</button>

                    <?php elseif ($step == 4): ?>
                        <h3 class="h6 mb-3">Shrnutí vaší sestavy:</h3>
                        <p><strong>Zákazník:</strong> <?php echo htmlspecialchars($_SESSION['pc']['customer_name']); ?></p>
                        <p><strong>Název PC:</strong> <?php echo htmlspecialchars($_SESSION['pc']['pc_name']); ?></p>
                        <p><strong>Účel:</strong> <?php echo $_SESSION['pc']['purpose']; ?></p>
                        
                        <ul class="summary-list">
                            <?php
                            $total = 0;
                            if (!empty($_SESSION['pc']['components'])):
                                foreach ($_SESSION['pc']['components'] as $comp): 
                                    $compData = explode('|', $comp);
                                    $total += (int)$compData[1];
                                ?>
                                <li>
                                    <span><?php echo $compData[0]; ?></span>
                                    <span><?php echo $compData[1]; ?> Kč</span>
                                </li>
                                <?php endforeach;
                            endif; ?>

                            <li class="bg-light p-2 mt-2">
                                <strong>Celková cena:</strong>
                                <strong><?php echo $total; ?> Kč</strong>
                            </li>
                        </ul>

                        <a href="pc.php?reset=1" class="btn btn-secondary mt-3">Nová sestava</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
