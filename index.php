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
    <form id="inputFormName-SK">
        <label for="nameInput-SK">Zadaj meno:</label>
        <input id="nameInput-SK" name="name" type="text" required>
        <button type="button" id="buttonName-SK">Prihlásiť sa</button>
    </form>
    <form id="loggedInForm-SK">
        <button type="button" id="buttonLogout-SK">Odhlásiť sa</button>
    </form>
    <p id="loginStatusText-SK">Nie ste prihlásení.</p>
    <ul id="editorList-SK"></ul>
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
    <form id="inputFormName-EN">
        <label for="nameInput-EN">Input name:</label>
        <input id="nameInput-EN" name="name" type="text" required>
        <button type="button" id="buttonName-EN">Log in</button>
        <p id="loginStatusText-EN"></p>
    </form>
</div>

<canvas id="graphCanvas" style="width:100%;max-width:700px"></canvas>
<canvas id="animationCanvas"></canvas>

<script>
    /* WEBSOCKETS START */
    /*
     */
    const socket = new WebSocket('wss://site196.webte.fei.stuba.sk:9000');

    let user = {};
    let editors = [];

    socket.addEventListener("message", msg => {
        const data = JSON.parse(msg.data);
        if ('event' in data) {
            if (data.event === 'new_user') {
                user.id = data.id;
                user.role = data.role;
            }
            else if (data.event === 'change_role'){
                if (data.role === 'editor') {
                    editors.push({'id': data.id, 'role': data.role, 'name': data.name})
                    updateEditors();
                } else if (data.role === 'visitor' && data.old_role === 'editor'){
                    editors.forEach(editor => {
                        if (editor.id === data.id){
                            let index = editors.indexOf(editor);
                            if (index !== -1) {
                                editors.splice(index, 1);
                            }
                        }
                    })
                    updateEditors();
                }
            }
        }
    })

    window.addEventListener("beforeunload", () => {
        if (user.role === 'editor'){
            logoutButtonSK.click();
        }
    })

    const findInEditors = (name) => {
        let flag = false;
        editors.forEach(editor => {
            if (editor.name === name){
                flag = true;
            }
        })
        return flag;
    }


    const loginFormSK = document.getElementById("inputFormName-SK");
    const loggedInFormSK = document.getElementById("loggedInForm-SK");
    const loginButtonSK = document.getElementById("buttonName-SK");
    const loginInputSK = document.getElementById("nameInput-SK");
    const loginStatusSK = document.getElementById("loginStatusText-SK");
    const editorListSK = document.getElementById("editorList-SK");
    const logoutButtonSK = document.getElementById("buttonLogout-SK");

    loggedInFormSK.style.display = 'none';

    const updateEditors = () => {
        if (editors) {
            let child = editorListSK.lastElementChild;
            while (child) {
                editorListSK.removeChild(child);
                child = editorListSK.lastElementChild;
            }
        }
        editors.forEach(editor => {
            let li = document.createElement("li");
            li.innerText = editor.name;
            li.style.cursor = "pointer";
            li.addEventListener("click", () => {

            })
            editorListSK.appendChild(li);
        })
    }

    loginButtonSK.addEventListener("click", () =>{
        const data = new FormData(loginFormSK);
        let name = data.get('name');
        if (name && !findInEditors(name)){
            let oldRole = user.role;
            user.role = 'editor';
            user.name = name;
            socket.send(JSON.stringify({'event': 'change_role', 'role': user.role, 'name': name, 'old_role': oldRole}));
            loginFormSK.style.display = 'none';
            loggedInFormSK.style.display = 'block';
            editorListSK.style.display = 'none';
            loginStatusSK.innerText = "Prihlásený pod menom: "+name;
        } else {
            loginStatusSK.innerText = "Meno už existuje.";
        }
    })

    logoutButtonSK.addEventListener("click", () =>{
        updateEditors();
        let oldRole = user.role;
        user.role = 'visitor';
        user.name = undefined;
        socket.send(JSON.stringify({'event': 'change_role', 'role': user.role, 'name': user.name, 'old_role': oldRole}));
        loggedInFormSK.style.display = 'none';
        loginFormSK.style.display = 'block';
        editorListSK.style.display = 'block';

    })
    /*
     */
    /* WEBSOCKETS END */




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
