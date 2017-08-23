<?php

session_start();
if(!isset($_SESSION['inTheSystem']))
{
    header('Location:index.php');
}

?>

<!DOCTYPE html>

<html>
    <head>
        <title>Krwawe ale dobre</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>
    
    <body>
      
    <?php
    
    echo "<p>Witaj ".$_SESSION['user']."!";
    echo '<strong><a href="logOut.php">[wyloguj sie!]</a></strong>';
    echo "<p><strong>Drewno:</strong>".$_SESSION['drewno'];
    echo "|<strong>Kamien:</strong>".$_SESSION['kamien'];
    echo "|<strong>Zboze:</strong>".$_SESSION['zboze'];
    echo "<p><strong>E-mail:</strong>".$_SESSION['email'];
    echo "<p><strong>Dni Premium:</strong>".$_SESSION['dnipremium'];
    echo "<br>";
   
    ?>

        
    </body>
</html>
