<?php
// Spustíme session
session_start();

// Kontrola, zda je uživatel přihlášen
if (!isset($_SESSION['user_id'])) {
    // Pokud není přihlášen, přesměrujeme ho na stránku pro přihlášení/registraci
    header("Location: /max-play/login-register/index.php");
    exit(); // Ukončíme běh skriptu, aby se zamezilo dalšímu vykonávání kódu
}

$modal = '    
        <script>
        $( document ).ready(function() {
            $("#success").modal("show");
        });
        </script>';

// Pokračujeme s obsahem stránky nebo provádíme další kód, který je určený přihlášeným uživatelům
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nahrání souboru</title>
    <script>
        function uploadFile() {
            document.querySelector('#submit').disabled = true;
            var formData = new FormData(document.getElementById("uploadForm"));
            var xhr = new XMLHttpRequest();

            xhr.upload.addEventListener("progress", function (e) {
                if (e.lengthComputable) {
                    var percentComplete = (e.loaded / e.total) * 100;
                    document.getElementById("submit").value = "Nahrávám " + percentComplete.toFixed(2) + " %";
                }
            }, false);

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Zde můžete zpracovat odpověď serveru (pokud je nějaká)
                    $("#success").modal("show");
                }
                else if (xhr.readyState == 4 && xhr.status == 500){
                    $("#error").modal("show");
                }
            };

            xhr.open("POST", "update.php", true);
            xhr.send(formData);
        }
    </script>
</head>
<body>

<?php include '../header.php'; ?>
<?php

if(isset($_GET['success'])){
    echo $modal;
}

?>

<div class="container bg-body-tertiary border p-3">
    <h2>Nahrání souboru</h2>
    <form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Název souboru</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" name="title" required>
        </div>

        <div class="mb-3">
            <label for="formFile" class="form-label">Vyberte soubor k nahrání:</label>
            <input class="form-control" type="file" id="formFile" name="file" required>
        </div>

        <!-- Nové vstupní pole pro nahrání úvodního obrázku -->
        <div class="mb-3">
            <label for="formFile" class="form-label">Vyberte úvodní obrázek (pokud existuje):</label>
            <input class="form-control" type="file" id="formFile" name="image">
        </div>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="hideView" name="field[0]">
            <label class="form-check-label" for="hideView">Skrýt úvoní obrázek</label>    
        </div>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="PassRequeired" name="field[1]" data-bs-toggle="modal" data-bs-target="#mPass">
            <label class="form-check-label" for="PassRequeired">Vyžadovat heslo</label>
        </div>

        <div class="modal" id="mPass">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h4>Zadejte heslo</h4>
                        <h5>Které bude vyžadováno pro zobrazení tohoto souboru</h5>
                        <div class="input-group mb-3">
                            <input type="password" name="pass" class="form-control" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                            <button class="btn btn-outline-success" type="button" id="button-addon1" data-bs-dismiss="modal">Uložit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="submit" value="Nahrát soubor" id="submit" class="btn btn-success mt-3">
    </form>
</div>
<div class="modal fade" id="success">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="mx-auto text-center">
                        <i class="far fa-check-circle text-success mb-4" style="font-size:75px"></i>
                        <br>
                        <h4>Soubor byl úpěšně nahrán!</h4>
                        <br>
                        <a href="." class="btn btn-success">Pokračovat <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="error">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="mx-auto text-center">
                        <i class="far fa-times-circle text-danger mb-4" style="font-size:75px"></i>
                        <br>
                        <h4>Vyskytkla se chyba!</h4>
                        <br>
                        <a href="." class="btn btn-success">Pokračovat <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="progress"></div>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body d-flex">
      <span class="me-auto">Hello, world! This is a toast message.</span>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>
<script>
    $(document).ready(function(){
        $("h2").click(function(){
            $('.toast').toast('show');
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
