<?php
session_start();
if(!isset($_SESSION['udanarejestracja']))
{
header('Location:rejestracja.php');
exit();
}
else{
    unset($_SESSION['udanarejestracja']);
}
//usuwanie zmiennych pamietajac wartosci
if(isset($_SESSION['fr_imie'])) unset($_SESSION['fr_imie']);
if(isset($_SESSION['fr_nazwisko'])) unset($_SESSION['fr_nazwisko']);
if(isset($_SESSION['fr_pesel'])) unset($_SESSION['fr_pesel']);
if(isset($_SESSION['fr_telefon'])) unset($_SESSION['fr_telefon']);
if(isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
if(isset($_SESSION['fr_login'])) unset($_SESSION['fr_login']);
if(isset($_SESSION['fr_haslo1'])) unset($_SESSION['fr_haslo1']);
if(isset($_SESSION['fr_haslo2'])) unset($_SESSION['fr_haslo2']);

//usuwanie bledow rejestracji
if (isset($_SESSION['e_imie'])) unset($_SESSION['e_imie']);
if (isset($_SESSION['e_nazwisko'])) unset($_SESSION['e_nazwisko']);
if (isset($_SESSION['e_pesel'])) unset($_SESSION['e_pesel']);
if (isset($_SESSION['e_telefon'])) unset($_SESSION['e_telefon']);
if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
if (isset($_SESSION['e_login'])) unset($_SESSION['e_login']);
if (isset($_SESSION['e_haslo1'])) unset($_SESSION['e_haslo1']);
if (isset($_SESSION['e_haslo2'])) unset($_SESSION['e_haslo2']);
?>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Room</title>
    <link rel="stylesheet" href="css/style.css" type="text/css"/>
</head>
<body>
<header><h1>Book Your Room</h1></header>
<nav>
    <a href="index.php">Hotel</a>
    <a href="rejestracja.php">Rejestracja</a>
    <a href="rezerwacja.php">Rezerwacja</a>
    <a href="Kontakt.php">Kontakt</a>
</nav>

<strong>
<div style="text-align: center">
    <br/><br>
    <br/><br>
    <br/><br>
    <br/><br>
    <br/><br>
Dziękujemy za rejestrację w serwisie! Możesz już zalogować się na swoje konto!<br/><br/>
<a href="rezerwacja.php">Zaloguj się na swoje konto!</a>
</div>
</strong>
</body>

</html>

