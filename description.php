<?php
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.min.js"></script>
    <script type="text/javascript" src="js/print.js"></script>
    <title>API</title>
</head>

<header><h1 id="lang">Tlmič automobilu</h1></header>

<body>
<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="nav-link" href="." id="main">Hlavná stránka</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="description.php" id="desc">Popis API</a>
    </li>
</ul>



    <label for="language"></label>
    <select class="form-select w-auto mb-4 mt-2" name="language" id="language" onchange="selectLangAPI(this.value)">
        <option value="SK">SK</option>
        <option value="EN">EN</option>
    </select>


    <a href="javascript:pdfFromHTML()" class="button">PDF</a>

    <div id="contentSK">
        <div class="part">
            <h2>POST /api.php?api_key={api_key}</h2>
            <p>Vráti vyrátanú hodnotu</p>
            <p>Telo</p>
            <pre>
                Example
                    [{
                        "input": string
                    }]
            </pre>
            <h3>Odpoved</h3>
            <h4> kód : 200 (OK)</h4>
            <pre>
                    Príklad
                    [{
                        "output": string
                    }]
            </pre>
        </div>

        <div class="part">
            <h2>GET /api.php?api_key={api_key}&r={r}</h2>
            <p>Vráti polia x1 a x2</p>
            <pre>
                r = number (výška prekážky)
            </pre>
            <h3>Odpoved</h3>
            <h4> kód : 200 (OK)</h4>
            <pre>
                Príklad
                [{
                    "x1": array,
                    "x2": array
                }]
            </pre>
        </div>

        <div class="part">
            <h2>GET /api.php?api_key={api_key}&email={email}</h2>
            <p>Pošle logy na email ako csv súbor</p>
            <pre>
                email = email nastavený v config
            </pre>
            <h3>Odpoved</h3>
            <h4> kód : 200 (OK)</h4>
            <pre>
                Príklad
                [{
                    "message" : string
                }]
            </pre>

        </div>
    </div>

    <div id="contentEN">
        <div class="part">
            <h2>POST /api.php?api_key={api_key}</h2>
            <p>Returns calculated value</p>
            <p>Body</p>
            <pre>
                Example
                    [{
                        "input": string
                    }]
            </pre>
            <h3>Response</h3>
            <h4> code: 200 (OK)</h4>
            <pre>
                    Example
                    [{
                        "output": string
                    }]
            </pre>
        </div>

        <div class="part">
            <h2>GET /api.php?api_key={api_key}&r={r}</h2>
            <p>Returns arrays x1 and x2</p>
            <pre>
                r = number (obstacle height)
            </pre>
            <h3>Response</h3>
            <h4> code: 200 (OK)</h4>
            <pre>
                Example
                [{
                    "x1": array,
                    "x2": array
                }]
            </pre>
        </div>

        <div class="part">
            <h2>GET /api.php?api_key={api_key}&email={email}</h2>
            <p>Send logs to email as csv file</p>
            <pre>
                email = email set in config
            </pre>
            <h3>Response</h3>
            <h4> code: 200 (OK)</h4>
            <pre>
                Example
                [{
                    "message" : string
                }]
            </pre>

        </div>
    </div>



<footer>© 2022 Vychlopeňová, Šalata, Lavrinčík, Masaryk</footer>

</body>
</html>
