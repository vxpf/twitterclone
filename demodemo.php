<?php
try {
$conn = new PDO("mysql:host=localhost;dbname=login_system", "root", "", [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);
} catch (PDOException $e) {
die("Connectiefout: " . $e->getMessage());
}

$sql = "SELECT * FROM users WHERE name = :wie AND woonplaats = :waar";
$stmt = $conn->prepare($sql);

$stmt->execute(
    [
        "wie" => 'marcl',
        "waar" => 'Rotterdam'
    ]
);

while($row = $stmt->fetch()) {
    echo $row['name'] . " / " . $row['email'] . "<br>";
}