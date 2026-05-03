<?php
// Create uploads folder if it doesn't exist
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Get form data
$title = htmlspecialchars($_POST['title']);
$notice = htmlspecialchars($_POST['notice']);
$fileName = '';

// Handle file upload
if(isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == 0){
    $fileName = basename($_FILES['fileUpload']['name']);
    $targetFile = $uploadDir . $fileName;
    move_uploaded_file($_FILES['fileUpload']['tmp_name'], $targetFile);
}

// Store notices in JSON file
$dataFile = 'notices.json';
$notices = [];

if(file_exists($dataFile)){
    $json = file_get_contents($dataFile);
    $notices = json_decode($json, true);
}

// Add new notice
$notices[] = [
    'title' => $title,
    'text' => $notice,
    'file' => $fileName,
    'date' => date("Y-m-d H:i:s")
];

// Save back to JSON
file_put_contents($dataFile, json_encode($notices, JSON_PRETTY_PRINT));

header("Location: admin.html?success=1");
exit;
?>
