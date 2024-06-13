<?php
session_start();

// Připojení k databázi
include '../web/config.php';
// Zpracování formuláře přihlášení
if (isset($_POST["submit"])) {
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  // Získání uloženého hesla pro zadané uživatelské jméno
  $query = "SELECT id, password FROM users WHERE username = '$username'";
  $result = $conn->query($query);

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $hashed_password = $row["password"];

    // Ověření hesla
    if (password_verify($password, $hashed_password)) {
      $_SESSION["user_id"] = $row["id"];
      header("Location: ../web/"); // Přesměrování na hlavní stránku po přihlášení
      exit();
    } 
    else {
      header("Location: .?passErr");
    }
  } 
  else {
      header("Location: .?userErr");
  }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
      body {
  /* background: #007bff;
  background: linear-gradient(to right, #0062E6, #33AEFF); */
  background: rgb(235,234,255);
  background: linear-gradient(90deg, rgba(235,234,255,1) 0%, rgba(255,255,255,1) 35%, rgba(255,252,219,1) 100%);
}

.btn-login {
  font-size: 0.9rem;
  letter-spacing: 0.05rem;
  padding: 0.75rem 1rem;
}

.btn-google {
  color: white !important;
  background-color: #ea4335;
}

.btn-facebook {
  color: white !important;
  background-color: #3b5998;
}
.form-control{
  border-radius: 0px;
}
.btn{
  border-radius: 0px;
}
.form-control:focus{
  border: 2px solid #0164E7;
  box-shadow: none;
  transition: 0.1s
}
    </style>
  </head>
  <body>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card border-0 shadow rounded-3 my-5">
          <div class="card-body p-4 p-sm-5">
            <h5 class="card-title text-center mb-5 fs-5">UrbexNation - Přihlášení</h5>
            <form method="post">
              <div class="form-floating mb-3">
                <input type="text" class="form-control <?php echo isset($_GET['userErr']) ? 'is-invalid' : '';?>" id="floatingInput" placeholder="name@example.com" name="username">
                <label for="floatingInput">Uživateské jméno</label>
                <div class="invalid-feedback">
                  Uživatel nebyl nalezen.
                </div>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control <?php echo isset($_GET['passErr']) ? 'is-invalid' : '';?>" id="floatingPassword" placeholder="Password" name="password">
                <label for="floatingPassword">Heslo</label>
                <div class="invalid-feedback">
                  Heslo není správné.
                </div>
              </div>

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="rememberPasswordCheck">
                <label class="form-check-label" for="rememberPasswordCheck">
                  Zapamatovat si mě
                </label>
              </div>
              <div class="d-grid">
                <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit" name="submit">Přihlásit</button>
              </div>
              <hr class="my-4">
              <div class="d-grid mb-2">
                <a class="btn btn-link" href="signup.php">
                  <i class="fab fa-google me-2"></i> Ještě nemáte účet? Zaregistrujte se
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>

