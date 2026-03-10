<?php
session_start();

// Reset session if requested
if (isset($_GET['reset'])) {
    unset($_SESSION['pizza']);
    header('Location: pizza.php');
    exit;
}

$step = isset($_POST['step']) ? (int)$_POST['step'] : 1;

// Process data from previous steps
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step == 2) {
        $_SESSION['pizza']['size'] = $_POST['size'];
    } elseif ($step == 3) {
        $_SESSION['pizza']['ingredients'] = isset($_POST['ingredients']) ? $_POST['ingredients'] : [];
    } elseif ($step == 4) {
        $_SESSION['pizza']['message'] = $_POST['message'];
    }
}

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">9. Pizza na vlastní přání - Krok <?php echo $step; ?> z 4</h2>
            </div>
            <div class="card-body">
                <form method="post" action="pizza.php">
                    <input type="hidden" name="step" value="<?php echo $step + 1; ?>">

                    <?php if ($step == 1): ?>
                        <!-- Strana 1: Velikost -->
                        <h3 class="h6 mb-3">Zvolte velikost pizzy:</h3>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="size" id="size1" value="Malá|150" required>
                            <label class="form-check-label" for="size1">Malá (150 Kč základ)</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="size" id="size2" value="Střední|200">
                            <label class="form-check-label" for="size2">Střední (200 Kč základ)</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="size" id="size3" value="Velká|250">
                            <label class="form-check-label" for="size3">Velká (250 Kč základ)</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Další krok</button>

                    <?php elseif ($step == 2): ?>
                        <!-- Strana 2: Ingredience -->
                        <h3 class="h6 mb-3">Vyberte ingredience:</h3>
                        <?php
                        $ingredients = [
                            'Sýr navíc' => 30,
                            'Šunka' => 40,
                            'Žampiony' => 25,
                            'Olivy' => 20,
                            'Feferonky' => 20,
                            'Ananas' => 25
                        ];
                        foreach ($ingredients as $name => $price):
                        ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="ingredients[]" value="<?php echo "$name|$price"; ?>" id="ing_<?php echo md5($name); ?>">
                            <label class="form-check-label" for="ing_<?php echo md5($name); ?>">
                                <?php echo "$name ($price Kč)"; ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                        <button type="submit" class="btn btn-primary mt-3">Další krok</button>

                    <?php elseif ($step == 3): ?>
                        <!-- Strana 3: Text na krabici -->
                        <div class="mb-3">
                            <label for="message" class="form-label">Text na krabici (např. přání k narozeninám):</label>
                            <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Dokončit objednávku</button>

                    <?php elseif ($step == 4): ?>
                        <!-- Strana poslední: Přehled -->
                        <h3 class="h6 mb-3">Shrnutí vaší objednávky:</h3>
                        <ul class="summary-list">
                            <?php
                            $total = 0;
                            $sizeData = explode('|', $_SESSION['pizza']['size']);
                            $total += (int)$sizeData[1];
                            ?>
                            <li>
                                <span>Velikost: <strong><?php echo $sizeData[0]; ?></strong></span>
                                <span><?php echo $sizeData[1]; ?> Kč</span>
                            </li>
                            
                            <?php if (!empty($_SESSION['pizza']['ingredients'])): ?>
                                <?php foreach ($_SESSION['pizza']['ingredients'] as $ing): 
                                    $ingData = explode('|', $ing);
                                    $total += (int)$ingData[1];
                                ?>
                                <li>
                                    <span><?php echo $ingData[0]; ?></span>
                                    <span><?php echo $ingData[1]; ?> Kč</span>
                                </li>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <li class="bg-light p-2 mt-2">
                                <strong>Celková cena:</strong>
                                <strong><?php echo $total; ?> Kč</strong>
                            </li>
                        </ul>
                        
                        <div class="mt-3">
                            <strong>Text na krabici:</strong>
                            <p class="border p-2 bg-white mt-1"><?php echo nl2br(htmlspecialchars($_SESSION['pizza']['message'])); ?></p>
                        </div>

                        <a href="pizza.php?reset=1" class="btn btn-secondary mt-3">Nová objednávka</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
