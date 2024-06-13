<?php

  session_start();
  include '../config.php';

  $ms = "";
  $ms1 = "";
  $ms2 = "";

  $species = "";
  $type = "";
  $status = "";

  $l = "";
  $loc = "";

  if(isset($_POST['posid-submit'])){
    $posid = $_POST['posid'];
    $coord = str_replace("N", "", explode("/" , $posid)[0]) . ", " . str_replace("E", "", explode("/" , $posid)[1]);
    $sql1 = "SELECT * FROM places WHERE coordinates = '$coord'";
    $r1 = mysqli_query($con, $sql1);
    $loc = mysqli_fetch_array($r1)['identifier'];
    header('location: ?id=' . $loc);
  }
  if(isset($_POST['name-submit'])){
    $names = $_POST['name'];
    $wasexplored = $_POST['was_explored'];
    $sspecies = $_POST['sspecies'];
    $sstatus = $_POST['sstatus'];
    $scoords = $_POST['scoords'];
    $sid = $_POST['sid'];
    $sql1 = "SELECT * FROM places WHERE name_decoded LIKE '%$names%' OR was_explored LIKE '%$wasexplored%' OR species LIKE '%$sspecies%' OR status LIKE '%$sstatus%' OR coordinates LIKE '%$scoords%' OR id LIKE '%$sid%'";
    $rname = mysqli_query($con, $sql1);
  }
  if(isset($_GET['all-submit']) || isset($_POST['all-submit'])){
    $sql1 = "SELECT * FROM places";
    $rall = mysqli_query($con, $sql1);
  }

  if(isset($_GET['id'])){
    $ms = "block";
    $ms1 = "none";

    $sql1 = "SELECT * FROM places WHERE identifier = '" . $_GET['id'] . "'";
    $r1 = mysqli_query($con, $sql1);
    $loc = mysqli_fetch_array($r1);

    $owner_id = $loc['owner_id'];
    $id = $loc['id'];

    $sql2 = "SELECT * FROM places_owner WHERE id = '$owner_id'";
    $r2 = mysqli_query($con, $sql2);
    $ow = mysqli_fetch_array($r2);

    $sql3 = "SELECT * FROM places_addresses WHERE place_id = '$id'";
    $r3 = mysqli_query($con, $sql3);
    $addr = mysqli_fetch_array($r3);
    // else{
    //     header('location: .');
    // }


    $posid = explode(", ", $loc['coordinates'])[0] . "N/" . explode(", ", $loc['coordinates'])[1] . "E";

    switch($loc['species']){
        case '0':
            
        break;
        case '1':
            $species = 'Obytné';
            $ico = 'fas fa-building';
        break;
        case '2':
            $species = 'Reprezentativvní';
            $ico = 'fas fa-university';
        break;       
        case '3':
            $species = 'Průmyslové';
            $ico = 'fas fa-industry';
        break;
        case '4':
          $species = 'Veřejné';
          $ico = 'fas fa-school';
        break;
        case '5':
            $species = 'Komerční'; 
            $ico = 'fas fa-hospital-alt';
        break;
        case '6':
            $species = 'Vojenské';   
            $ico = 'fas fa-shield-alt';
        break;       
        case '7':
            $species = 'Dopravní';   
            $ico = 'fas fa-car';
        break;
    }
    switch($loc['type']){
        case '0':

        break;
        case '1':
            $type = 'Budova';
        break;
        case '2':
            $type = 'Podzemní objekt';
        break;       
        case '3':
            $type = 'unel/kanalizace';
        break;
        case '4':
            $type = 'Ruiny';
        break;
        case '5':
            $type = 'Staveniště';
        break;
        case '6':
            $type = 'Doly';
        break;       
    }

    switch($loc['status']){
        case '0':
        break;
        case '1':
            $status = 'Prázdné';
        break;
        case '2':
            $status = 'Nikdy prázdné';
        break;       
        case '3':
            $status = 'V opravě';
        break;
        case '4':
            $status = 'Zaniklý';
        break;
        case '4':
          $status = 'K záchraně';
      break;
      case '4':
        $status = 'Zachráněné';
      break;
    }
  }
  else{
    $ms = "none";
    $ms1 = "block";
  }

  function Get_secName($domain, $tpid){  
    $ccon = mysqli_connect("localhost","root","",$domain);
    $sql1 = "SELECT * FROM places WHERE identifier='$tpid'";
    $result = mysqli_query($ccon,$sql1);
    $nav = mysqli_fetch_array($result);
    $web = $nav['a_sec'];
    $sql = "SELECT * FROM p_sections WHERE sec_id='$web'";
    $result1 = mysqli_query($ccon, $sql);
    if($result1->num_rows > 0){
        $article = mysqli_fetch_array($result1);
        return $article['sec_name'];
            }
    else{
        return '';
    }
  }

?>
<!doctype html>
<html lang="cs">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="assets\style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap-grid.min.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  </head>
  <body class="bg-body-tertiary">


    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8">
      <div class="container bg-white mx-auto rounded shadow-sm" style="display:<?php echo $ms?>; background:#fff"> 
    <a href=".">Zpět</a>
    <h1 class="pb-3 border-bottom">Karta lokace</h1>
    <hr class="mb-3">
    <div class="row">
        <div class="col-sm-7">
            <div class="table-responsive">
                <table class="table table-hover table-stripped border">
                    <thead>
                        <tr class="table-secondary">
                            <td colspan="2">Informace o lokaci</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Název lokace:</td>
                            <td><?php echo $loc['name']?></td>
                        </tr>
                        <tr>
                            <td>ID Lokace:</td>
                            <td><?php echo $loc['id']?></td>
                        </tr>
                        <tr>
                            <td>Poziční identifikátor (PosID):</td>
                            <td><?php echo $posid?></td>
                        </tr>
                        <tr>
                            <td>Mapová značka:</td>
                            <td><?php echo "<i class='" . $ico . " me-2'></i>" . $species?></td>
                        </tr>
                        <tr>
                            <td>Typ lokace:</td>
                            <td><?php echo $type?></td>
                        </tr>
                        <tr>
                            <td>Stav lokace:</td>
                            <td><?php echo $status?></td>
                        </tr>
                        <tr>
                            <td>Bylo prozkoumáno:</td>
                            <td><?php echo $loc['was_explored'] ? "Ano" : "Ne"?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-stripped border">
                  <thead>
                      <tr class="table-secondary">
                          <td colspan="2">Adresa lokace</td>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td>Ulice a ČP:</td>
                          <td><?php echo $addr['street'] . " " . $addr['street_num']?></td>
                      </tr>
                      <tr>
                          <td>Město:</td>
                          <td><?php echo $addr['city']?></td>
                      </tr>
                      <tr>
                          <td>PSČ:</td>
                          <td><?php echo $addr['zip_code']?></td>
                      </tr>
                      <tr>
                          <td>Stát:</td>
                          <td><?php echo $addr['country']?></td>
                      </tr>
                  </tbody>
              </table>
          </div>
        </div>
        <div class="border col-sm-5">
            <div class="header p-2 border-bottom table-secondary" style="padding:5px">
                Umístění lokace na mapě 
            </div>
            <div class="m-2">
                <div id="locmap" style="height:400px"></div>  
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-stripped border">
            <thead>
                <tr class="table-secondary">
                    <td>Osoby spjaté s lokací</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $ow['name_wi']?> (majitel)</td>
                </tr>
                <tr>
                    <td>Architekt: (neznámý nebo nevyplněný)</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table table-hover border">
            <thead>
                <tr>
                    <td colspan="2" class="table-secondary">Detail majitele</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Jméno:</td>
                    <td><?php echo $ow['name_wi']?></td>
                </tr>
                <tr>
                    <td>Ulice:</td>
                    <td><?php echo $ow['street']?></td>
                </tr>
                <tr>
                    <td>ČP:</td>
                    <td><?php echo $ow['street_num']?></td>
                </tr>
                <tr>
                    <td>Město:</td>
                    <td><?php echo $ow['city']?></td>
                </tr>
                <tr>
                    <td>PSČ:</td>
                    <td><?php echo $ow['zip_code']?></td>
                </tr>
                <tr>
                    <td>Země:</td>
                    <td><?php echo $ow['country']?></td>
                </tr>
                <tr>
                    <td>Telefon:</td>
                    <td><?php echo $ow['tel']?></td>
                </tr>
                <tr>
                    <td>E-mail:</td>
                    <td><?php echo $ow['mail']?></td>
                </tr>
                <tr>
                    <td>Web::</td>
                    <td><?php echo $ow['web']?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table table-hover border">
            <thead>
                <tr>
                    <td class="table-secondary">Detail architekta</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Není k dispozici</td>
                </tr>
            </tbody>
        </table>
    </div>
<div class="table-responsive">
        <table class="table table-hover border">
            <thead>
                <tr>
                    <td colspan="2" class="table-secondary">Ochrana nemovitosti</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Žádná</td>
                </tr>
            </tbody>
        </table>
    </div>
<div class="table-responsive">
        <table class="table table-hover border">
            <thead>
                <tr>
                    <td colspan="2" class="table-secondary">Náhled nemovitosti</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><img width="100%" src="<?php echo '../user/images/' . $loc['header_img_path'] . ''?>" alt=""></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
      </div>
      <div class="col-sm-2"></div>
    </div>
    

  <script>
      var locmap = L.map('locmap').setView([<?php echo $loc['coordinates']?>], 15);

      L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
      }).addTo(locmap);

      var marker = L.marker([<?php echo $loc['coordinates']?>]).addTo(locmap) .bindPopup('<?php echo $loc['name']?>, <?php echo $loc['coordinates']?>');
    </script>
  <script>
    var modal = $('#mOwner');
    function openModal() {
      modal.show();
      }
    function closeModal() {
      modal.hide();
    }
    window.onclick = function(event) {
      if (event.target == document.querySelector("#mOwner")) {
        modal.hide();
      }
    }
  </script>
  <script>
    function search() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("search");
      filter = input.value.toUpperCase();
      table = document.getElementById("results");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }       
      }
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <!-- 50.5570092N, 13.5902331E -->
  </body>
</html>