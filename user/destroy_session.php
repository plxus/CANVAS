<?php
  session_start();

  if(isset($_SESSION['username'])){
    $_SESSION=array();
    setcookie(session_name(),'',time()-2592000,'/');
    session_destroy();
  }
