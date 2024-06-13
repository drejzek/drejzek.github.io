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

if(isset($_POST['submit'])){
    // Získání informací z nahrávacího formuláře
    echo $id = $_POST["id"];
    echo $title = $_POST["title"];
    for($i=0;$i<4;$i++)
        $field[$i] = isset($_POST['field'][$i]) ? 1 : 0;
    echo $ViewFile = $field[0];
    echo $hideView = $field[1];
    echo $passRequired = $field[2];
    echo $pass = md5($_POST["pass"]);    

    // Vložení informací do databáze
    $sql = "UPDATE `file_id` 
            SET `title`='$title',`ViewFile`='$ViewFile',`hideView`='$hideView',`passRequired`='$passRequired',`pass`='$pass' WHERE id = $id";


    if ($conn->query($sql) === TRUE) {
        header('location: file-detail.php?id=' . $id . '&success');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Uzavření spojení s databází
    $conn->close();
}

if(isset($_POST['privacySubmit'])){
    // Získání informací z nahrávacího formuláře
    echo $id = $_POST["id"];
    echo $privacy = $_POST["privacy"];

    // Vložení informací do databáze
    $sql = "UPDATE `file_id` 
            SET `privacy`='$privacy' WHERE id = $id";


    if ($conn->query($sql) === TRUE) {
        header('location: file-detail.php?id=' . $id . '&success');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Uzavření spojení s databází
    $conn->close();
}
?>
