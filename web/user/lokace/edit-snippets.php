




            




            <div class="container bg-body-tertiary border p-3 mb-3">
                
            </div>



            




            <div class="container bg-body-tertiary border p-3 mb-3">
                <form id="addressForm"  enctype="multipart/form-data" action="../data/update/places-address-update.php" method="post">
                    <div class="d-flex mb-3">
                        <h4 class="me-auto">Časová osa</h4>
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




            <div class="container bg-body-tertiary border p-3 mb-3 col-sm-12">
            <form id="descForm"  enctype="multipart/form-data" action="update.php" method="post">
                <div class="d-flex mb-3">
                    <h4 class="me-auto">Popis lokace</h4>
                </div>
                <div id="editor" class="bg-white" style="height:200px" onkeyup="document.querySelector('#pdescOut').value = document.querySelector('.ql-editor').innerHTML">
                    <?php echo $locdata['descr']?>
                </div>
                <input type="hidden" name="pdescOut" id="pdescOut">
                <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                <input type="button" value="Uložit" id="submit" class="btn btn-success mt-3" name="submit" onclick="submitDesc()">
            </form>
            <script>
                    function submitDesc() {
                        document.querySelector('#pdescOut').value = document.querySelector('.ql-editor').innerHTML
                        var form = document.getElementById('descForm');
                        var formData = new FormData(form);

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', '../data/update/places-desr-update.php', true);
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 300) {
                            if(xhr.responseText == '1'){
                                $('#success').modal('show')
                            }
                            else{
                                $('#unsuccess').modal('show')
                            }
                            // Zde můžete provést další akce po úspěšném odeslání formuláře
                            }
                        };
                        xhr.onerror = function() {
                            console.error('Request failed');
                            // Zde můžete zpracovat případnou chybu při odesílání formuláře
                        };
                        xhr.send(formData);
                    }
                </script>
        </div>




        <div class="container bg-body-tertiary border p-3 mb-3 col-sm-12">
                <form id="uploadForm"  enctype="multipart/form-data" action="update.php" method="post">
                <div class="d-flex mb-3">
                    <h4 class="me-auto">Odkazy</h4>
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




        <div class="container bg-body-tertiary border p-3 mb-3">
                <h4 class="mb-3">Umístění na mapě</h4>
                <div id="map" style="height:400px"></div>
                <script>
                    var map = L.map('map').setView([<?php echo $locdata['coordinates'];?>],20);

                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    L.marker([<?php echo $locdata['coordinates'];?>]).addTo(map)
                        .bindPopup('<?php echo $locdata['name'];?>')
                        .openPopup();
                </script>
            </div>




            <div class="container bg-body-tertiary border p-3 mb-3">
                <h4 class="mb-3">Náhledový obrázek</h4>
                <?php
                
                    if($locdata['header_img_path'] != null)
                    {
                        echo '<img class="img-thumbnail" src="../images/' . $locdata['header_img_path'] . '">';
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




