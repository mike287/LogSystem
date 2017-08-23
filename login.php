<?php

session_start();

if(!isset($_POST['login']) || (!isset($_POST['password'])))
{
    header('Location:index.php');
    exit();
}
require_once 'connect.php';

$dbh = @new mysqli($host, $db_user, $db_password, $db_name);

    if ($dbh->connect_errno!=0)
    {
        echo "ERROR:".$dbh->connect_errno;
    }
    else
    {
        $login = $_POST['login'];
        $password = $_POST['password'];
        
        //zabezpieczenie !!!
        
                $login = htmlentities($login, ENT_QUOTES, "UTF-8");

	
		if ($rezult = @$dbh->query(
		sprintf("SELECT * FROM uzytkownicy WHERE user='%s'",
		mysqli_real_escape_string($dbh,$login))))
        {
            $userNbr = $rezult->num_rows;
            if($userNbr > 0)
            {
                    $row = $rezult->fetch_assoc();
                    if(password_verify($password, $row['pass']))
                    {

                    $_SESSION['inTheSystem'] = true;


                    $_SESSION['id'] = $row['id'];
                    $_SESSION['user'] = $row['user'];
                    $_SESSION['drewno'] = $row['drewno'];
                    $_SESSION['kamien'] = $row['kamien'];
                    $_SESSION['zboze'] = $row['zboze'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['dnipremium'] = $row['dnipremium'];

                    unset($_SESSION['error']);
                    $rezult->close();
                    header('Location:game.php');
                }
                else
                {
                 
                $_SESSION['error'] = '<span style = "color:red">Nieprawidłości napotkano przybyszu</span>';
                header('Location:index.php');
                }
                
            }else{
                 
                $_SESSION['error'] = '<span style = "color:red">Nieprawidłości napotkano przybyszu</span>';
                header('Location:index.php');
            }
        }
                
        $dbh->close();
    }


    $login = $_POST['login'];
    $password = $_POST['password'];
  
    


?>