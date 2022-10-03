<?php
date_default_timezone_set('Europe/Istanbul');
session_start();
$conn = new mysqli("localhost", "root", "1234", "dakik");
$tckn = $_SESSION['tckn'];
$ad = $_SESSION['ad'];
$soyad = $_SESSION['soyad'];
$bitis = date('d/m/Y H:i:s',time());
$stmt = $conn->prepare("UPDATE `cevaplar` SET bitis = '".$bitis."' WHERE tckn = ".$tckn);
$del = $conn->prepare("DELETE FROM temp WHERE kayit_id = '".$tckn."' ");
$stmt->execute();
$del->execute();
$del->close();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html lang="tr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <title>dakik - Envanter Testi</title>
</head>
<body>
<div class="container">
    <div class="title">dak.ik - Envanter Testi</div>
    <div class="content">
        <div class="container mb-5">
            <div class="row">
                <div class="col-12">
                    <p class="fw-bold" style="text-align: center">Tebrikler! Testi başarıyla tamamladınız.
                    </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
