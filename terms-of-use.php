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

.btn-google {
  color: white !important;
  background-color: #ea4335;
}

.btn-facebook {
  color: white !important;
  background-color: #3b5998;
}
.form-control:focus{
  border: 1px solid #0164E7;
  box-shadow: none;
}
    </style>
  </head>
  <body>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-10 mx-auto">
        <div class="card border-0 shadow rounded-3 my-5">
          <div class="card-body p-4 p-sm-5">
            <h5 class="card-title text-center mb-5 fw-light fs-5">Podmínky pro používání webové platformy pro nahrávání a sdílení souborů:</h5>
            <form method="post" action="web/">
                <ul>
                    <li>
                        <h6>Povolený obsah</h6>
                        <ul>
                            <li>Uživatelé mohou nahrávat pouze soubory, ke kterým mají práva a které nesporují platné zákony.</li>
                            <li>Zakázáno je sdílet obsah porušující autorská práva, nelegální software, nebo jakýkoli obsah, který podněcuje k nenávisti nebo násilí.</li>
                        </ul>
                    </li>
                    <li>
                        <h6>Omezení typů souborů</h6>
                        <ul>
                            <li>Přijímány jsou běžné formáty souborů (např. obrázky, dokumenty, videa).</li>
                            <li>Zakázáno je nahrávat soubory s nebezpečným obsahem, včetně škodlivého softwaru a virů.</li>
                        </ul>
                    </li>
                    <li>
                        <h6>Odpovědnost za obsah</h6>
                        <ul>
                            <li>Uživatelé jsou plně odpovědni za obsah, který nahrávají, a souhlasí, že provozovatel webu nenese odpovědnost za jejich činy.</li>
                        </ul>
                    </li>
                    <li>
                        <h6>Ochrana osobních údajů</h6>
                        <ul>
                            <li>Provádění nahrávek a sdílení obsahu nesmí porušovat práva na ochranu osobních údajů ostatních uživatelů.</li>
                        </ul>
                    </li>
                    <li>
                        <h6>Respektování práv třetích stran</h6>
                        <ul>
                            <li>Uživatelé se zavazují respektovat práva a soukromí třetích stran, např. nedostávat se neoprávněně k jejich souborům nebo je nesdílet bez povolení.</li>
                        </ul>
                    </li>
                    <li>
                        <h6>Omezení kapacity a doby uchovávání</h6>
                        <ul>
                            <li>Web může stanovit maximální kapacitu pro jednotlivé uživatelské účty a vyhrazenou dobu uchovávání nahrávek.</li>
                        </ul>
                    </li>
                    <li>
                        <h6>Ochrana proti zneužití</h6>
                        <ul>
                            <li>Jakékoli pokusy o neoprávněný přístup, manipulaci s daty nebo zneužívání služby mohou vést k okamžitému ukončení účtu.</li>
                        </ul>
                    </li>
                    <li>
                        <h6>Aktualizace podmínek</h6>
                        <ul>
                            <li>Web si vyhrazuje právo kdykoli aktualizovat podmínky používání a uživatelé jsou povinni tyto změny respektovat.</li>
                        </ul>
                    </li>
                </ul>
                <p>Používání této webové platformy je podmíněno dodržováním uvedených pravidel a jejich porušení může mít za následek omezení nebo zrušení přístupu k službě.</p>
                <div class="container mt-5">
                    <input type="submit" value="Souhlasím" class="btn btn-success">
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