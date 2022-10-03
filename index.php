<?php
date_default_timezone_set('Europe/Istanbul');
session_start();
$conn = new mysqli("localhost", "root", "1234", "dakik");

$ad = $soyad = $eposta = $tckn = $dogumtarihi = $cinsiyet = $telefon = "";

$gecerli = true;

for ($x = 0; $x < 40; $x++) {
    $karisik[$x] = $x;
}
shuffle($karisik);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["ad"])) {
        $gecerli = false;
    } else {
        $ad = test_input($_POST["ad"]);
    }

    if (empty($_POST["soyad"])) {
        $gecerli = false;
    } else {
        $soyad = test_input(strtoupper($_POST['soyad']));
    }

    if (empty($_POST["eposta"])) {
        $gecerli = false;
    } else {
        $eposta = test_input($_POST["eposta"]);
        if (!filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
            $gecerli = false;
        }
    }

    if (empty($_POST["tckn"])) {
        $gecerli = false;
    } else {
        $tckn = test_input($_POST["tckn"]);
    }

    if (empty($_POST["telefon"])) {
        $gecerli = false;
    } else {
        $telefon = test_input($_POST["telefon"]);
    }

    if (empty($_POST["dogumtarihi"])) {
        $gecerli = false;
    } else {
        $dogumtarihi = test_input($_POST["dogumtarihi"]);
    }

    if (empty($_POST["aydinlatma"])) {
        $gecerli = false;
    } else {
        $aydinlatma = test_input($_POST["aydinlatma"]);
    }

    if (empty($_POST["acikriza"])) {
        $gecerli = false;
    } else {
        $acikriza = test_input($_POST["acikriza"]);
    }


    if (empty($_POST["cinsiyet"])) {
        $gecerli = false;
    } else {
        $cinsiyet = test_input($_POST["cinsiyet"]);
    }


    if ($gecerli) {
        $kayitid = $tckn;
        $kalansorular_sql = "SELECT * FROM (SELECT soru_no FROM kisiliktesti_secenekleri
EXCEPT
SELECT soru_no FROM temp WHERE kayit_id ='" . $kayitid . "') as br, kisiliktesti_secenekleri ks WHERE br.soru_no = ks.soru_no";
        $kalansorular = $conn->query($kalansorular_sql);

        $dogumgunu = explode(".", $dogumtarihi);
        $gun = intval($dogumgunu[0]);
        $ay = intval($dogumgunu[1]);
        function burc($gun, $ay)
        {
            $burc = array('', 'Oğlak', 'Kova', 'Balık', 'Koç', 'Boğa',
                'İkizler', 'Yengeç', 'Aslan', 'Başak', 'Terazi', 'Akrep', 'Yay', 'Oğlak');
            $son_gun = array('', 19, 18, 20, 20, 20, 22, 22, 22, 22, 22, 21, 19, 19);
            return ($gun > $son_gun[$ay]) ? $burc[$ay + 1] : $burc[$ay];
        }

        $burc = burc($gun, $ay);
        $x = 1;
        while ($test = $kalansorular->fetch_assoc()) {
            $karisik[$x] = $test['soru_no'];
            $x++;
        }
        shuffle($karisik);
        $baslangic = date('d/m/Y H:i:s',time());

        $conn = new mysqli("localhost", "root", "1234", "dakik");
        $stmt = $conn->prepare("INSERT INTO testi_dolduranlar(`tckn`,`mail`,`ad`,`soyad`,`mobil`,`dtarihi`,`burc`,`cinsiyet`,`kayit_zamani`) VALUES(?,?,?,?,?,?,?,?,?)");
        $stmt2 = $conn->prepare("INSERT INTO cevaplar(`tckn`,`baslangic`) VALUES(?,?)");
        $stmt->bind_param("sssssssss", $tckn, $eposta, $ad, $soyad, $telefon, $dogumtarihi, $burc, $cinsiyet, $baslangic);
        $stmt2->bind_param("ss", $tckn,$baslangic);
        $_SESSION['tckn'] = $tckn;
        $_SESSION['ad'] = $ad;
        $_SESSION['soyad'] = $soyad;
        $stmt->execute();
        $stmt2->execute();
        $stmt->close();
        $stmt2->close();
        $conn->close();
        header('Location: kisiliktesti.php?soru=' . $karisik[0]);
    }
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <html lang="tr" dir="ltr">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
              integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
              crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
                integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
                crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
                crossorigin="anonymous"></script>
        <link rel="stylesheet" href="style.css">
    </head>
<body>
<div class="container">
    <div class="title">Quiz App</div>
    <div class="content">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">First Name</span>
                    <input type="text" name="ad" id="dene" value="<?php echo $ad; ?>" required>
                </div>
                <div class="input-box">
                    <span class="details">Last Name</span>
                    <input type="text" name="soyad" id="soyad" value="<?php echo $soyad; ?>" required>
                </div>
                <div class="input-box">
                    <span class="details">Personal ID</span>
                    <input type="text" name="tckn" id="tckn" value="<?php echo $tckn; ?>" maxlength="11" required>
                </div>
                <div class="input-box">
                    <span class="details">E-mail address</span>
                    <input type="email" name="eposta" value="<?php echo $eposta; ?>" required>
                </div>
                <div class="input-box">
                    <span class="details">Date of birth</span>
                    <input type="text" name="dogumtarihi" value="<?php echo $dogumtarihi; ?>" class="date-slashes"
                           maxlength="10" required>
                </div>
                <div class="input-box">
                    <span class="details">Phone number</span>
                    <input type="text" name="telefon" id="tel" value="<?php echo $telefon; ?>" maxlength="10" required>
                </div>
                <div class="input-box">
                    <span class="details">Gender</span>
                    <select class="cinsiyet" name="cinsiyet" required>
                        <option value="" selected disabled>Please Select...</option>
                        <option value="Erkek" name="cinsiyet"
                            <?php if (isset($_POST['cinsiyet']) &&
                                $_POST['cinsiyet'] == "Erkek") echo "selected"; ?>>Erkek
                        </option>
                        <option value="Kadın" name="cinsiyet"
                            <?php if (isset($_POST['cinsiyet']) &&
                                $_POST['cinsiyet'] == "Kadın") echo "selected"; ?>>Kadın
                        </option>
                    </select>
                </div>
                <div class="kvk">
                    <span class="details">Terms&Conditions</span>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="aydinlatma"
                               id="flexCheckDefault" <?php if (isset($_POST['aydinlatma']))
                            echo "checked='checked'"; ?> required>
                        <small>By clicking, I agree <a href="http://www.dakik.com.tr/" target="_blank"> terms and conditions
                                </a></small>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="button">
                    <input type="submit" name="submit" value="Start Test">
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade aydinlatma" id="aydinlatma" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script src="script.js"></script>