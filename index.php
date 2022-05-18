<?php
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="js/script.js"></script>
    <title>Webte Final</title>
</head>
<header><h1 id="lang">Tlmič automobilu</h1></header>
<body>

<nav>
    <div class="navbar">
        <ul>
            <li><a href="index.php" id="main">Hlavná stránka</a></li>
            <li><a href="description.php" id="desc">Popis API</a></li>
        </ul>
    </div>
</nav>

<label for="language">SK / EN:</label>
<select name="language" id="language" onchange="selectLang(this.value)">
    <option value="SK">SK</option>
    <option value="EN">EN</option>
</select>

<div id="langSK">
    <form>
        <label for="textarea"></label>
        <textarea id="textarea"></textarea><br>
        <button type="submit">Odošli</button><br>
        <p class="output">Tu bude output</p>
    </form>
</div>

<div id="langEN">
    <form>
        <label for="textarea"></label>
        <textarea id="textarea"></textarea><br>
        <button type="submit">Send</button><br>
        <p class="output">Here should be the output</p>
    </form>
</div>

<canvas id="graphCanvas"></canvas>
<canvas id="animationCanvas"></canvas>

</body>

</html>
