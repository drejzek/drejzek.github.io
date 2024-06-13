<?php
session_start();

require '../../config.php';
require '../data/class.data.php';

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
    $id = $_GET['id'];

    $dt = new Data($id);
    $links = $dt->getAllLinks();

    $posid = explode(", ", $locdata['coordinates'])[0] . "N/" . explode(", ", $locdata['coordinates'])[1] . "E";

  }
  else{

    $dt = new Data(0);
    $links = $dt->getALlLinks();
    

    
  }
?>

<?php include '../../header.php'; ?>
<?php

if(isset($_GET['success'])){
    echo $modal;
}

?>
<div class="container">
    <div class="container bg-body-tertiary border p-3 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href=".">Urbex Nation</a></li>
                <li class="breadcrumb-item"><a href=".">Lokace</a></li>
                <li class="breadcrumb-item active" aria-current="page">Odkazy</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="container bg-body-tertiary border p-3 mb-3">
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
                    <table class="table table-hover" id="linksTable">
                        <thead>
                            <tr>
                                <th>Titulek</th>
                                <th>Typ</th>
                                <th>Popis</th>
                                <th>Lokace</th>
                                <th>Akce</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $lnk_type = null;
                                while($lnk = mysqli_fetch_array($links)){
                                    $loc = (new Data(0))->getLocNameByPID($lnk['place_id']);
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
                                        <!--<td style="max-width: 50%">' . $lnk['url'] . '</td>-->
                                        <td>' . $lnk['descr'] . '</td>
                                        <td>' . $loc . '</td>
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
            </form>
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
            <form action="data/insert/places-links-insert.php" id="linksForm" method=post>
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
            <script>
                    function submitLinks() {
                        var form = document.getElementById('linksForm');
                        var formData = new FormData(form);

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'data/insert/places-links-insert.php', true);
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 300) {
                            if(xhr.responseText == '1'){
                                $('#success').modal('show')
                                $('#mAddLink').modal('hide')
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
                <script>
                function cloneLink(id)
                {
                    // Najdi řádek s daným id
                    var row = document.getElementById('linksTable').querySelector('tr[id="' + id + '"]');
                    
                    var titleInput = document.getElementById('title');
                    var urlInput = document.getElementById('url');
                    var descInput = document.getElementById('desc');

                    // Pokud řádek byl nalezen
                    if (row) 
                    {
                        // Uložení dat z řádku do proměnných
                        titleInput.value = row.cells[0].innerText;
                        urlInput.value = row.cells[2].innerText;
                        descInput.innerHTML = row.cells[3].innerText;

                        $('#mAddLink').modal('show');
                    } 
                    else
                    {
                        console.log("Řádek s id " + id + " nebyl nalezen.");
                    }
                }
            </script>
        </div>
    </div>
</div>
 <div class="modal fade" id="mAddTimeline">
    <div class="modal-dialog modal-dialog-centered modal-dialog-md">
        <div class="modal-content">
            <form action="data/insert/places-timeline-insert.php" id="tlForm" method=post>
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
                    function submitTimeline() {
                        var form = document.getElementById('tlForm');
                        var formData = new FormData(form);

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'data/insert/places-timeline-insert.php', true);
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 300) {
                            if(xhr.responseText == '1'){
                                $('#success').modal('show')
                                $('#mAddTimeline').modal('hide')
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
                <script>
                function cloneLink(id)
                {
                    // Najdi řádek s daným id
                    var row = document.getElementById('linksTable').querySelector('tr[id="row' + id + '"]');
                    
                    var titleInput = document.getElementById('title');
                    var urlInput = document.getElementById('url');
                    var descInput = document.getElementById('desc');

                    // Pokud řádek byl nalezen
                    if (row) 
                    {
                        // Uložení dat z řádku do proměnných
                        titleInput.value = row.cells[0].innerText;
                        urlInput.value = row.cells[2].innerText;
                        descInput.innerHTML = row.cells[3].innerText;

                        $('#mAddLink').modal('show');
                    } 
                    else
                    {
                        console.log("Řádek s id " + id + " nebyl nalezen.");
                    }
                }
            </script>
        </div>
    </div>
</div>
 <div class="modal fade" id="mLinkDelAsk">
    <div class="modal-dialog modal-dialog-centered modal-dialog-md">
        <div class="modal-content">
            <form action="data/insert/places-links-insert.php" id="dellinksForm" method=post>
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
            <script>
                    function submitDelLinks() {
                        var form = document.getElementById('dellinksForm');
                        var formData = new FormData(form);

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'data/delete/places-links-delete.php', true);
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 300) {
                            if(xhr.responseText == '1'){
                                $('#success').modal('show')
                                $('#mLinkDelAsk').modal('hide')
                                var id = document.querySelector('#LinkID').value
                                var row = document.querySelector('#row' + id).remove()
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
    </div>
</div>
 <div class="modal fade" id="mTimelineDelAsk">
    <div class="modal-dialog modal-dialog-centered modal-dialog-md">
        <div class="modal-content">
            <form action="data/insert/places-timeline-delete.php" id="deltimelineForm" method=post>
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
            <script>
                    function submitDelTimeline() {
                        var form = document.getElementById('deltimelineForm');
                        var formData = new FormData(form);

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'data/delete/places-timeline-delete.php', true);
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 300) {
                            if(xhr.responseText == '1'){
                                $('#success').modal('show')
                                $('#mTimelineDelAsk').modal('hide')
                                var id = document.querySelector('#TlID').value
                                var row = document.querySelector('#tl' + id).remove()
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
                    </form>
                </div>
                <button class="btn btn-danger" data-bs-dismiss="modal">Ne</i></button>
                <script>
                    function submitForm() {
                        var form = document.getElementById('ownerForm');
                        var formData = new FormData(form);

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'data/update/owner-single-update.php', true);
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
        </div>
    </div>
</div>
    <script>
        function updateVote(action, fileId) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'vote.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.getElementById('likesCount').textContent = response.likes;
                        document.getElementById('dislikesCount').textContent = response.dislikes;
                    } else {
                        console.error(response.message);
                    }
                }
            };
            xhr.send('action=' + action + '&file_id=' + fileId);
        }
    </script>
    <script>
        const exampleModal = document.getElementById('mOWnerChangeAsk')
            if (exampleModal) {
                exampleModal.addEventListener('show.bs.modal', event => {
                    // Button that triggered the modal
                    const button = event.relatedTarget
                    // Extract info from data-bs-* attributes
                    const recipient = button.getAttribute('data-bs-whatever')
                    // If necessary, you could initiate an Ajax request here
                    // and then do the updating in a callback.

                    // Update the modal's content.
                    const modalBodyInput = exampleModal.querySelector('.modal-footer #ownerID')

                    modalBodyInput.value = recipient
                })
            }
    </script>
    <script>
        const exampleModal1 = document.getElementById('mLinkDelAsk')
            if (exampleModal1) {
                exampleModal1.addEventListener('show.bs.modal', event => {
                    // Button that triggered the modal
                    const button = event.relatedTarget
                    // Extract info from data-bs-* attributes
                    const recipient = button.getAttribute('data-bs-whatever')
                    // If necessary, you could initiate an Ajax request here
                    // and then do the updating in a callback.

                    // Update the modal's content.
                    const mInput = exampleModal1.querySelector('#LinkID')

                    mInput.value = recipient
                })
            }
    </script>
    <script>
        const exampleModal2 = document.getElementById('mTimelineDelAsk')
            if (exampleModal2) {
                exampleModal2.addEventListener('show.bs.modal', event => {
                    // Button that triggered the modal
                    const button = event.relatedTarget
                    // Extract info from data-bs-* attributes
                    const recipient = button.getAttribute('data-bs-whatever')
                    // If necessary, you could initiate an Ajax request here
                    // and then do the updating in a callback.

                    // Update the modal's content.
                    const mInput = exampleModal2.querySelector('#TlID')

                    mInput.value = recipient
                })
            }
    </script>
    <script>
        function loadNewOwner() {
            var form = document.getElementById('newOwner');
            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'data/search/owner-single.php', true);
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    document.querySelector('#newOwnerName').innerHTML = xhr.responseText.split(';')[0];
                    document.querySelector('#newOwnerIco').innerHTML = "IČO: " + xhr.responseText.split(';')[1];
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
    <script src="AddRow.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
