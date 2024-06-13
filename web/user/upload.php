<?php
// Spustíme session
session_start();

include '../config.php';

// Kontrola, zda je uživatel přihlášen
if (!isset($_SESSION['user_id'])) {
    // Pokud není přihlášen, přesměrujeme ho na stránku pro přihlášení/registraci
    header("Location: /max-play/login-register/index.php");
    exit(); // Ukončíme běh skriptu, aby se zamezilo dalšímu vykonávání kódu
}

// Připojení k databázi (upravte připojovací údaje podle své konfigurace)
// Kontrola připojení
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Získání ID přihlášeného uživatele (předpokládáme, že máte nějaký způsob získání ID)
// Může být nutné upravit, pokud máte jiný způsob správy relací
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Pokud není uživatel přihlášen, můžete zvolit vhodnou akci, např. přesměrování na přihlašovací stránku.
    die("Uživatel není přihlášen.");
}

// Získání informací z nahrávacího formuláře
$title = isset($_POST["title"]) ? $_POST["title"] : "";
for($i=0;$i<4;$i++)
    $field[$i] = isset($_POST['field'][$i]) ? 1 : 0;
$hideView = $field[0];
$passRequired = $field[1];
$pass = isset($_POST["pass"]) ? $_POST["pass"] : "";
$file_type = isset($_FILES["file"]["type"]) ? $_FILES["file"]["type"] : "";
$file_size = isset($_FILES["file"]["size"]) ? $_FILES["file"]["size"] : "";
$image_path = null;

// Kontrola, zda byl vybrán soubor pro úvodní obrázek
if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
    $image_path = "images/"; // Upravte podle vaší konfigurace
    if (!file_exists($image_path)) {
        mkdir($image_path, 0777, true);
    }

    $image_path .= $user_id . "_" . time() . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
} else {
    // Pokud není zvolen úvodní obrázek a soubor je video, použijeme obrázek pro video
    $defaultImageDirectory = 'default-image/';

    // Získáme informace o nahrávaném souboru
    $fileName = $_FILES["file"]["name"];
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

    // Vytvoříme název defaultního obrázku na základě typu souboru
    $image_path = $defaultImageDirectory;
    if (strpos($fileType, 'video') !== false || in_array($fileType, ['mp4', 'avi', 'mkv', 'mov'])) {
        $image_path .= 'video.jpg';
    } elseif (strpos($fileType, 'image') !== false || in_array($fileType, ['jpeg', 'jpg', 'png', 'gif'])) {
        $image_path .= 'obrazek.jpg';
    } elseif (in_array($fileType, ['rar', 'zip'])) {
        $image_path .= 'slozeny.jpg';
    } elseif (in_array($fileType, ['doc', 'docx', 'txt'])) {
        $image_path .= 'text.jpg';
    } else {
        // Pokud není žádný vhodný defaultní obrázek, použijeme obrázek pro ostatní typy souborů
        $image_path .= 'ostatni.jpg';
    }
}

// Cesta ke složce pro ukládání souborů
$upload_directory = "file/";

// Kontrola existence složky a její vytvoření, pokud neexistuje
if (!file_exists($upload_directory)) {
    mkdir($upload_directory, 0777, true);
}

// Získání prvního dostupného čísla pro pojmenování souboru
$file_number = 1;
while (file_exists($upload_directory . $file_number . "." . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION))) {
    $file_number++;
}

// Cesta k souboru
$file_path = $upload_directory . $file_number . "." . pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

// Kontrola velikosti souboru (maximální kapacita 100 MB)
if ($file_size > 100 * 1024 * 1024) {
    die("Soubor přesahuje maximální povolenou velikost (100 MB).");
}

// Přesunutí nahraného souboru na server
move_uploaded_file($_FILES["file"]["tmp_name"], $file_path);

// Vložení informací do databáze
$sql = "INSERT INTO file_id (title, access_key, file_path, file_type, file_size, user_id, hideView, passRequired, pass, image_path) 
        VALUES ('$title', '" . md5($title) . "', '$file_path', '$file_type', $file_size, $user_id, '$hideView', '$passRequired', '" . md5($pass) . "', '$image_path')";

if ($conn->query($sql) === TRUE) {
    header('location: file-detail.php?id=' . $id . '&success');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Uzavření spojení s databází
$conn->close();
?>
