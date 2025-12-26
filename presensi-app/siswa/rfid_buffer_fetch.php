<?php
include '../config.php';
header('Content-Type: application/json');

$sql = "SELECT RFID FROM rfid_buffer ORDER BY id DESC LIMIT 1";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    echo json_encode(['rfid' => $row['RFID']]);
} else {
    echo json_encode(['rfid' => '']);
}
