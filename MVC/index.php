<?php
require_once('connection.php');
if (isset($_GET['controllers'])) {
  $controller = $_GET['controllers'];
  if (isset($_GET['action'])) {
     $action = $_GET['action'];
  } else {
     $action = 'index';
  }
} 
else 
  if(isset($_POST['controllers']))
  {
     $controller = $_POST['controllers'];
     if (isset($_POST['action']))
     {
        $action = $_POST['action'];
     } else 
     {
        $action = 'index';
     }
  }
  else
  {
    $controller = 'home';
    $action = 'index';
  }
require_once('router.php');
?>