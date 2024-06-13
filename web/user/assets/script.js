function submitGeneral() {
    var form = document.getElementById('generalForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/update/places-general-update.php', true);
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


function submitMap() {
    var form = document.getElementById('mapForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/update/places-map-update.php', true);
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


function submitDesc() {
    var form = document.getElementById('descForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/update/places-descr-update.php', true);
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


function submitAddress() {
    var form = document.getElementById('addressForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/update/places-address-update.php', true);
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


function submitSecurity() {
    var form = document.getElementById('securityForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/update/places-security-update.php', true);
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


function submitImg() {
    var form = document.getElementById('imgForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/update/places-img-update.php', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
        if(xhr.responseText == '1'){
            $('#mSelectImg').modal('hide')
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


function delImg() {
    var form = document.getElementById('imgControlForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/delete/places-img-delete.php', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
        if(xhr.responseText == '1'){
            $('#mSelectImg').modal('hide')
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


document.getElementById('search').addEventListener('keyup', function() {
    var searchText = this.value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/search/owner.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById('results').innerHTML = xhr.responseText;
        }
    };
    xhr.send('searchText=' + searchText);
});

function loadOwners() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/search/owner.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById('results').innerHTML = xhr.responseText;
        }
    };
    xhr.send('searchText=%20');
}


function submitDelRow() {
    var form = document.getElementById('frmDelRow');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/delete/places-delete.php', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
        if(xhr.responseText == '1'){
            $('#success').modal('show')
            $('#mRowDel').modal('hide')
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


function submitImg() {
    var form = document.getElementById('imgForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/update/places-img-update.php', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
        if(xhr.responseText == '1'){
            $('#mSelectImg').modal('hide')
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

function delImg() {
    var form = document.getElementById('imgControlForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/delete/places-img-delete.php', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
        if(xhr.responseText == '1'){
            $('#mSelectImg').modal('hide')
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

function submitLinks() {
    var form = document.getElementById('linksForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/insert/places-links-insert.php', true);
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


function submitTimeline() {
    var form = document.getElementById('tlForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/insert/places-timeline-insert.php', true);
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


function submitDelLinks() {
    var form = document.getElementById('dellinksForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/delete/places-links-delete.php', true);
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


function submitDelTimeline() {
    var form = document.getElementById('deltimelineForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/delete/places-timeline-delete.php', true);
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


function submitForm() {
    var form = document.getElementById('ownerForm');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/update/owner-single-update.php', true);
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


function submitForm() {
    var form = document.getElementById('ownerForm1');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/update/owner-single-update.php', true);
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

const exampleModal0 = document.getElementById('mOWnerAddAsk')
if (exampleModal0) {
    exampleModal0.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget
        // Extract info from data-bs-* attributes
        const recipient = button.getAttribute('data-bs-whatever')
        // If necessary, you could initiate an Ajax request here
        // and then do the updating in a callback.

        // Update the modal's content.
        const modalBodyInput = exampleModal0.querySelector('.modal-footer #ownerID')

        modalBodyInput.value = recipient
    })
}


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


function loadNewOwner() {
    var form = document.getElementById('newOwner');
    var formData = new FormData(form);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../data/search/owner-single.php', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            document.querySelector('#newOwnerName').innerHTML = xhr.responseText.split(';')[0];
            document.querySelector('#newOwnerIco').innerHTML = "IČO: " + xhr.responseText.split(';')[1];

            document.querySelector('#newOwnerName1').innerHTML = xhr.responseText.split(';')[0];
            document.querySelector('#newOwnerIco1').innerHTML = "IČO: " + xhr.responseText.split(';')[1];
            // Zde můžete provést další akce po úspěšném odeslání formuláře
        }
    };
    xhr.onerror = function() {
        console.error('Request failed');
        // Zde můžete zpracovat případnou chybu při odesílání formuláře
    };
    xhr.send(formData);
}


window.addEventListener("load", function() {
    $('#cGeneral').collapse('show');
    $('#cMaps').collapse('show');
    $('#cAddress').collapse('show');
    $('#cDesc').collapse('show');
    $('#cOwner').collapse('show');
    $('#cSec').collapse('show');
    $('#cThImg').collapse('show');
    $('#cTimeLine').collapse('show');
    $('#cLinks').collapse('show');
});


var quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            ['link', 'image', 'video'],
            [{ 'align': [] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'color': [] }, { 'background': [] }],
            ['clean']
        ]
    },
    // placeholder: 'Compose an epic...',
    // bounds: '#editor', // Restricts typing area
    // scrollingContainer: '#scrolling-container', // Customize scrolling container
    // formats: ['bold', 'italic', 'underline'], // Customize available formats
    // blotFormatter: true, // Enable rich text blot formatter
    // keyboard: { bindings: ... } // Customize keyboard bindings
});