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
    <script type="text/javascript" src="js/print.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <title>Doc</title>
</head>
<header><h1 id="lang">Dokumentácia</h1></header>
<body>

<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="nav-link" href="." id="main">Hlavná stránka</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="description.php" id="desc">Popis API</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="documentation.php" id="doc">Dokumentácia</a>
    </li>
</ul>

<label for="language"></label>
<select class="form-select w-auto mb-4 mt-2" name="language" id="language" onchange="selectLangDOC(this.value)">
    <option value="SK" selected>SK</option>
    <option value="EN">EN</option>
</select>

<div id="contentSK">
    <h2>Rozdelenie úloh:</h2>
    <div class="part">
        <div class="part">
            <h4>xvychlopenova:</h4>
            <ul>
                <li>Dvojjazyčnosť</li>
                <li>Graf synchronizovaný s animáciou</li>
                <li>Export popisu API do pdf</li>
                <li>Používanie verzionovacieho systému</li>
                <li>Oprava chýb</li>
                <li>Finalizácia aplikácie</li>
            </ul>
        </div>
        <div class="part">
            <h4>xsalata:</h4>
            <ul>
                <li>API ku CAS zabezpečené API kľúčom alebo tokenom</li>
                <li>Logovanie a export do csv + odoslanie mailu</li>
                <li>Overenie API cez formulár v rozsahu špecifikovanom v úlohe 5</li>
                <li>Používanie verzionovacieho systému</li>
                <li>Oprava chýb</li>
                <li>Finalizácia aplikácie</li>
            </ul>
        </div>
        <div class="part">
            <h4>xlavrincikb:</h4>
            <ul>
                <li>Animácia</li>
                <li>Používanie verzionovacieho systému</li>
                <li>Oprava chýb</li>
                <li>Finalizácia aplikácie</li>
            </ul>
        </div>
        <div class="part">
            <h4>xmasarykm1:</h4>
            <ul>
                <li>Synchrónne sledovanie experimentovania</li>
                <li>Používanie verzionovacieho systému</li>
                <li>Oprava chýb</li>
                <li>Finalizácia aplikácie</li>
            </ul>
        </div>
        <div class="part">
            <h4>Nedokončené úlohy:</h4>
            <ul>
                <li>Docker balíček</li>
            </ul>
        </div>
    </div>

    <div class="part">
        <h4>Použité knižnice, frameworky a API:</h4>

        <p>ChartJS: knižnica pre vykreslenie grafu</p>
        <p>Link: https://www.chartjs.org</p><br>

        <p>JSpdf: knižnica pre export stránku do pdf a stiahnutie pdf</p>
        <p>Link: https://github.com/parallax/jsPDF</p><br>

        <p>JQuery: knižnica, kladie dôraz na interakciu medzi JavaScriptom a HTML</p>
        <p>Link: https://jquery.com</p><br>

        <p>NodeJS: Knižnica pre prácu s webovým serverom</p>
        <p>Link: https://nodejs.org/en/</p><br>

        <p>PHPMailer: Knižnica pre bezpečné odosielanie e-mailov</p>
        <p>Link: https://github.com/PHPMailer/PHPMailer</p><br>

        <p>Bootstrap: framework s nástrojmi pre CSS</p>
        <p>Link: https://getbootstrap.com</p><br>

        <p>Websockets: API, ktorý poskytuje obojsmernú interaktívnu komunikačnú reláciu medzi prehliadačom používateľa a serverom</p>
        <p>Link: https://github.com/PHPMailer/PHPMailer</p><br>

        <p>Octave: softvér na prevádzanie šíselných výpočtov</p>
        <p>Link: https://www.gnu.org/software/octave/index</p><br>
    </div>

</div>

<div id="contentEN" style="display: none">
    <h2>How we divided our tasks:</h2>
    <div class="part">
        <div class="part">
            <h4>xvychlopenova:</h4>
            <ul>
                <li>Bilingual site</li>
                <li>Graph synchronized with animation</li>
                <li>Export API description to pdf</li>
                <li>Using the versioning system</li>
                <li>Fixing bugs</li>
                <li>Application finalization</li>
            </ul>
        </div>
        <div class="part">
            <h4>xsalata:</h4>
            <ul>
                <li>API to CAS secured by API key or token</li>
                <li>Logging and exporting to csv + sending email</li>
                <li>Forms API validation to the extent specified in Task 5</li>
                <li>Using the versioning system</li>
                <li>Fixing bugs</li>
                <li>Application finalization</li>
            </ul>
        </div>
        <div class="part">
            <h4>xlavrincikb:</h4>
            <ul>
                <li>Animmation</li>
                <li>Using the versioning system</li>
                <li>Fixing bugs</li>
                <li>Application finalization</li>
            </ul>
        </div>
        <div class="part">
            <h4>xmasarykm1:</h4>
            <ul>
                <li>Synchronous monitoring of experimentation</li>
                <li>Using the versioning system</li>
                <li>Fixing bugs</li>
                <li>Application finalization</li>
            </ul>
        </div>
        <div class="part">
            <h4>Unfinished tasks:</h4>
            <ul>
                <li>Docker package</li>
            </ul>
        </div>
    </div>

    <div class="part">
        <h4>Used libraries, frameworks and APIs:</h4>

        <p>ChartJS: library for graphs</p>
        <p>Link: https://www.chartjs.org</p><br>

        <p>JSpdf: library for exporting site to pdf and download pdf</p>
        <p>Link: https://github.com/parallax/jsPDF</p><br>

        <p>JQuery: library for simplifying JavaScript programming</p>
        <p>Link: https://jquery.com</p><br>

        <p>NodeJS: library for working with web server</p>
        <p>Link: https://nodejs.org/en/</p><br>

        <p>PHPMailer: library for safe email sending</p>
        <p>Link: https://github.com/PHPMailer/PHPMailer</p><br>

        <p>Bootstrap: framework with tools for CSS</p>
        <p>Link: https://getbootstrap.com</p><br>

        <p>Websockets: API for opening a two-way interactive communication session between the user's browser and a server</p>
        <p>Link: https://github.com/PHPMailer/PHPMailer</p><br>

        <p>Octave: softwave used for numerical computations</p>
        <p>Link: https://www.gnu.org/software/octave/index</p><br>
    </div>
</div>
<footer>© 2022 xvychlopenova, xsalata, xlavrincikb, xmasarykm1</footer>
</body>
</html>
