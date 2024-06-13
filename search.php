<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
      body {
        background: #007bff;
        background: linear-gradient(to right, #0062E6, #33AEFF);
        }

        .btn-login {
        font-size: 0.9rem;
        letter-spacing: 0.05rem;
        padding: 0.75rem 1rem;
        }

        .btn-outline-secondary {
        color: white !important;
        background-color: #ea4335;
        }
    </style>
  </head>
  <body>    
  <div class="px-4 py-5 my-5 mx-5 text-center bg-white">
    <h5 class="card-title text-center mb-5 fw-light fs-5">Hledání souborů podle přístupového klíče</h5>
    <div class="col-lg-6 mx-auto">
        <form method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control <?php echo isset($_GET['userErr']) ? 'is-invalid' : '';?>" id="floatingInput" placeholder="name@example.com" name="key">
                <label for="floatingInput">Přístupový klíč</label>
                <div class="invalid-feedback">
                    Soubor nebyl nalezen.
                </div>
            </div>
            <div class="d-grid">
                <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit" name="submit">Hledat</button>
              </div>
        </form>
        <?php
        if(isset($_POST['submit'])){
          include 'web/config.php';
          $sql = "SELECT * FROM file_id WHERE access_key = '" . $_POST['key'] . "'";
          $result = mysqli_query($conn, $sql);
          if ($result !== null && $result->num_rows > 0) {
            echo '
            <div class="table-responsive">
            <table class="table table-bordered table-hover mt-4">
            <thead>
                <tr>
                    <td>
                        ID
                    </td>
                    <td>
                        Náhled
                    </td>
                    <td>
                        Název
                    </td>
                    <td>
                        Nahráno
                    </td>
                    <td>
                        Akce
                    </td>
                </tr>
            </thead>
            <tbody>
            ';
              while($row = $result->fetch_assoc()) {
                  echo '
                  <tr>
                      <td>
                      ' . $row['id'] . '
                      </td>
                      <td style="width:20%">
                      <img class="w-25" src="web/' . $row['image_path'] . '">
                      </td>
                      <td>
                      ' . $row['title'] . '
                      </td>
                      <td>
                      ' . $row['created_at'] . '
                      </td>
                      <td style="width:20%">
                        <a class="btn btn-outline-primary" type="submit" name="viewFile" href="file.php?key=' . $row['access_key'] . '">Detail</a>
                      </td>
                  </tr>
                  
                  ';
              }
              echo '        </body>
              </table></div>';
          } else {
              echo '        <div class="alert alert-danger mt-5">
              <i class="fas fa-times-circle me-3"></i><span>Nebyly nalezeny žádné soubory</span>
          </div>';
          }
          }
        
        ?>

    </div>
  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>