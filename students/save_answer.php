<?php 

session_start();

$questionno = $_POST['questionno'];
$value = $_POST['value'];

$_SESSION['answer'][$questionno] = $value;

