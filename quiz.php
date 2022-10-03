<?php
date_default_timezone_set('Europe/Istanbul');
session_start();
$tckn = $_SESSION['tckn'];
$ad = $_SESSION['ad'];
$soyad = $_SESSION['soyad'];
$kayitid = $tckn;

$conn = new mysqli("localhost", "root", "1234", "dakik");

$sorular_sql = "SELECT sorular FROM `kisiliktesti_sorulari`";
$secenekler_sql = "SELECT * FROM `kisiliktesti_secenekleri` ORDER BY RAND()";
$kalansorular_sql = "Select * FROM (SELECT soru_no FROM kisiliktesti_secenekleri
EXCEPT
SELECT soru_no FROM temp WHERE kayit_id ='" . $kayitid . "') as br, kisiliktesti_secenekleri ks WHERE br.soru_no = ks.soru_no";

$sorular = $conn->query($sorular_sql);
$secenekler = $conn->query($secenekler_sql);
$kalansorular = $conn->query($kalansorular_sql);
$secenek = "";


$bugun = date("d/m/Y");
$soruturu = 1;

$x = 0;
while ($test = $kalansorular->fetch_assoc()) {
    $karisik[$x] = $test['soru_no'];
    $x++;
}
if (is_null($karisik)) {
    header('Location: end.php');
}
shuffle($karisik);

$kalansorusayisi = 0;
foreach ($karisik as $val) {
    $kalansorusayisi++;
}

?>
<?php
for ($x = 0; $x < 40; $x++) {
    $array[$x] = $x;
}
shuffle($array);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $simdikisoru = $_POST['soru'];
    if (isset($_POST['secenek'])) {
        while (isset($_POST["secenek"]) && $simdikisoru <= 40) {
            $sorunumarasi = $_POST['soru'];
            if ($secenek = $_POST["secenek"]) {
                $cevaplanan = strval($_POST['secenek']);
                $stmt = $conn->prepare("UPDATE `cevaplar` SET kt" . $sorunumarasi . " = '" . ($cevaplanan) . "' WHERE tckn = " . $tckn);
                $temp = $conn->prepare("INSERT INTO `temp` (`kayit_id`,`sturu_id`,`soru_no`) VALUES (?,?,?);");
                $temp->bind_param("sii", $kayitid, $soruturu, $simdikisoru);
                $temp->execute();
                $stmt->execute();
                $stmt->close();
                $temp->close();
                $conn->close();
                header('Location: quiz.php?soru=' . $karisik[0]);
                break;
            }
        }

    } else {
        header('Location: quiz.php?soru=' . $karisik[0]);
    }

}
while ($row = $secenekler->fetch_assoc()) {
    if ($_GET['soru'] == $row['soru_no']) {
        $cvp = array($row['secenek_a'] . "A", $row['secenek_b'] . "B", $row['secenek_c'] . "C", $row['secenek_d'] . "D");
        shuffle($cvp);
    }
}
?>
<!DOCTYPE html>
<html lang="tr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="title">Quiz</div>
        <div class="content" style="text-align: left;"><b><?php echo 'Welcome, '; ?><a
                        style="color:#8688CE"><?php echo $ad . ' ' . $soyad; ?></a></b></div>
        <br>
        <div class="content">
            <div class="content"
                 style="text-align: left"><?php echo('Soru: ' . 41 - $kalansorusayisi . '/' . ($kalansorusayisi - 1)); ?></div>
            <p class="fw-bold"><?php echo(41 - $kalansorusayisi . ') '); ?>
                <?php while ($col = $sorular->fetch_assoc()) {
                    echo(implode($col));
                } ?>
            </p>
            <div class="row">
                <div class="col-md-6"><input type="radio" name="secenek" id="five" onclick="cevapla(this.value)"
                                             value="<?php echo substr($cvp[0], -1); ?>"> <label for="five"
                                                                                                class="box fifth w-100">
                        <div class="course"><span class="circle"></span> <span
                                    class="subject"><?php echo substr($cvp[0], 0, strlen($cvp[0]) - 1); ?></span></div>
                    </label></div>
                <div class="col-md-6"><input type="radio" name="secenek" id="six" onclick="cevapla(this.value)"
                                             value="<?php echo substr($cvp[1], -1); ?>"> <label for="six"
                                                                                                class="box sixth w-100">
                        <div class="course"><span class="circle"></span> <span
                                    class="subject"><?php echo substr($cvp[1], 0, strlen($cvp[1]) - 1); ?></span>
                        </div>
                    </label></div>
                <div class="col-md-6"><input type="radio" name="secenek" id="seven" onclick="cevapla(this.value)"
                                             value="<?php echo substr($cvp[2], -1); ?>"> <label for="seven"
                                                                                                class="box seveth w-100">
                        <div class="course"><span class="circle"></span> <span
                                    class="subject"><?php echo substr($cvp[2], 0, strlen($cvp[2]) - 1); ?></span>
                        </div>
                    </label></div>
                <div class="col-md-6"><input type="radio" name="secenek" id="eight" onclick="cevapla(this.value)"
                                             value="<?php echo substr($cvp[3], -1); ?>"> <label for="eight"
                                                                                                class="box eighth w-100">
                        <div class="course"><span class="circle"></span> <span
                                    class="subject"><?php echo substr($cvp[3], 0, strlen($cvp[3]) - 1); ?></span>
                        </div>
                    </label></div>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    <div class="button">
                        <input type="text" name="soru" hidden id="" value="<?php echo($_GET['soru']); ?>">
                        <input type="submit" name="submit" id="btn" value="Soruyu atla"/>
                    </div>
                </div>
            </div>
        </div>
</div>
</form>
</div>
</body>
</html>
<script>
    function cevapla(id) {
        //alert(id);
        if (document.getElementById('five').checked == true) {
            document.getElementById('btn').value = "Diğer soruya geç";
        }
        if (document.getElementById('six').checked == true) {
            document.getElementById('btn').value = "Diğer soruya geç";
        }
        if (document.getElementById('seven').checked == true) {
            document.getElementById('btn').value = "Diğer soruya geç";
        }
        if (document.getElementById('eight').checked == true) {
            document.getElementById('btn').value = "Diğer soruya geç";
        }
    }
</script>