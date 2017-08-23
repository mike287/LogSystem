<?php

session_start();

if((isset($_SESSION['inTheSystem'])) && ($_SESSION['inTheSystem'] == true))
{
    header('Location:game.php');
    exit();
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
      
     Tylko praktyka uczyni z Ciebie mistrza!
     <br /><br />
     
     <a href="rejestration.php">REJESTRACJA</a>
     
     <form action = "login.php" method="post">
         
         Login:<br /> <input type="text" name="login"><br />
         Password:<br /> <input type="password" name="password"><br />
         <input type="submit" value="Log in"><br />
                  
     </form>
     
     <?php
        
        if(isset($_SESSION['error'])){
             echo $_SESSION['error']; 
        }
     
     
    ?>
        
    </body>
</html>
