<?php

session_start();
if((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
{
header('Location:rezerwacja.php');
}
$polaczenie= @mysqli_connect("localhost", "root", "", "mydb");

if($polaczenie->connect_errno!=0)
{
    echo "Error: ".$polaczenie->connect_errno;
}
else {
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    $login = htmlentities($login, ENT_QUOTES, "UTF-8");

    if ($rezultat = @$polaczenie->query(sprintf("SELECT * FROM klienci WHERE Login='%s'",
        mysqli_real_escape_string($polaczenie, $login)))) {
        $ilu_userow = $rezultat->num_rows;
        if ($ilu_userow > 0) {
            $wiersz = $rezultat->fetch_assoc();
            if (password_verify($haslo, $wiersz['Hasło'])) {
                $_SESSION['zalogowany'] = true;
                $_SESSION['Login'] = $wiersz['Login'];
                $_SESSION['id'] = $wiersz['id'];
                unset($_SESSION['blad']);
                $rezultat->close();
                header('Location:rezerwacja.php');
            } else {
                $_SESSION['blad'] = '<span style="color:red">Nieprawidłowe hasło!</span>';
                header('Location:rezerwacja.php');
            }

        } else {
            $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
            header('Location:rezerwacja.php');
        }
    }
    $polaczenie->close();
}
