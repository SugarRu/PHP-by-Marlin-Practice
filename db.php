<?php 

session_start();

$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
$pdo = new PDO('mysql:host=localhost;dbname=marlin_php', 'root', '', $options);