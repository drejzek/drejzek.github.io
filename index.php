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
            <h5 class="card-title text-center mb-3 fs-5">UrbexNation</h5>
            <div class="d-flex">
              <p class="mb-3 mx-auto">Tato aplikace je přístupná pouze registrovaným uživatelům</p>
            </div>
            <form method="post">
              <div class="mb-3 d-flex ">
                <button class="btn btn-primary btn-login text-uppercase fw-bold flex-fill me-1" type="submit" name="login">Přihlášení</button>
                <button class="btn btn-outline-primary btn-login text-uppercase fw-bold flex-fill ms-1" type="submit" name="signup">Registrace</button>
              </div>
              <hr class="my-4">
              <div class="d-grid mb-2 d-flex">
                  <span class="mx-auto">&copy; David Rejzek <?php echo date('Y')?> | Všechna práva vyhrazena.</span>
              </div>
            </form>
            <?php
            
              if(isset($_POST['login'])){
                header('location: auth/');
              }
              if(isset($_POST['signup'])){
                header('location: auth/signup.php');
              }
            
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>

