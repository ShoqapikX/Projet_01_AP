<?php   

 session_start();   
 session_unset();   
 session_destroy(); 
// redirection vers page d'accueil après déco
 header('Location: ../index.php');  
 exit(); // pour s'assurer que le script s'arrête après la déco
?>