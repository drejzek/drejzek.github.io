var i = 1;
function AddRow(){
    var links = document.querySelector('#links');
    var row = document.createElement('tr');
    var idcell = document.createElement('td');
    var titlecell = document.createElement('td');
    var typecell = document.createElement('td');
    var urlcell = document.createElement('td');
    var desccell = document.createElement('td');

    var id = document.createElement('span');
    id.id = "id";
    id.innerHTML = i;

    var inputId = document.createElement('input');
    inputId.type = "hidden";
    inputId.name = "rowId";
    inputId.value = i;

    var title = document.createElement('input');
    title.type = "text";
    title.name = "title[]";
    title.className = "form-control";

    var type = document.createElement('select');
    type.name = "type[]";
    type.className = "form-select";

    var o = document.createElement('option');
    o.textContent = "";
    o.value = 1;
    var o1 = document.createElement('option');
    o1.textContent = "Článek";
    o1.value = 1;
    var o2 = document.createElement('option');
    o2.textContent = "Galerie";
    o2.value = 2;
    var o3 = document.createElement('option');
    o3.textContent = "Video";
    o3.value = 3;

    type.appendChild(o);
    type.appendChild(o1);
    type.appendChild(o2);
    type.appendChild(o3);

    var url = document.createElement('input');
    url.type = "url";
    url.name = "url[]";
    url.className = "form-control";

    var desc = document.createElement('input');
    desc.type = "desc";
    desc.name = "desc[]";
    desc.className = "form-control";

    idcell.appendChild(inputId);
    idcell.appendChild(id);
    titlecell.appendChild(title);
    typecell.appendChild(type);
    urlcell.appendChild(url);
    desccell.appendChild(desc);
    row.appendChild(idcell);
    row.appendChild(titlecell);
    row.appendChild(typecell);
    row.appendChild(urlcell);
    row.appendChild(desccell);
    links.appendChild(row);

    i++;
}