<?php
require "config/config.php";

?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
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
    <form id="inputFormSK">
        <label for="input"></label>
        <textarea id="input" name="input"></textarea><br>
        <button type="button" id="inputButtonSK">Vyrátaj</button><br>
        <p id="outputSK">Tu bude output</p>
    </form>
    <form id="inputFormR-SK">
        <label for="r">Zadaj r:</label>
        <input id="r" name="r" type="number" step="0.01">
        <button type="button" id="buttonR-SK">Odošli r</button>
    </form>
    <form>
        <button type="button" id="emailButtonSK">Odošli email</button>
        <p id="emailResponseSK"></p>
    </form>
</div>

<div id="langEN">
    <form id="inputFormEN">
        <label for="input"></label>
        <textarea id="input" name="input"></textarea><br>
        <button type="button" id="inputButtonEN">Calculate</button><br>
        <p id="outputEN">Here should be the output</p>
    </form>
    <form id="inputFormR-EN">
        <label for="r">Type r:</label>
        <input id="r" name="r" type="number">
        <button type="button" id="buttonR-EN">Send r</button>
    </form>
    <form>
        <button type="button" id="emailButtonEN">Send email</button>
        <p id="emailResponseEN"></p>
    </form>
</div>

<canvas id="graphCanvas" style="width:100%;max-width:700px"></canvas>
<canvas id="animationCanvas"></canvas>

<script>
    const inputButtonSK = document.getElementById("inputButtonSK");
    const inputButtonEN = document.getElementById("inputButtonEN");
    const graphCanvas = document.getElementById("graphCanvas");
    let key = "<?php echo $api_key;?>";
    let x1;
    let x2;

    inputButtonSK.addEventListener("click", () =>{
        const form = document.getElementById("inputFormSK");
        const data = new FormData(form);
        let json = jsonify(data);
        const request = new Request("api.php?api_key="+key,
            {
                method: "POST",
                body : json
            });
        fetch(request)
            .then(response => {
                if(response.status === 200){
                    response.json().then( result => {
                        document.getElementById("outputSK").innerText = result["output"];
                        document.getElementById("outputEN").innerText = result["output"];
                    })
                }
                else{
                    document.getElementById("outputSK").innerText = "Chýba príkaz.";
                    document.getElementById("outputEN").innerText = "Input missing.";
                }

            })

    })

    const inputRbuttonSK = document.getElementById("buttonR-SK");
    const inputRbuttonEN = document.getElementById("buttonR-EN");
    let timer;
    let index = 0;
    let r;
    inputRbuttonSK.addEventListener("click", () => {
        const form = document.getElementById("inputFormR-SK");
        const data = new FormData(form);
        r = data.get('r');
        fetch("api.php?api_key="+key+"&r="+r, {method: "GET"})
            .then(response => {
                if(response.status === 200){
                    index = 0;
                    response.json().then(result => {
                        x1 = result["x1"];
                        x2 = result["x2"];
                        timer = setInterval(addData, 10);
                    })
                }
            })
    })

    let oldValuesX1 = [r];
    let oldValuesX2 = [r];
    let oldLabels = [0];

    function addData() {
        let newDataX1 = x1[index];
        let newDataX2 = x2[index];
        let newRecord = {
            data: {
                x1: newDataX1,
                x2: newDataX2
            }
        }
        oldValuesX1.push(newRecord.data.x1);
        oldValuesX2.push(newRecord.data.x2)
        oldLabels.push(index);
        graph.update();
        index++;
        if(index === x1.length){
            clearInterval(timer);
        }
    }

    const graph = new Chart("graphCanvas", {
        type: 'line',
        data: {
            labels : oldLabels,
            datasets: [
                {
                    label: "X1",
                    data: oldValuesX1,
                    borderColor: 'rgb(194,20,51)',
                    tension: 0.5,
                },
                {
                    label: "X2",
                    data: oldValuesX2,
                    borderColor: 'rgb(0,73,255)',
                    tension: 0.5,
                }
            ]
        },
        options: {}
    });



    const emailButtonSK = document.getElementById("emailButtonSK");
    const emailButtonEN = document.getElementById("emailButtonEN");
    let email = "<?php echo $email ?>";

    emailButtonSK.addEventListener("click", () => {
        fetch("api.php?api_key="+key+"&email="+email, {method: "GET"})
            .then(response => {
                response.json().then(result => {
                    console.log(result);
                    document.getElementById("emailResponseSK").innerText = result["message"];
                    document.getElementById("emailResponseEN").innerText = result["message"];
                })
            })
    })

    function jsonify(data){
        let object = {};
        data.forEach(function (value,key){
            if(value !==""){
                object[key] = value;
            }

        });
        return JSON.stringify(object);
    }





</script>
<script type="text/javascript" src="js/script.js"></script>
</body>

</html>
