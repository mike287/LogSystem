<?php

session_start();

if(!isset($_SESSION['successRej']))
{
    header('Location:index.php');
    exit();
}
else
{
    unset($_SESSION['successRej']);
}

// usuwamy zmienne pamietajace wartosci wpisane do formularza

if(isset($_SESSION['fr_nick'])) unset($_SESSION['fr_nick']);
if(isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
if(isset($_SESSION['fr_password1'])) unset($_SESSION['fr_password1']);
if(isset($_SESSION['fr_password2'])) unset($_SESSION['fr_password2']);
if(isset($_SESSION['fr_regulamin'])) unset($_SESSION['fr_regulamin']);

// usuwanie błedow rejestracji

if(isset($_SESSION['e_nick'])) unset($_SESSION['e_nick']);
if(isset($_SESSION['e_mail'])) unset($_SESSION['e_mail']);
if(isset($_SESSION['e_password'])) unset($_SESSION['e_password']);
if(isset($_SESSION['e_regulamin'])) unset($_SESSION['e_regulamin']);
if(isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);


?>

<!DOCTYPE html>

<html>
    <head>
        <title>Krwawe ale dobre</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    
    <body>
      
     Fajnie że jesteś z nami ;)
     <br /><br />
     
     <a href="index.php">zaloguj sie do gry</a>
     
        
    </body>
</html>
