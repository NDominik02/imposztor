<?php
// Feltöltési könyvtár beállítása
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Store error messages
$error_message = "";

// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $error_message .= "File is an image - " . $check["mime"] . ". ";
        $uploadOk = 1;
    } else {
        $error_message .= "File is not an image. ";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    $error_message .= "Sorry, file already exists. ";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 5242880) { // 5 MB = 5 * 1024 * 1024 bytes
    $error_message .= "Sorry, your file is too large. ";
    $uploadOk = 0;
}

// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    $error_message .= "Sorry, only JPG, JPEG, PNG files are allowed. ";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    // Redirect to the form with error message
    header("Location: addPost.php?error=" . urlencode($error_message));
    exit();
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // Redirect to the form with success message
        header("Location: addPost.php?success=The file " . urlencode(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.");
        exit();
    } else {
        // Redirect to the form with error message
        header("Location: addPost.php?error=Sorry, there was an error uploading your file.");
        exit();
    }
}
?>