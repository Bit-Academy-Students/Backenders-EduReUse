<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$id = (int) $_GET['id'];

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());


$sql = "DELETE FROM offers WHERE id = :id;";
$stmt = $conn->prepare($sql);
$delete = $stmt->execute(['id' => $id]);



 header('Location: /user/posts');
    exit;

?>