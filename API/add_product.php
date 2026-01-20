<?php
header("Content-Type: application/json");
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$name = $data["name"];
$price = $data["price"];

$sql = "INSERT INTO products (name, price) VALUES ('$name', '$price')";

if ($conn->query($sql)) {
    echo json_encode(["message" => "Product Added"]);
} else {
    echo json_encode(["message" => "Error"]);
}
?>
