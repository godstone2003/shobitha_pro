<?php
$servername = "database";  // Docker service name
$username = "wwwclient23"; // From SQL file
$password = "wwwclient23Creds"; // From SQL file
$dbname = "7009db";        // From SQL file

// Create connection to the Database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Processing the Form Data with validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullnamedata = $_POST['fullname'] ?? '';
    $suggestiondata = $_POST['suggestion'] ?? '';
    
    // Basic validation
    if (empty($fullnamedata) || empty($suggestiondata)) {
        die("Error: All fields are required");
    }
    
    // Using Prepared Statements to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO suggestion (fullname, suggestion) VALUES (?, ?)");
    $stmt->bind_param("ss", $fullnamedata, $suggestiondata);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: /index.php');
        exit();
    } else {
        die("Error: " . $stmt->error);
    }
} else {
    die("Error: Invalid request method");
}
?>