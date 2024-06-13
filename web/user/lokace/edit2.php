<?php
session_start();

$cs = true;

require '../../config.php';
require '../data/class.data.php';

$likes = "";
$dislikes = "";
$privacy = array('', '', '', '');

$ow_status = "";

$species_1 = '';
$species_2 = '';
$species_3 = '';
$species_4 = '';
$species_5 = '';
$species_6 = '';
$species_7 = '';

$status_1 = '';
$status_2 = '';
$status_3 = '';
$status_4 = '';
$status_5 = '';
$status_6 = '';

$modal = '    
        <script>
        $( document ).ready(function() {
            $("#success").modal("show");
        });
        </script>';


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loggedInUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$usernameParam = isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '';

if(isset($_GET['id'])){
    $ms = "block";
    $ms1 = "none";
    $id = $_GET['id'];

    if((new Data(0))->isRowExists($id, 'places')){
        $dt = new Data($id);
        $locdata = $dt->getGeneralData();
        if($locdata['owner_id'] != null){
            $ow = $dt->getOwnerData();
            $ow_status = true;
        }
        else{
            $ow_status = false;
        }
        $addr = $dt->getAddressData();
        $sec = $dt->getSecurityData();
        $links = $dt->getLinks();
        $timeline = $dt->getTimeline();

        $abletourbex = $locdata['able_to_urbex'] ? "checked" : "";
        $wasexplored = $locdata['was_explored'] ? "checked" : "";
        
        // else{
        //     header('location: .');
        // }

        if($locdata['coordinates'] != null)
            $posid = explode(", ", $locdata['coordinates'])[0] . "N/" . explode(", ", $locdata['coordinates'])[1] . "E";

        switch($locdata['species']){
            case '0':
                
            break;
            case '1':
                $species = 'Obytné';
                $ico = 'fas fa-building';
                $species_1 = 'selected';
            break;
            case '2':
                $species = 'Reprezentativvní';
                $ico = 'fas fa-university';
                $species_2 = 'selected';
            break;       
            case '3':
                $species = 'Průmyslové';
                $ico = 'fas fa-industry';
                $species_3 = 'selected';
            break;
            case '4':
                $species = 'Veřejné';
                $ico = 'fas fa-school';
                $species_4 = 'selected';
            break;
            case '5':
                $species = 'Komerční'; 
                $ico = 'fas fa-hospital-alt';
                $species_5 = 'selected';
            break;
            case '6':
                $species = 'Vojenské';   
                $ico = 'fas fa-shield-alt';
                $species_6 = 'selected';
            break;       
            case '7':
                $species = 'Dopravní';   
                $ico = 'fas fa-car';
                $species_7 = 'selected';
            break;
        }
        switch($locdata['type']){
            case '0':

            break;
            case '1':
                $type = 'Budova';
            break;
            case '2':
                $type = 'Podzemní objekt';
            break;       
            case '3':
                $type = 'tunel/kanalizace';
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

        switch($locdata['status']){
            case '0':
            break;
            case '1':
                $status = 'Prázdné';
                $status_1 = 'selected';
            break;
            case '2':
                $status = 'Používané';
                $status_2 = 'selected';
            break;       
            case '3':
                $status = 'V opravě';
                $status_3 = 'selected';
            break;
            case '4':
                $status = 'Zaniklý';
                $status_4 = 'selected';
            break;
            case '5':
                $status = 'K záchraně';
                $status_5 = 'selected';
            break;
            case '6':
                $status = 'Zachráněné';
                $status_6 = 'selected';
            break;
        }
        //print_r((new Data('1'))->getOwners(null));



        if(isset($_POST['submit-street'])){
            $id = $_POST['id'];
            $street = $_POST['street'];
            $streetnum = $_POST['streetnum'];
            $city = $_POST['city'];
            $country = $_POST['country'];
            $zip_code = $_POST['zip_code'];
            $dt = new Data($id);
            if($res = $dt->UpdateAddress($street, $streetnum, $city, $country, $zip_code)){
                echo 'ok';
            }
            else{
                echo 'not ok';
            }
        }
    }
    else $cs = 0;
}
else $cs = 0;




?>

<?php include '../../header.php'; ?>
<?php

if(isset($_GET['success'])){
    echo $modal;
}

?>
<div class="d-flex">
    <div class="alert alert-danger mx-auto"  style="display: <?php echo $cs ? "none" : "block"?>">
        <span><i class="fas fa-times-circle me-3"></i><strong>Chyba 404:</strong> Vámi požadovaná lokace nebyla nalezena.</span>
    </div>
</div>
<div class="container" style="display: <?php echo $cs ? "block" : "none"?>">
    <div class="card p-3 mb-3">
        <div class="d-flex">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=".">Urbex Nation</a></li>
                    <li class="breadcrumb-item"><a href=".">Lokace</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $locdata['shortcut'];?></li>
                </ol>
            </nav>
            <div class="ms-auto">
                <div class="btn-group">
                    <button class="btn" data-bs-toggle="modal" data-bs-target="#mViewHistory" ><i class="fas fa-history text-primary"></i></button>
                    <button class="btn"><i class="fas fa-share text-primary"></i></button>
                    <button data-bs-toggle="modal" data-bs-target="#mRowDel" class="btn"><i class="fas fa-trash text-danger"></i></button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ------------------------------------------ -->

    <div class="card">
        <div class="card-header d-flex">
            <b class="me-auto">Obecné</b>
            <button class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#cGeneral"><i class="fas fa-chevron-down"></i></button>
        </div>
        <div class="card-body collapse" id="cGeneral">
            <form id="generalForm"  enctype="multipart/form-data" action="../data/update/places-general-update.php" method="post">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label fw-bold">Název lokace</label>
                    <input type="text" class="form-control form-control-lg" id="exampleFormControlInput1" name="name" value="<?php echo $locdata['name'];?>" required>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Zkratka lokace</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" name="shortcut" value="<?php echo $locdata['shortcut'];?>" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Identifikátor lokace</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" name="identifier" value="<?php echo $locdata['identifier'];?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="o3" name="field[0]" <?php echo $abletourbex?>>
                    <label class="form-check-label" for="o3">Vhodné na urbex</label>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="o4" name="field[1]" <?php echo $wasexplored?>>
                    <label class="form-check-label" for="o4">Bylo prozkoumáno</label>
                </div>

                <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                <input type="button" value="Uložit" id="submit" class="btn btn-success mt-3" name="submit-general" onclick="submitGeneral()">
            </form>
        </div>
    </div>

    <!-- ------------------------------------------ -->



    <div class="card">
        <div class="card-header d-flex">
            <b class="me-auto">Mapová značka</b>
            <button class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#cMaps"><i class="fas fa-chevron-down"></i></button>
        </div>
        <div class="card-body" id="cMaps">
            <form action="" method="post" id="mapForm">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Souřadnice</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="exampleFormControlInput1" name="coordinates" value="<?php echo $locdata['coordinates'];?>" required>
                        <button type=button class="btn btn-outline-secondary"><i class="fas fa-map-marker-alt"></i></button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Mapová značka</label>
                    <select name="species" id="" class="form-select">
                        <option value="1" <?php echo $species_1?>>Obytné</option>
                        <option value="2" <?php echo $species_2?>>Reprezentativní</option>
                        <option value="3" <?php echo $species_3?>>Průmyslové</option>
                        <option value="4" <?php echo $species_4?>>Veřejné</option>
                        <option value="5" <?php echo $species_5?>>Komerční</option>
                        <option value="6" <?php echo $species_6?>>Vojenské</option>
                        <option value="7" <?php echo $species_7?>>Dopravní</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Stav lokace</label>
                    <select name="status" id="" class="form-select">
                        <option value="1" <?php echo $status_1?>>Prázdné</option>
                        <option value="2" <?php echo $status_2?>>Používáné</option>
                        <option value="3" <?php echo $status_3?>>Stavební úpravy</option>
                        <option value="4" <?php echo $status_4?>>Zaniklé</option>
                        <option value="5" <?php echo $status_5?>>K záchraně</option>
                        <option value="6" <?php echo $status_6?>>Zachráněnné</option>
                    </select>
                </div>

                <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                <input type="button" value="Uložit" id="submit" class="btn btn-success mt-3" name="submit-general" onclick="submitMap()">
            </form>
        </div>
    </div>


    <!-- ------------------------------------------ -->


    <div class="card">
        <div class="card-header d-flex">
            <b class="me-auto">Popis lokace</b>
            <button class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#cDesc"><i class="fas fa-chevron-down"></i></button>
        </div>
        <div class="card-body collapse" id="cDesc">
            <form id="descForm"  enctype="multipart/form-data" action="../data/update/places-descr-update.php" method="post">
                <div id="editor" class="bg-white" style="height:200px" onkeyup="document.querySelector('#pdescOut').value = document.querySelector('.ql-editor').innerHTML">
                    <?php echo $locdata['descr']?>
                </div>
                <input type="hidden" name="pdescOut" id="pdescOut">
                <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                <input type="button" value="Uložit" id="submit" class="btn btn-success mt-3" name="submit" onclick="submitDesc()">
            </form>
        </div>
    </div>



    <!-- ------------------------------------------ -->




    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header d-flex">
                    <b class="me-auto">Adresa lokace</b>
                    <button class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#cAddress"><i class="fas fa-chevron-down"></i></button>
                </div>
                <div class="card-body collapse" id="cAddress">
                    <form id="addressForm"  enctype="multipart/form-data" action="../data/update/places-address-update.php" method="post">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Ulice</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" name="street" value="<?php echo $addr['street'];?>" required>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Č. P.</label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1" name="streetnum" value="<?php echo $addr['street_num'];?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Město</label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1" name="city" value="<?php echo $addr['city'];?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">PSČ</label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1" name="zip_code" value="<?php echo $addr['zip_code'];?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Stát</label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1" name="country" value="<?php echo $addr['country'];?>" required>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                        <input type="button" value="Uložit" id="submit" class="btn btn-success mt-3" name="submit" onclick="submitAddress()">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header d-flex">
                    <b class="me-auto">Majitel</b>
                    <button class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#cOwner"><i class="fas fa-chevron-down"></i></button>
                </div>
                <div class="card-body collapse" id="cOwner">
                    <form id="uploadForm"  enctype="multipart/form-data" action="update.php" method="post">
                    <h4 class="mb-3"></h4>
                    <div class="container bg-white border p-3" style="display:<?php echo $ow_status ? "block" : "none" ?>">
                        <div class="d-flex">
                            <div class="me-auto">
                                    <span><?php echo $ow['name']?></span>
                                    <br>
                                    <small class="text-secondary">
                                        IČO: <?php echo $ow['ico']?>
                                    </small>
                            </div>
                            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#mOwnerChange"><i class="fas fa-pencil"></i></button>
                            <button type="button" class="btn" data-bs-toggle="collapse" data-bs-target="#cOwnerInfo"><i class="fas fa-chevron-down"></i></button>
                        </div>
                        <div class="collapse border-top mt-3 pt-3" id="cOwnerInfo">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <td>Jméno:</td>
                                        <td><?php echo $ow['name']?></td>
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
                    </div>
                    <div class="container bg-white border p-3" style="display:<?php echo $ow_status ? "none" : "block" ?>">
                        <div class="d-flex">
                            <p class="me-auto"><i class="fas fa-exclamation-triangle text-danger me-3"></i>Přidejte majitele</p>
                            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#mOwnerAdd"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                    <input type="submit" value="Uložit" id="submit" class="btn btn-success mt-3" name="submit">
                </form>
            </div>
            </div>
        </div>
    </div>

                


    <!-- ------------------------------------------ -->
    
    
    
    <div class="card">
        <div class="card-header d-flex">
            <b class="me-auto">Bezpečnost</b>
            <button class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#cSec"><i class="fas fa-chevron-down"></i></button>
        </div>
        <div class="card-body collapse" id="cSec">
            <form id="securityForm"  enctype="multipart/form-data" action="../data/update/places-security-update.php" method="post">
                <div class="mb-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="1" name="sfield[0]" <?php echo $sec['security_sys'] == 1 ? "checked" : "" ?>>
                                <label class="form-check-label" for="1">Výskyt zabezpečovacích systémů</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="2" name="sfield[1]" <?php echo $sec['cctv'] == 1 ? "checked" : "" ?>>
                                <label class="form-check-label" for="2">Výskyt kamerových systémů</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="3" name="sfield[2]" <?php echo $sec['security'] == 1 ? "checked" : "" ?>>
                                <label class="form-check-label" for="3">Výskyt bezpečnostních služeb</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="4" name="sfield[3]" <?php echo $sec['dogs'] == 1 ? "checked" : "" ?>>
                                <label class="form-check-label" for="4">Výskyt psů</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="5" name="sfield[4]" <?php echo $sec['homelessOrDrugsMen'] == 1 ? "checked" : "" ?>>
                                <label class="form-check-label" for="5">Výskyt osob bez domova/pod vlivem návykových látek</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="6" name="sfield[5]" <?php echo $sec['paranormal'] == 1 ? "checked" : "" ?>>
                                <label class="form-check-label" for="6">Výskyt paranormálních jevů</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row mb-3 pb-3 border-bottom">
                                <label for="inputEmail3" class="col-sm-4 col-form-label fw-bold">Celkový status</label>
                                <div class="col-sm-8">
                                    <input type="number" min=1 max=10 class="form-control" id="inputEmail3" name="overall_status" value="<?php echo $sec['overall_status']?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Statika lokace</label>
                                <div class="col-sm-8">
                                    <input type="number" min=1 max=10 class="form-control" id="inputEmail3" name="statistics" value="<?php echo $sec['statics']?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Pozorní sousedé</label>
                                <div class="col-sm-8">
                                    <input type="number" min=1 max=10 class="form-control" id="inputEmail3" name="neighbours" value="<?php echo $sec['neighbours']?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Riziko chycení</label>
                                <div class="col-sm-8">
                                    <input type="number" min=1 max=10 class="form-control" id="inputEmail3" name="catchnum" value="<?php echo $sec['catchnum']?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-4 col-form-label">Obtížnost přístupnosti</label>
                                <div class="col-sm-8">
                                    <input type="number" min=1 max=10 class="form-control" id="inputEmail3" name="entrance_difficulty" value="<?php echo $sec['entramce_difficulty']?>">
                                </div>
                            </div>
                            <div class="form-text">
                                Výše uvedené parametry zhodnocují stav dané lokace počtem bodů.
                                Tzn. čím větší číslo je tím je daný parametr lepší.
                                Např. Statika lokace: 1 - špatná, 10 - výborná; Riziko chycení: 1 - Vysoké, 10 - žádné.
                                <br>
                                <br>
                                Celkvý status zhodnocuje celkový stav budovy. Například jestli je v buvodě původní vybavení, stav okem, apod. Nesouvsí s výše uvedenými parametry
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                <input type="button" value="Uložit" id="submit" class="btn btn-success mt-3" name="submit" onclick="submitSecurity()">
            </form>
        </div>
    </div>
    
    
    <!-- ------------------------------------------ -->
    
    
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header d-flex">
                    <b class="me-auto">Náhledový obrázek</b>
                    <button class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#cThImg"><i class="fas fa-chevron-down"></i></button>
                </div>
                <div class="card-body collapse" id="cThImg">                
                    <?php
                        
                        if($locdata['header_img_path'] != null)
                        {
                            echo '<img class="img-thumbnail mb-3" src="../images/' . $locdata['header_img_path'] . '">';
                            echo '
                            <form id="imgControlForm">
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#mSelectImg">Upravit</button>
                                <button type="button" class="btn btn-outline-danger" onclick="delImg()"><i class="fas fa-trash"></i></button>
                                <input name="id" type="hidden" value="' . $locdata['id'] . '">
                            </form>
                            ';
                        }
                        else
                        {
                            echo '<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mSelectImg">Přidat  obrázek</button>';
                        }
                    
                    ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header d-flex">
                    <b class="me-auto">Časová osa</b>
                    <button class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#cTimeLine"><i class="fas fa-chevron-down"></i></button>
                </div>
                <div class="card-body collapse" id="cTimeLine">                
                    <form id="addressForm"  enctype="multipart/form-data" action="../data/update/places-address-update.php" method="post">
                        <div class="d-flex mb-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mAddTimeline"><i class="fas fa-plus"></i> Přidat</button>
                        </div>
                        <div class="list-group" id="timeline">
                            <?php
                                    while($tl = mysqli_fetch_array($timeline)){
                                        switch($tl['title']){
                                            case '0':
                                            break;
                                            case '1':
                                                $status = 'Prázdné';
                                                $status_1 = 'selected';
                                            break;
                                            case '2':
                                                $status = 'Používané';
                                                $status_2 = 'selected';
                                            break;       
                                            case '3':
                                                $status = 'V opravě';
                                                $status_3 = 'selected';
                                            break;
                                            case '4':
                                                $status = 'Zaniklý';
                                                $status_4 = 'selected';
                                            break;
                                            case '5':
                                                $status = 'K záchraně';
                                                $status_5 = 'selected';
                                        break;
                                        case '6':
                                            $status = 'Zachráněné';
                                            $status_6 = 'selected';
                                        break;
                                        case '7':
                                            $status = 'Vznik';
                                            $status_6 = 'selected';
                                        break;
                                        }
                                        echo '
                                        <li class="list-group-item d-flex" id="tl' . $tl['id'] . '">
                                            <div class="me-auto">
                                                <span class="fw-bold">' . (isset(explode("-", $tl['date'])[1]) ? explode("-", $tl['date'])[1] . '/' : '') .( isset(explode("-", $tl['date'])[0]) ? explode("-", $tl['date'])[0] : '') . ' &gt; ' . $status . '</span>
                                                <br>
                                                <span>
                                                    ' . $tl['descr'] . '
                                                </span>
                                            </div>
                                            <button type="button" class="btn text-danger" data-bs-toggle="modal" data-bs-target="#mTimelineDelAsk" data-bs-whatever="' . $tl['id'] . '"><i class="fas fa-trash"></i></button>
                                        </li>
                                        ';
                                    }
                                
                                ?>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                    </form>
                </div>
            </div>    
        </div>
    </div>         



            
            <!-- ------------------------------------------ -->



            <div class="card">
                <div class="card-header d-flex">
                    <b class="me-auto">Odkazy</b>
                    <button class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#cLinks"><i class="fas fa-chevron-down"></i></button>
                </div>
                <div class="card-body collapse" id="cLinks">                
                    <div class="row">
                        <form id="uploadForm"  enctype="multipart/form-data" action="update.php" method="post">
                        <div class="d-flex mb-3">
                            <button class="btn btn-outline-primary" type="button" onclick="$('#mAddLink').modal('show')"><i class="fas fa-plus"></i> Přidat odkaz</button>
                        </div>
                        <?php 
                            
                                if($links->num_rows == 0){
                                    $ts = "none";
                                    $tsa = "block";
                                }
                                else{
                                    $ts = "block";
                                    $tsa = "none";
                                }
                            
                        ?>
                        <div class="alert alert-info" style="display:<?php echo $tsa?>">
                            <span>
                                <i class="fas fa-info-circle"></i> Nebyly nalezeny žádné odkazy.
                            </span>
                        </div>
                        <div class="container bg-white border px-3 pb-3 pt-0" style="display:<?php echo $ts?>">
                        <div class="table-responsive">
                            <table class="table table-hover" id="linksTable">
                                <thead>
                                    <tr>
                                        <th>Titulek</th>
                                        <th>Typ</th>
                                        <th>URL</th>
                                        <th>Popis</th>
                                        <th>Akce</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $lnk_type = null;
                                        while($lnk = mysqli_fetch_array($links)){
                                            switch ($lnk['type']){
                                                case 1:
                                                    $lnk_type = "Článek";
                                                    break;
                                                case 2:
                                                    $lnk_type = "Galerie";
                                                    break;
                                                case 3:
                                                    $lnk_type = "Video";
                                                    break;
                                                case 4:
                                                    $lnk_type = "Katastr nemovitostí (KN)";
                                                    break;
                                                    case 5:
                                                        $lnk_type = "Registr";
                                                        break;
                                                case 6:
                                                    $lnk_type = "Ostatní";
                                                    break;
                                            }
                                            echo '
                                            
                                            <tr id="row' . $lnk['id'] . '">
                                            <td>' . $lnk['title'] . '</td>
                                            <td>' . $lnk_type . '</td>
                                            <td style="max-width: 50%">' . $lnk['url'] . '</td>
                                            <td>' . $lnk['descr'] . '</td>
                                            <td class="d-flex">
                                            <span class="mx-auto">
                                            <a href="' . $lnk['url'] . '" class="btn text-primary" target="_blank"><i class="fas fa-external-link"></i></a>
                                            <button type="button" class="btn btn-sm text-primary" onclick="cloneLink(' . $lnk['id'] . ')"><i class="far fa-clone"></i></button>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#mLinkDelAsk" data-bs-whatever="' . $lnk['id'] . '" class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>
                                            <span>
                                            </td>
                                            </tr>
                                            
                                            ';
                                        }
                                        
                                        ?>
                                </tbody>
                            </table>
                        </div>

                        </div>
                    </form>
                </div>
            </div>               



            <!-- ------------------------------------------ -->
            


    <!-- <div class="card">
        <div class="card-header d-flex">
            <b class="me-auto">     </b>
            <button class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#  "><i class="fas fa-chevron-down"></i></button>
        </div>
        <div class="card-body collapse" id="    ">                
            
        </div>
    </div> -->
    
    
    <!-- ------------------------------------------ -->


    <div class="modal" id="mSelectImg">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Vyberte úvodní obrázek</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body h-50">
                <div class="d-flex">
                    <form id="imgForm" action="../data/update/places-img-update.php" method="post" class="col-thumbnail">
                        <?php
                            echo '<div class="row mx-auto">';
                            if ($handle = opendir('../images/')) { 
                                $i = 0;
                                while (false !== ($entry = readdir($handle))) {
                                    if($entry == "Thumbs.db")
                                        continue;

                                    if ($entry != "." && $entry != "..") {
                                     /*    echo '
                                            <div class="col-sm-2">
                                                <button type="button" class="btn" id="option1" autocomplete="off" onclick="document.querySelector(\'#imgSrc\').value=\'' . $entry . '\'"><img class="img-thumbnail" src="../images/' . $entry . '"></button>
                                            </div>
                                        '; */
                                        echo '
                                            <div class="col-sm-3">
                                                <input type="radio" class="btn-check" name="options-base" id="option' . $i . '" autocomplete="off" onclick="document.querySelector(\'#imgSrc\').value=\'' . $entry . '\'">
                                                <label class="btn" for="option' . $i . '"><img class="img-thumbnail" width="200" src="../images/' . $entry . '"></label>
                                            </div>
                                        ';
                                    }
                                    $i++;
                                }
                                closedir($handle);
                            }
                        ?>
                        </div>
                        <div class="modal-footer">
                            <input name="src" type="hidden" id="imgSrc">
                            <input name="id" type="hidden" value="<?php echo $locdata['id'] ?>">
                            <input class="btn btn-success" name="button" value="Uložit" type="button" onclick="submitImg()">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="mOwnerChange">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex">
                        <h4 class="mb-4">Změnit majitele</h4>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                    </div>
                    <label for="search" class="form-label">Změnit majitele:</label>
                        <input type="search" name="owSearch" id="search" placeholder="Zadejte název nebo IČO" class="form-control">
                        <div class="list-group mb-3" id="results"></div>
                </div>
                <div class="modal-footer d-flex">
                    <small class="text-secondary">Pokud majitel v seznamu ještě není můžete ho přidat <a href="" class="btn-link">zde.</a></small>
                    <button class="btn btn-danger ms-auto" data-bs-dismiss="modal">Zavřít</i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mOwnerAdd">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex">
                        <h4 class="mb-4">Přidat majitele</h4>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                    </div>
                    <label for="search" class="form-label">Přidat majitele:</label>
                        <input type="search" name="owSearch" id="searchAdd" placeholder="Zadejte název nebo IČO" class="form-control">
                        <div class="list-group mb-3" id="resultsAdd"></div>
                </div>
                <div class="modal-footer d-flex">
                    <small class="text-secondary">Pokud majitel v seznamu ještě není můžete ho přidat <a href="" class="btn-link">zde.</a></small>
                    <button class="btn btn-danger ms-auto" data-bs-dismiss="modal">Zavřít</i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="mRowDel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="../data/delete/places-delete.php" id="frmDelRow" method="post">
                    <div class="modal-body px-3">
                        <div class="d-flex">
                            <h4 class="mb-4">Smazat lokaci</h4>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                        </div>
                        <span>Opravdu chete smazat tuto lokaci?</span>
                        <div class="container border rounded mt-3">
                            <span class="fw-bold">Dojde ke smazání těchto položek:</span>
                            <ul>
                                <li>Lokace</li>
                                <li>Odkazy</li>
                                <li>Časová osa</li>
                            </ul>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                        <input type="hidden" name="linkId" id="LinkID" value="">
                    </div>
                    <div class="modal-footer d-flex mt-3">
                        <span class="text-secondary">Tuto akci nelze odvolat!</span>
                        <input type="button" value="Ano" id="submit" class="ms-auto me-3 btn btn-danger" name="submit" onclick="submitDelRow()">
                        <input type="submit" value="Ano 2" id="submit" class="ms-auto me-3 btn btn-danger" name="submit">
                        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Ne</i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<div class="modal" id="mOwner">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        Najít majitele
                        <input type="search" name="owner" id="searsch" class="form-control" placeholder="Zadejte jméno nebo IČO">
                        <div class="list-group" id="results">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
<div class="modal" id="mSelectImg">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-body h-50">
                <div class="container-fluid border-bottom mb-3 d-flex">
                    <h4 class="lead mb-3 me-auto">Vyberte úvodní obrázek</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="d-flex">
                    <form id="imgForm" action="../data/update/places-img-update.php" method="post" class="col-thumbnail">
                        <?php
                            echo '<div class="row mx-auto">';
                            if ($handle = opendir('../images/')) { 
                                $i = 0;
                                while (false !== ($entry = readdir($handle))) {
                                    if($entry == "Thumbs.db")
                                        continue;

                                    if ($entry != "." && $entry != "..") {
                                        echo '
                                            <div class="col-sm-2">
                                                <button type="button" class="btn" id="option1" autocomplete="off" onclick="document.querySelector(\'#imgSrc\').value=\'' . $entry . '\'"><img class="img-thumbnail" src="../images/' . $entry . '"></button>
                                            </div>
                                        ';
                                    }
                                    $i++;
                                }
                                closedir($handle);
                            }
                            echo '
                                <input name="src" type="hidden" id="imgSrc">
                                <input name="id" type="hidden" value="' . $locdata['id'] . '">
                                <input name="button" value="Uložit" type="button" onclick="submitImg()">
                                ';
                        ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

<!--     <div class="player-buttons">
        <form method="post" action="">
            <input type="hidden" name="action" value="like">
            <button type="button" class="btn" id="green" onclick="updateVote('like', <?php echo $fileRow['id']; ?>)">
                <i class="fa fa-thumbs-up fa-lg" aria-hidden="true"></i> 
                <span id="likesCount"><?php echo $likes; ?></span>
            </button>
        </form>
        <form method="post" action="">
            <input type="hidden" name="action" value="dislike">
            <button type="button" class="btn" id="red" onclick="updateVote('dislike', <?php echo $fileRow['id']; ?>)">
                <i class="fa fa-thumbs-down fa-lg" aria-hidden="true"></i> 
                <span id="dislikesCount"><?php echo $dislikes; ?></span>
            </button>
        </form>
    </div>
 -->
 <div class="modal fade" id="mAddLink">
    <div class="modal-dialog modal-dialog-centered modal-dialog-md">
        <div class="modal-content">
            <form action="../data/insert/places-links-insert.php" id="linksForm" method=post>
                <div class="modal-body px-3">
                    <div class="d-flex">
                        <h4 class="mb-4">Přidat odkaz</h4>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Titulek</label>
                        <input type="text" name="title" id="title" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Typ odkazu</label>
                        <select name="type" id="type" class="form-select">
                            <option value="0"></option>
                            <option value="1">Článek</option>
                            <option value="2">Galerie</option>
                            <option value="3">Video</option>
                            <option value="4">Katastr nemovitostí (KN)</option>
                            <option value="5">Registr</option>
                            <option value="6">Ostatní</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">URL</label>
                        <input type="text" name="url" id="url" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Popis</label>
                        <textarea name="desc" id="desc" cols="30" rows="3" class="form-control"></textarea>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                    <div class="d-flex mt-3">
                                <input type="reset" value="Vymazat" class="btn">
                        <input type="button" value="Uložit" id="submit" class="ms-auto me-3 btn btn-success" name="submit" onclick="submitLinks()">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Zavřít</i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
 <div class="modal fade" id="mAddTimeline">
    <div class="modal-dialog modal-dialog-centered modal-dialog-md">
        <div class="modal-content">
            <form action="../data/insert/places-timeline-insert.php" id="tlForm" method=post>
                <div class="modal-body px-3">
                    <div class="d-flex">
                        <h4 class="mb-4">Přidat položku časové osy</h4>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Stav</label>
                        <select name="title" id="" class="form-select">
                            <option value=""></option>
                            <option value="1">Prázdné</option>
                            <option value="2">Používáné</option>
                            <option value="3">Stavební úpravy</option>
                            <option value="4">Zaniklé</option>
                            <option value="5">K záchraně</option>
                            <option value="6">Zachráněnné</option>
                            <option value="7">Vznik</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Datum</label>
                        <input type="month" name="date" id="date" class="form-control" max="<?php echo date('m-d-Y')?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Popis</label>
                        <textarea name="desc" id="desc" cols="30" rows="3" class="form-control"></textarea>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                    <div class="d-flex mt-3">
                        <input type="reset" value="Vymazat" class="btn">
                        <input type="button" value="Uložit" id="submit" class="ms-auto me-3 btn btn-success" name="submit" onclick="submitTimeline()">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Zavřít</i></button>
                    </div>
                </div>
            </form>
            <script>
                   
                </script>
                <script>
                
            </script>
        </div>
    </div>
</div>
 <div class="modal fade" id="mLinkDelAsk">
    <div class="modal-dialog modal-dialog-centered modal-dialog-md">
        <div class="modal-content">
            <form action="../data/delete/places-links-delete.php" id="dellinksForm" method=post>
                <div class="modal-body px-3">
                    <div class="d-flex">
                        <h4 class="mb-4">Smazat odkaz</h4>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                    </div>
                    <span>Opravdu chete smazat tento odkaz?</span>
                    <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                    <input type="hidden" name="linkId" id="LinkID" value="">
                    <div class="d-flex mt-3">
                        <input type="button" value="Ano" id="submit" class="ms-auto me-3 btn btn-danger" name="submit" onclick="submitDelLinks()">
                        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Ne</i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
 <div class="modal fade" id="mTimelineDelAsk">
    <div class="modal-dialog modal-dialog-centered modal-dialog-md">
        <div class="modal-content">
            <form action="../data/insert/places-timeline-delete.php" id="deltimelineForm" method=post>
                <div class="modal-body px-3">
                    <div class="d-flex">
                        <h4 class="mb-4">Smazat položku časové osy</h4>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                    </div>
                    <span>Opravdu chete smazat tuto položku?</span>
                    <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                    <input type="hidden" name="TlID" id="TlID" value="">
                    <div class="d-flex mt-3">
                        <input type="button" value="Ano" id="submit" class="ms-auto me-3 btn btn-danger" name="submit" onclick="submitDelTimeline()">
                        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Ne</i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
 <div class="modal fade" id="success">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="mx-auto text-center">
                        <i class="far fa-check-circle text-success mb-4" style="font-size:75px"></i>
                        <br>
                        <h4>Data byla úpěšně změněna!</h4>
                        <br>
                        <button class="btn btn-success" data-bs-dismiss="modal">Zavřít</i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <div class="modal fade" id="mViewHistory">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Historie změn</h3>
                <button class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover">
                    <tr>
                        <th>
                            Objekt
                        </th>
                        <th>
                            Typ změny
                        </th>
                        <th>
                            Datum a čas
                        </th>
                        <th>
                            Uživatel
                        </th>
                        <th>
                            Akce
                        </th>
                    </tr>

                    <?php
                    
                        $sql = "SELECT * FROM changes_history WHERE type = 'places' AND subject_id = '$id' ORDER BY timestamp DESC";

                        $result = mysqli_query($con, $sql);

                        $hname = "";
                        $hchange_type = "";

                        while($i = mysqli_fetch_array($result)){

                            $inner_sql = "SELECT * FROM users WHERE id = '" . $i['user_id'] . "'";
                            $username = mysqli_fetch_array(mysqli_query($conn, $inner_sql))['username'];

                            switch($i['name']){
                                case 'general':
                                    $hname = "Obecné";
                                break;
                                case 'address':
                                    $hname = 'Adresa';
                                break;
                                case 'descr':
                                    $hname = 'Popis';
                                break;
                                case 'map':
                                    $hname = 'Mapová značka';
                                break;
                                case 'owner_id':
                                    $hname = 'Majitel objektu';
                                break;
                            }

                            switch($i['change_type']){
                                case 0:
                                    $hchange_type = "Položka změněna";
                                break;
                                case 1:
                                    $hchange_type = "Položka přidána";
                                break;
                                case 2:
                                    $hchange_type = "Položka smazána";
                                break;
                            }

                            echo '
                            
                            <tr>
                                <td>' . $hname . '</td>
                                <td>' . $hchange_type . '</td>
                                <td>' . $i["timestamp"] . '</td>
                                <td>' . $username . '</td>
                                <td><button class="btn btn-link btn-sm" data-bs-toggle="modal" data-bs-target="#mHistoryDetail' . $i['id'] . '" >Detail</button></td>
                            </tr>
                            
                            ';
                        }
                    
                    ?>

                </table>
            </div>
        </div>
    </div>
</div>

<?php
    
    $sql = "SELECT * FROM changes_history WHERE type = 'places' AND subject_id = '$id'";

    $result = mysqli_query($con, $sql);

    while($i = mysqli_fetch_array($result)){

        $old = explode(";", $i['old_value']);
        $new = explode(";", $i['new_value']);

        echo '
        
            <div class="modal" id="mHistoryDetail' . $i['id'] . '">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="d-flex">
                                <h3>Historie změn</h3>
                                <button class="btn-close ms-auto" data-bs-toggle="modal" data-bs-target="#mViewHistory"></button>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <h6>Původní hodnoty</h6>
                                    <ul class="list-group">
                                    ';
                                        foreach($old as $o){
                                            echo '<li class="list-group-item">' . $o . '</li>';
                                        }
                                echo '
                                </ul>
                                </div>
                                <div class="col-sm-6">
                                    <h6>Nové hodnoty</h6>
                                    <ul class="list-group">
                                    ';
                                        foreach($new as $o){
                                            echo '<li class="list-group-item">' . $o . '</li>';
                                        }
                                echo '
                                </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            ';
    }

?>




 <div class="modal fade" id="unsuccess">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="mx-auto text-center">
                        <i class="far fa-times-circle text-danger mb-4" style="font-size:75px"></i>
                        <br>
                        <h4>Nastala chyba při vykonávání požadavku!</h4>
                        <br>
                        <button class="btn btn-success" data-bs-dismiss="modal">Zavřít</i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <div class="modal fade" id="mOWnerChangeAsk">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <h4 class="mb-4">Změna majitele</h4>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <span>Původní majitel</span>
                <div class="container bg-white border p-3">
                    <div class="d-flex">
                        <div class="me-auto">
                            <span><?php echo $ow['name_wi']?></span>
                            <br>
                            <small class="text-secondary">
                                IČO: <?php echo $ow['ico']?>
                            </small>
                        </div>
                    </div>
                </div>
                <br>
                <span>Nový majitel</span>
                <div class="container bg-white border p-3">
                    <div class="d-flex">
                        <div class="me-auto">
                            <span id="newOwnerName"></span>
                            <br>
                            <small id="newOwnerIco" class="text-secondary">
                                
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex">
                    <form action="" id='ownerForm'>
                        <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']?>">
                        <input type="hidden" name="ownerID" id="ownerID" value="">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="submitForm()">Ano</i></button>
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="submitForm()">Ne</i></button>
                    </form>
                </div>
                <button class="btn btn-danger" data-bs-dismiss="modal">Ne</i></button>
            </div>
        </div>
    </div>
</div>
 <div class="modal fade" id="mOWnerAddAsk">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <h4 class="mb-4">Přidat majitele</h4>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <span>Nový majitel</span>
                <div class="container bg-white border p-3">
                    <div class="d-flex">
                        <div class="me-auto">
                            <span id="newOwnerName1"></span>
                            <br>
                            <small id="newOwnerIco1" class="text-secondary">
                                
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex">
                    <form action="../data/update/owner-single-update.php" id='ownerForm1' method="post">
                        <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']?>">
                        <input type="hidden" name="ownerID" id="ownerID" value="">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="submitForm()">Ano</i></button>
                        <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Ano 2</i></button>
                    </form>
                </div>
                <button class="btn btn-danger" data-bs-dismiss="modal">Ne</i></button>
            </div>
        </div>
    </div>
</div>
</div>


<div class="control-panel position-absolute position-fixed bottom-0 start-50 translate-middle shadow">
    <div class="btn-group border">
        <a href="?id=<?php echo $_GET['id'] - 1 ?>" class="btn btn-light"><i class="fas fa-chevron-left"></i></a>
        <span class="px-3 bg-light"><?php echo $locdata['name'];?></span>
        <a href="?id=<?php echo $_GET['id'] + 1 ?>" class="btn btn-light"><i class="fas fa-chevron-right"></i></a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="../assets/script.js"></script>
</body>
</html>
