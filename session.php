<?php
if(!isset($_SESSION)){session_start();}
if(!isset($_SESSION['usr'])){
header("location:.");
exit;
}
?>