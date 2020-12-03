<?php
session_start();
if (isset($_POST['email']))
{
 //udana walidacja
    $walidacja=true;
//sprawdz login
    $login=$_POST['login'];
    if((strlen($login)<5) ||(strlen($login)>20))
{
$walidacja=false;
$_SESSION['e_login']="Login musi posiadac od 5 do 20 znaków!";
}
    if(ctype_alnum($login)==false){
        $walidacja=false;
        $_SESSION['e_login']="Login moze skladać się tylko z liter i cyfr, bez Polskich znaków";
    }
    // sprawdz imie
    $imie=$_POST['imie'];
    if((strlen($imie)<3) ||(strlen($imie)>20))
    {
        $walidacja=false;
        $_SESSION['e_imie']="Imię musi posiadac od 3 do 20 znaków!";
    }
    if(ctype_alnum($imie)==false){
        $walidacja=false;
        $_SESSION['e_imie']="Imię moze skladać się tylko z liter, bez Polskich znaków";
    }
    // sprawdz pesel
    $pesel=$_POST['pesel'];
    if((strlen($pesel)!=11))
    {
        $walidacja=false;
        $_SESSION['e_pesel']="Pesel musi posiadac 11 znaków!";
    }
    if(ctype_digit($pesel)==false){
        $walidacja=false;
        $_SESSION['e_pesel']="Pesel może się skladać tylko z cyfr";
    }
 // sprawdz telefon
    $telefon=$_POST['telefon'];
    if((strlen($telefon)!=9))
    {
        $walidacja=false;
        $_SESSION['e_telefon']="Numer telefonu musi posiadac 9 znaków!";
    }
    if(ctype_digit($telefon)==false){
        $walidacja=false;
        $_SESSION['e_telefon']="Numer telefonu może się skladać tylko z cyfr";
    }
    //sprawdz nazwisko
    $nazwisko=$_POST['nazwisko'];
    if((strlen($nazwisko)<3) ||(strlen($nazwisko)>20))
    {
        $walidacja=false;
        $_SESSION['e_nazwisko']="Nazwisko musi posiadac od 3 do 20 znaków!";
    }
    if(ctype_alnum($nazwisko)==false){
        $walidacja=false;
        $_SESSION['e_nazwisko']="Nazwisko moze skladać się tylko z liter, bez Polskich znaków";
    }
    //sprawdz email
    $email=$_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
    {
        $walidacja=false;
        $_SESSION['e_email']="Podaj poprawny adres e-mail!";
    }
    //sprawdz haslo
    $haslo1=$_POST['haslo1'];
    $haslo2=$_POST['haslo2'];
    if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
    {
        $walidacja=false;
        $_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków!";
    }

    if ($haslo1!=$haslo2)
    {
        $walidacja=false;
        $_SESSION['e_haslo']="Podane hasła nie są identyczne!";

    } $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
//Czy zaakceptowano regulamin?

    if (!isset($_POST['regulamin']))
    {
        $walidacja=false;
        $_SESSION['e_regulamin']="Potwierdź akceptację regulaminu!";
    }

    //sprawdzenie captchy
   /* $captcha="6LdPje0ZAAAAAF9O34_38XxWRvq769RibxImCmZD";
    $sprawdz=file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret'.$captcha.'&response='.$_POST['g-recaptcha-response']);
    $odpowiedz= json_decode($captcha);

    if($odpowiedz==false)
    {
        $walidacja=false;
        $_SESSION['e_captcha']="Potwierdź, że nie jesteś robotem!";
    } */
//zapamietaj wprowadzone dane
    $_SESSION['fr_imie']=$imie;
    $_SESSION['fr_nazwisko']=$nazwisko;
    $_SESSION['fr_pesel']=$pesel;
    $_SESSION['fr_telefon']=$telefon;
    $_SESSION['fr_email']=$email;
    $_SESSION['fr_login']=$login;
    $_SESSION['fr_haslo1']=$haslo1;
    $_SESSION['fr_haslo2']=$haslo2;
    mysqli_report(MYSQLI_REPORT_STRICT);
    try{
        $polaczenie= mysqli_connect("localhost", "root", "", "mydb");
        if($polaczenie->connect_errno!=0)
        {
            throw new Exception(mysqli_connect_error());
        }
        else{
            //sprawdzenie czy email juz istnieje
            $rezultat=$polaczenie->query("SELECT id FROM klienci WHERE Adres_email='$email'");
            if(!$rezultat) throw new Exception($polaczenie->error);
            $ile_maili=$rezultat->num_rows;
            if($ile_maili>0)

                {
                    $walidacja=false;
                    $_SESSION['e_email']="Istnieje juz konto o podanym adresie email";
                }
            //sprawdzenie czy login
            $rezultat=$polaczenie->query("SELECT id FROM klienci WHERE Login='$login'");
            if(!$rezultat) throw new Exception($polaczenie->error);
            $ile_loginow=$rezultat->num_rows;
            if($ile_loginow>0)

            {
                $walidacja=false;
                $_SESSION['e_login']="Istnieje juz konto o podanym Loginie";
            }
            if($walidacja==true)
            {
                if($polaczenie->query("INSERT INTO klienci VALUES ('','$imie','$nazwisko','$pesel',
                            '$email','$telefon','$login','$haslo_hash')" )){

                    $_SESSION['udanarejestracja']=true;
                    header('Location: witamy.php');
                }
                else
                {
                    throw new Exception($polaczenie->error);
                }
            }


            $polaczenie->close();
        }

    }
    catch (Exception $e){
        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
        echo '<br />Informacja : '.$e;
    }



}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css" type="text/css"/>
    <link rel="stylesheet" href="css/home.css" type="text/css">
    <link rel="stylesheet" href="css/slide.css" type="text/css">
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/bootstrap/bootstrap-reboot.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.js"
            integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="JS/slide.js"></script>
    <script type="text/css" src="JS/bootstrap/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/0ac495dccd.js" crossorigin="anonymous"></script>
    <title>Book Your Room</title>
</head>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Book Your Room</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto mr-0">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Strona Główna<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="rejestracja.php">Rejestracja</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="rezerwacja.php">Rezerwacja</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Kontakt.php">Kontakt</a>
            </li>
        </ul>
    </div>
</nav>
<div style="text-align: center">
<form method="post">
    Imię: <br/> <input type="text" value="<?php
    if(isset($_SESSION['fr_imie']))
    {
        echo $_SESSION['fr_imie'];
        unset($_SESSION['fr_imie']);
    }
?>" name="imie"> <br>
    <?php
    if(isset($_SESSION['e_imie']))
    {
        echo '<div class="error">'.$_SESSION['e_imie'].'</div>';
        unset($_SESSION['e_imie']);
    }
    ?>
    Nazwisko: <br/> <input type="text" value="<?php
    if(isset($_SESSION['fr_nazwisko']))
    {
        echo $_SESSION['fr_nazwisko'];
        unset($_SESSION['fr_nazwisko']);
    }
    ?>" name="nazwisko"> <br>
    <?php
    if(isset($_SESSION['e_nazwisko']))
    {
        echo '<div class="error">'.$_SESSION['e_nazwisko'].'</div>';
        unset($_SESSION['e_nazwisko']);
    }
    ?>
    Pesel: <br/> <input type="text" value="<?php
    if(isset($_SESSION['fr_pesel']))
    {
        echo $_SESSION['fr_pesel'];
        unset($_SESSION['fr_pesel']);
    }
    ?>" name="pesel"> <br>
    <?php
    if(isset($_SESSION['e_pesel']))
    {
        echo '<div class="error">'.$_SESSION['e_pesel'].'</div>';
        unset($_SESSION['e_pesel']);
    }
    ?>
    Telefon: <br/> <input type="text" value="<?php
    if(isset($_SESSION['fr_telefon']))
    {
        echo $_SESSION['fr_telefon'];
        unset($_SESSION['fr_telefon']);
    }
    ?>" name="telefon"> <br>
    <?php
    if(isset($_SESSION['e_telefon']))
    {
        echo '<div class="error">'.$_SESSION['e_telefon'].'</div>';
        unset($_SESSION['e_telefon']);
    }
    ?>

    E-mail: <br/> <input type="text" value="<?php
    if(isset($_SESSION['fr_email']))
    {
        echo $_SESSION['fr_email'];
        unset($_SESSION['fr_email']);
    }
    ?>" name="email"> <br>
    <?php
    if(isset($_SESSION['e_email']))
    {
        echo '<div class="error">'.$_SESSION['e_email'].'</div>';
        unset($_SESSION['e_email']);
    }
    ?>
    Login: <br/> <input type="text" value="<?php
    if(isset($_SESSION['fr_login']))
    {
        echo $_SESSION['fr_login'];
        unset($_SESSION['fr_login']);
    }
    ?>" name="login"> <br>
    <?php
    if(isset($_SESSION['e_login']))
    {
        echo '<div class="error">'.$_SESSION['e_login'].'</div>';
        unset($_SESSION['e_login']);
    }
        
        ?>
    Twoje hasło: <br/> <input type="password" value="<?php
    if(isset($_SESSION['fr_haslo1']))
    {
        echo $_SESSION['fr_haslo1'];
        unset($_SESSION['fr_haslo1']);
    }
    ?>" name="haslo1"> <br>
        <?php
    if(isset($_SESSION['e_haslo']))
    {
        echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
        unset($_SESSION['e_haslo']);
    }
    ?>
    Powtórz hasło: <br/> <input type="password"  value="<?php
    if(isset($_SESSION['fr_haslo2']))
    {
        echo $_SESSION['fr_haslo2'];
        unset($_SESSION['fr_haslo2']);
    }
    ?>" name="haslo2"> <br>
    <br/>
    <label>
    <input type="checkbox" name="regulamin"/> Akceptuje regulamin
    </label>
    <br/>
    <?php
    if(isset($_SESSION['e_regulamin']))
    {
        echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
        unset($_SESSION['e_regulamin']);
    }
    ?>
    <!--<div class="g-recaptcha" data-sitekey="6LdPje0ZAAAAANLroAJl0AowLt9ogzYvv9v7kBrh"></div> -->
    <?php
    /*if(isset($_SESSION['e_captcha']))
    {
        echo '<div class="error">'.$_SESSION['e_captcha'].'</div>';
        unset($_SESSION['e_captcha']);
     }
    */
    ?>
    <br/>
    <input type="submit" value="Zarejestruj się">

</form>
</div>

<br/><br/>
<br/><br/>
<br/><br/>


<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h6>O nas</h6>
                <p class="text-justify"> Z Sunset and Sunrise, położonym na klifie obok odległej, smaganej wiatrem plaży na zachodnim wybrzeżu Bali,
                    ze wspaniałym 360-stopniowym widokiem na góry, pola ryżowe i Ocean Indyjski, jest spektakularną enklawą willi
                    znaną jako Kembangdesa. Ta posiadłość położona jest zaledwie 30 minut na północ od kurortów Tanah Lot,
                    na wspaniałym zakolu wybrzeża,</p>
            </div>

            <!-- <div class="col-xs-6 col-md-3">
                 <h6>Categories</h6>
                 <ul class="footer-links">
                     <li><a href="http://scanfcode.com/category/c-language/">C</a></li>
                     <li><a href="http://scanfcode.com/category/front-end-development/">UI Design</a></li>
                     <li><a href="http://scanfcode.com/category/back-end-development/">PHP</a></li>
                     <li><a href="http://scanfcode.com/category/java-programming-language/">Java</a></li>
                     <li><a href="http://scanfcode.com/category/android/">Android</a></li>
                     <li><a href="http://scanfcode.com/category/templates/">Templates</a></li>
                 </ul>
             </div>
 -->
            <div class="col-xs-6 col-md-3">
                <h6>Szybki dostęp</h6>
                <ul class="footer-links">
                    <li><a href="http://scanfcode.com/about/">O nas</li>
                    <li><a href="http://scanfcode.com/contact/">Skontaktuj się</a></li>
                </ul>
            </div>
        </div>
        <hr>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-6 col-xs-12">
                <p class="copyright-text">Copyright &copy; 2020 All Rights Reserved by
                    <a href="#">Kacper</a>.
                </p>
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <ul class="social-icons">
                    <li><a class="facebook" href="https://pl-pl.facebook.com"><i class="fa fa-facebook"></i></a></li>
                    <li><a class="twitter" href="https://twitter.com"><i class="fa fa-twitter"></i></a></li>
                    <li><a class="instagram" href="https://www.instagram.com"><i class="fa fa-instagram"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
</body>

</html>