<?php

session_start();
if(isset($_POST['submit']))
{
    // Udana walidacja - tak - flaga
    $success = true;
    
    // sprawdzam nick'a z baza
    
    $nick = $_POST['nick'];
    // sprawdzam dlugosc nicka 
    if ((strlen($nick) < 3) || (strlen($nick) > 20))
    {
        $success = false;
        $_SESSION['e_nick'] = "Nick musi posiadać od 3 do 20 znaków";
    }
    
    if (ctype_alnum($nick) == false)
    {
        $success = false;
        $_SESSION['e_nick'] = "Nick może składać tylko się z liter i cyfr";
    }
    
    //email sprawdzenie
    
    $email = $_POST['email'];
    $emailOk = filter_var($email, FILTER_SANITIZE_EMAIL);
    
   
    if ((filter_var($emailOk, FILTER_VALIDATE_EMAIL == false) || $emailOk != $email))
    {
        $success = false;
        $_SESSION['e_mail'] = "popraw wartość email";
    }
    
    // sprwadzam popranosc hasla
    
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    
    if((strlen($password1)<8) || (strlen($password1)>20))
    {
        $success = false;
        $_SESSION['e_password'] = "hasło musi posiadać od 8 do 20 znaków";
    }
    if ($password1 != $password2)
    {
        $success = false;
        $_SESSION['e_password'] = "podane hasła są różne";
    }
    
    $password_hash = password_hash($password1, PASSWORD_DEFAULT);

    // czy zaakceptowano regulamin?
    
    if(!isset($_POST['regulamin']))
    {
        $success = false;
        $_SESSION['e_regulamin'] = "Potwierdz akceptacje regulaminu";
        
    }
    
    // bot or no bot !
    
    $secret = "6LdHhCQUAAAAAHEstNilZdq6b2YQxH0STyhhR-8o";
    $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    
    $answer = json_decode($check);
    
    if ($answer->success==false)
    {
        $success = false;
        $_SESSION['e_bot'] = "potwierdz że nie jesteś botem";
    }
    
 // zapamietaj podane dane
    
   $_SESSION['fr_nick'] = $nick;
   $_SESSION['fr_email'] = $email;
   $_SESSION['fr_password1'] = $password1;
   $_SESSION['fr_password2'] = $password2;
   if(isset($_POST['regulamin'])) $_SESSION['fr_regulamin'] = true;
   
    
 // polaczenie z baza   
    require_once 'connect.php';
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try{
        
     $dbh = new mysqli($host, $db_user, $db_password, $db_name);   
        if ($dbh->connect_errno!=0)
        {
        throw new Exception(mysqli_connect_errno());
        }
        else
        {
            // czy email istnieje
            
            $rezult = $dbh->query("SELECT id FROM uzytkownicy WHERE email ='$email'");
            if (!$rezult) throw new Exception($dbh->error);
            
            $howManyEmails = $rezult->num_rows;
            if($howManyEmails > 0)
            {
            $success = false;
            $_SESSION['e_mail'] = "istnieje juz konto z takim emailem";
            }
            
            // czy login istnieje
            
            $rezult = $dbh->query("SELECT id FROM uzytkownicy WHERE user ='$nick'");
            if (!$rezult) throw new Exception($dbh->error);
            
            $howManyLogins = $rezult->num_rows;
            if($howManyLogins > 0)
            {
            $success = false;
            $_SESSION['e_nick'] = "istnieje juz konto z takim loginem";
            }
            
            if($success == true)
            {
            // SUKCES WSZYSTKO PRZESZŁO !!!
            if ($dbh->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$password_hash', '$email', 100, 100, 100, 14)"))
            {
               $_SESSION['successRej'] = true;
               header('Location:welcome.php');
            }
            else
            {
                throw new Exception($dbh->error); 
            }
            }
            
            $dbh->close();
        }
        
        
        
    } catch (Exception $ex) {

        echo '<span style = "color:red;">Błąd serwera! Przepraszamy</span>';
        echo '<br />Informacja developerska: '.$ex;
    }
    
       
    
    
}

?>
<!DOCTYPE html>

<html>
    <head>
        <title>NEW ACCOUNT</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src='https://www.google.com/recaptcha/api.js'></script>
        
        
        <style>
            .error
            {
                color:red;
                margin-top:10px;
                margin-bottom:10px;
            }
        </style>
        
        
    </head>
    
    <body>
        
        <form method="post">
            
            Nickname: <br /> <input type="text" name="nick" value="<?php 
            if (isset($_SESSION['fr_nick']))
            {
                echo $_SESSION['fr_nick'];
                unset($_SESSION['fr_nick']);
            }
                    
                    ?>" /><br />
            <?php 
            
            if (isset($_SESSION['e_nick']))
            {
                echo '<div class = "error">'.$_SESSION['e_nick'].'</div>';
                unset($_SESSION['e_nick']);
            }
            
            ?>
            
            E-mail: <br /> <input type="text" name="email" value="<?php 
            if (isset($_SESSION['fr_email']))
            {
                echo $_SESSION['fr_email'];
                unset($_SESSION['fr_email']);
            }
             ?>"  /><br />
            
            <?php 
            
            if (isset($_SESSION['e_mail']))
            {
                echo '<div class = "error">'.$_SESSION['e_mail'].'</div>';
                unset($_SESSION['e_mail']);
            }
            
            ?>
            
            Password: <br /> <input type="password" name="password1" value="<?php 
            if (isset($_SESSION['fr_password1']))
            {
                echo $_SESSION['fr_password1'];
                unset($_SESSION['fr_password1']);
            }
             ?>"  /><br />
            <?php 
            
            if (isset($_SESSION['e_password']))
            {
                echo '<div class = "error">'.$_SESSION['e_password'].'</div>';
                unset($_SESSION['e_password']);
            }
            
            ?>
            Confirm Your Password: <br /> <input type="password" name="password2" value="<?php 
            if (isset($_SESSION['fr_password2']))
            {
                echo $_SESSION['fr_password2'];
                unset($_SESSION['fr_password2']);
            }
             ?>" /><br />
            <label>
            <input type="checkbox" name="regulamin" value="<?php 
            if (isset($_SESSION['fr_regulamin']))
            {
                echo "checked";
                unset($_SESSION['fr_regulamin']);
            }
            ?>"
                    
                    />akceptuje regulamin
            </label>
            
            <?php 
            
            if (isset($_SESSION['e_regulamin']))
            {
                echo '<div class = "error">'.$_SESSION['e_regulamin'].'</div>';
                unset($_SESSION['e_regulamin']);
            }
            
            ?>
            <div class="g-recaptcha" data-sitekey="6LdHhCQUAAAAAD3jkAAnr4ig5kV_j2E4GRphTBkG"></div><br />
            <?php 
            
            if (isset($_SESSION['e_bot']))
            {
                echo '<div class = "error">'.$_SESSION['e_bot'].'</div>';
                unset($_SESSION['e_bot']);
            }
            
            ?>
            
            
            <input type="submit" name="submit" value="create account"/>
            
            
            
            
        </form>
      
    
        
    </body>
</html>

