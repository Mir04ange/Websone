<?php
session_start();

if (isset($_GET['reset'])) {
    unset($_SESSION['rezervace']);
    header('Location: rezervace.php');
    exit;
}

$step = isset($_POST['step']) ? (int)$_POST['step'] : 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step == 2) {
        $_SESSION['rezervace']['room'] = $_POST['room'];
    } elseif ($step == 3) {
        $_SESSION['rezervace']['services'] = isset($_POST['services']) ? $_POST['services'] : [];
    } elseif ($step == 4) {
        $_SESSION['rezervace']['days'] = (int)$_POST['days'];
        $_SESSION['rezervace']['people'] = (int)$_POST['people'];
    }
}

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h2 class="h5 mb-0">11. Rezervace víkendového pobytu - Krok <?php echo $step; ?> z 4</h2>
            </div>
            <div class="card-body">
                <form method="post" action="rezervace.php">
                    <input type="hidden" name="step" value="<?php echo $step + 1; ?>">

                    <?php if ($step == 1): ?>
                        <h3 class="h6 mb-3">Zvolte typ ubytování:</h3>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="room" id="r1" value="Standardní pokoj|600" required>
                            <label class="form-check-label" for="r1">Standardní pokoj (600 Kč/noc)</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="room" id="r2" value="Pokoj s balkonem|800">
                            <label class="form-check-label" for="r2">Pokoj s balkonem (800 Kč/noc)</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="room" id="r3" value="Apartmán s vířivkou|1200">
                            <label class="form-check-label" for="r3">Apartmán s vířivkou (1200 Kč/noc)</label>
                        </div>
                        <button type="submit" class="btn btn-info text-white">Další krok</button>

                    <?php elseif ($step == 2): ?>
                        <h3 class="h6 mb-3">Doplňkové služby:</h3>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="services[]" value="Polopenze|300|per_person_night" id="s1">
                            <label class="form-check-label" for="s1">Polopenze (300 Kč/osoba/noc)</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="services[]" value="Wellness vstup|250|per_person_day" id="s2">
                            <label class="form-check-label" for="s2">Wellness vstup (250 Kč/osoba/den)</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="services[]" value="Parkování|100|per_stay" id="s3">
                            <label class="form-check-label" for="s3">Parkování (100 Kč/pobyt)</label>
                        </div>
                        <button type="submit" class="btn btn-info text-white">Další krok</button>

                    <?php elseif ($step == 3): ?>
                        <div class="mb-3">
                            <label for="days" class="form-label">Počet dnů pobytu:</label>
                            <input type="number" class="form-control" id="days" name="days" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="people" class="form-label">Počet osob:</label>
                            <input type="number" class="form-control" id="people" name="people" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-info text-white">Dokončit rezervaci</button>

                    <?php elseif ($step == 4): ?>
                        <h3 class="h6 mb-3">Shrnutí vaší rezervace:</h3>
                        <?php
                        $days = $_SESSION['rezervace']['days'];
                        $people = $_SESSION['rezervace']['people'];
                        $roomData = explode('|', $_SESSION['rezervace']['room']);
                        $roomPrice = (int)$roomData[1] * $days;
                        $total = $roomPrice;
                        ?>
                        <p><strong>Počet nocí:</strong> <?php echo $days; ?></p>
                        <p><strong>Počet osob:</strong> <?php echo $people; ?></p>
                        
                        <ul class="summary-list">
                            <li>
                                <span>Ubytování: <?php echo $roomData[0]; ?> (<?php echo $roomData[1]; ?> Kč/noc)</span>
                                <span><?php echo $roomPrice; ?> Kč</span>
                            </li>
                            
                            <?php if (!empty($_SESSION['rezervace']['services'])): ?>
                                <?php foreach ($_SESSION['rezervace']['services'] as $service): 
                                    $sData = explode('|', $service);
                                    $sName = $sData[0];
                                    $sBasePrice = (int)$sData[1];
                                    $sType = $sData[2];
                                    
                                    $sFinalPrice = 0;
                                    if ($sType === 'per_person_night') {
                                        $sFinalPrice = $sBasePrice * $people * $days;
                                        $sLabel = "$sName ($sBasePrice Kč x $people os. x $days nocí)";
                                    } elseif ($sType === 'per_person_day') {
                                        $sFinalPrice = $sBasePrice * $people * $days;
                                        $sLabel = "$sName ($sBasePrice Kč x $people os. x $days dní)";
                                    } else {
                                        $sFinalPrice = $sBasePrice;
                                        $sLabel = "$sName ($sBasePrice Kč/pobyt)";
                                    }
                                    $total += $sFinalPrice;
                                ?>
                                <li>
                                    <span><?php echo $sLabel; ?></span>
                                    <span><?php echo $sFinalPrice; ?> Kč</span>
                                </li>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <li class="bg-light p-2 mt-2">
                                <strong>Celková cena pobytu:</strong>
                                <strong><?php echo $total; ?> Kč</strong>
                            </li>
                        </ul>

                        <a href="rezervace.php?reset=1" class="btn btn-secondary mt-3">Nová rezervace</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
