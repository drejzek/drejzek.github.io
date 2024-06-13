<?php
session_start();

// Zničení všech dat v session
session_unset();
session_destroy();

// Přesměrování na přihlašovací stránku
header("Location: index.php");
exit();
?>
