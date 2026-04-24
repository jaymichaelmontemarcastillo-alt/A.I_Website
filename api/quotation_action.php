<?php
session_start();
require_once '../connect/config.php';

$pdo = getDBConnection();

$id = $_POST['id'];
$action = $_POST['action'];

if ($action == 'accept') {
    $status = 'accepted';
} else {
    $status = 'cancelled';
}

$stmt = $pdo->prepare("UPDATE quotations SET status=? WHERE id=?");
$stmt->execute([$status, $id]);

header("Location: ../quotations.php");
exit;
