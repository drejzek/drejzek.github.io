<?php
session_start();

// Připojení k databázi
$conn = new mysqli("localhost", "mafie", "Fousek2006196", "max-play");

// Zpracování formuláře registrace
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitRegister"])) {
    $regUsername = $_POST["regUsername"];
    $regPassword = password_hash($_POST["regPassword"], PASSWORD_DEFAULT);

    // Vložení uživatele do databáze
    $query = "INSERT INTO users (username, password) VALUES ('$regUsername', '$regPassword')";
    $result = $conn->query($query);

    if ($result) {
        // Úspěšná registrace, nyní přihlásit uživatele
        $loginQuery = "SELECT * FROM users WHERE username='$regUsername'";
        $loginResult = $conn->query($loginQuery);

        if ($loginResult && $loginResult->num_rows > 0) {
            $user = $loginResult->fetch_assoc();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            // Přesměrování na max-play/web/index.php
            header("Location: /max-play/web/index.php");
            exit();
        } else {
            echo "Chyba při přihlášení po registraci.";
        }
    } else {
        echo "Chyba při registraci: " . $conn->error;
    }
}
?>
