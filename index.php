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
        <label for="inputSK"></label>
        <textarea id="inputSK" name="input"></textarea><br>
        <button type="button" id="inputButtonSK">Vyrátaj</button><br>
        <p id="outputSK">Tu bude output</p>
    </form>
    <form id="inputFormR-SK">
        <label for="rSK">Zadaj r:</label>
        <input id="rSK" name="r" type="number" step="0.01">
        <button type="button" id="buttonR-SK" onclick="animationLoop()">Odošli r</button>
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
    <p id="loginStatusText-SK">Nie ste prihlásený</p>
    <ul id="editorList-SK"></ul>

</div>

<div id="langEN">
    <form id="inputFormEN">
        <label for="inputEN"></label>
        <textarea id="inputEN" name="input"></textarea><br>
        <button type="button" id="inputButtonEN">Calculate</button><br>
        <p id="outputEN">Here should be the output</p>
    </form>
    <form id="inputFormR-EN">
        <label for="rEN">Type r:</label>
        <input id="rEN" name="r" type="number" step="0.01">
        <button type="button" id="buttonR-EN" onclick="animationLoop()">Send r</button>
    </form>
    <form>
        <button type="button" id="emailButtonEN">Send email</button>
        <p id="emailResponseEN"></p>
    </form>
    <form id="inputFormName-EN">
        <label for="nameInput-EN">Input name:</label>
        <input id="nameInput-EN" name="name" type="text" required>
        <button type="button" id="buttonName-EN">Log in</button>
    </form>
    <form id="loggedInForm-EN">
        <button type="button" id="buttonLogout-EN">Log out</button>
    </form>
    <p id="loginStatusText-EN">You are not logged in</p>
    <ul id="editorList-EN"></ul>

</div>

<div id="choice">
    <label for="checkGraph">graph</label>
    <input type="checkbox" id="checkGraph" onclick="checkChoiceValue()" checked>
    <label for="checkAnim">anim</label>
    <input type="checkbox" id="checkAnim" onclick="checkChoiceValue()" checked>
</div>

<canvas id="graphCanvas" style="width:100%;max-width:700px"></canvas>
<canvas id="animationCanvas" width="300" height="500"></canvas>

<style>
    #animationCanvas {
        margin:20px auto;
        border:1px solid #333;
    }
</style>

<script>

    // ANIMACIA
    let frameRate  = 1/15;
    let frameDelay = frameRate * 1000;
    let t;

    let canvas;
    let ctx;
    let width  = 300;
    let height = 500;

    let block_m1_x = width/2 - 40;
    let block_m1_y = 300;

    let block_m2_x = width/2 - 40;
    let block_m2_y = 130;

    let setup = function() {
        let block_m1_y = 300;
        let block_m2_y = 130;
        canvas = document.getElementById('animationCanvas');
        ctx = canvas.getContext('2d');

        // KRESLENIE
        ctx.clearRect(0, 0, width, height);
        ctx.save();


        // ZEM
        ctx.fillStyle = '#CCCCCC';
        ctx.fillRect(0, height-20, width, 20);

        // KOLESO
        /*
        ctx.beginPath();
        ctx.arc(150, 340, 140, 0, 2 * Math.PI);
        ctx.strokeStyle = "black";
        ctx.lineWidth = 8;
        ctx.stroke();
        ctx.lineWidth = 1;
        ctx.fillStyle = '#6e7377';
        ctx.fill();
        */

        // pružina 1
        for (y = 0 ; y < block_m1_y; y += 20)
        {
            ctx.strokeStyle = 'black';
            ctx.beginPath();
            ctx.moveTo(width/2 -10, y);
            ctx.lineTo(block_m1_x + 15 , y - 10);
            ctx.lineTo(block_m1_x + 30, y - 20);
            ctx.stroke();
            ctx.closePath();
        }
        for (y = 0 ; y < block_m1_y; y += 20)
        {
            ctx.strokeStyle = 'black';
            ctx.beginPath();
            ctx.moveTo(width/2 - 25, y);
            ctx.lineTo(block_m1_x + 30, y - 10);
            ctx.lineTo(block_m1_x + 15, y - 20);
            ctx.stroke();
            ctx.closePath();
        }

        // pružina 2
        for (y = block_m1_y ; y > block_m2_y + 30; y -= 20)
        {
            ctx.strokeStyle = 'black';
            ctx.beginPath();
            ctx.moveTo(width/2 -10, y);
            ctx.lineTo(block_m1_x + 15 , y - 10);
            ctx.lineTo(block_m1_x + 30, y - 20);
            ctx.stroke();
            ctx.closePath();
        }
        for (y = block_m1_y ; y > block_m2_y + 30; y -= 20)
        {
            ctx.strokeStyle = 'black';
            ctx.beginPath();
            ctx.moveTo(width/2 - 25, y);
            ctx.lineTo(block_m1_x + 30, y - 10);
            ctx.lineTo(block_m1_x + 15, y - 20);
            ctx.stroke();
            ctx.closePath();
        }

        // ČIARA M1
        ctx.strokeStyle = 'black';
        ctx.beginPath();
        ctx.moveTo(width/2 + 10, 0);
        ctx.lineTo(block_m1_x + 50, block_m2_y);
        ctx.stroke();
        ctx.closePath();

        // ČIARA M2
        ctx.strokeStyle = 'black';
        ctx.beginPath();
        ctx.moveTo(width/2 + 10, block_m1_y);
        ctx.lineTo(block_m2_x + 50, block_m2_y + 40);
        ctx.stroke();
        ctx.closePath();


        // M1
        ctx.fillStyle = '#C21433FF';
        ctx.fillRect(block_m1_x , block_m1_y , 80, 40);
        ctx.strokeStyle = "black";
        ctx.lineWidth = 2;
        ctx.strokeRect(block_m1_x , block_m1_y , 80, 40);
        ctx.lineWidth = 1;
        ctx.fillStyle = "white";
        ctx.font = "18px Verdana";
        ctx.fillText("m1", block_m1_x + 25 , block_m1_y + 25);


        // M2
        ctx.fillStyle = '#0049FFFF';
        ctx.fillRect(block_m2_x , block_m2_y , 80, 40);
        ctx.strokeStyle = "black";
        ctx.lineWidth = 2;
        ctx.strokeRect(block_m2_x , block_m2_y , 80, 40);
        ctx.lineWidth = 1;
        ctx.fillStyle = "white";
        ctx.font = "18px Verdana";
        ctx.fillText("m2", block_m2_x + 25 , block_m2_y + 25);

        ctx.restore();
    };
    setup();

    function animationLoop() {
        let i = 0;
        let loop = function () {
            if(!isNaN(oldValuesX1[i]) || !isNaN(oldValuesX2[i])) {
                // KRESLENIE
                ctx.clearRect(0, 0, width, height);
                ctx.save();

                // ZEM
                ctx.fillStyle = '#CCCCCC';
                ctx.fillRect(0, height - 20, width, 20);

                // KOLESO
                /*
                ctx.beginPath();
                ctx.arc(150, 340, 140, 0, 2 * Math.PI);
                ctx.strokeStyle = "black";
                ctx.lineWidth = 8;
                ctx.stroke();
                ctx.lineWidth = 1;
                ctx.fillStyle = '#6e7377';
                ctx.fill();
                 */

                // pružina 1

                block_m1_y = 300;
                block_m1_y += oldValuesX1[i] * 2000;
                block_m2_y = 130;
                block_m2_y += oldValuesX2[i] * 2000;

                // pružina 1
                for (y = 0 ; y < block_m1_y; y += 20)
                {
                    ctx.strokeStyle = 'black';
                    ctx.beginPath();
                    ctx.moveTo(width/2 -10, y);
                    ctx.lineTo(block_m1_x + 15 , y - 10);
                    ctx.lineTo(block_m1_x + 30, y - 20);
                    ctx.stroke();
                    ctx.closePath();
                }
                for (y = 0 ; y < block_m1_y; y += 20)
                {
                    ctx.strokeStyle = 'black';
                    ctx.beginPath();
                    ctx.moveTo(width/2 - 25, y);
                    ctx.lineTo(block_m1_x + 30, y - 10);
                    ctx.lineTo(block_m1_x + 15, y - 20);
                    ctx.stroke();
                    ctx.closePath();
                }

                // pružina 2
                for (let y = block_m1_y; y > block_m2_y + 30; y -= 20) {
                    ctx.strokeStyle = 'black';
                    ctx.beginPath();
                    ctx.moveTo(width / 2 - 10, y);
                    ctx.lineTo(block_m1_x + 15, y - 10);
                    ctx.lineTo(block_m1_x + 30, y - 20);
                    ctx.stroke();
                    ctx.closePath();
                }
                for (y = block_m1_y ; y > block_m2_y + 30; y -= 20)
                {
                    ctx.strokeStyle = 'black';
                    ctx.beginPath();
                    ctx.moveTo(width/2 - 25, y);
                    ctx.lineTo(block_m1_x + 30, y - 10);
                    ctx.lineTo(block_m1_x + 15, y - 20);
                    ctx.stroke();
                    ctx.closePath();
                }

                // ČIARA M1
                ctx.strokeStyle = 'black';
                ctx.beginPath();
                ctx.moveTo(width/2 + 10, 0);
                ctx.lineTo(block_m1_x + 50, block_m2_y);
                ctx.stroke();
                ctx.closePath();

                // ČIARA M2
                ctx.strokeStyle = 'black';
                ctx.beginPath();
                ctx.moveTo(width / 2 + 10, block_m1_y);
                ctx.lineTo(block_m2_x + 50, block_m2_y + 40);
                ctx.stroke();
                ctx.closePath();


                // M1
                ctx.fillStyle = '#C21433FF';
                ctx.fillRect(block_m1_x, block_m1_y, 80, 40);
                ctx.strokeStyle = "black";
                ctx.lineWidth = 2;
                ctx.strokeRect(block_m1_x, block_m1_y, 80, 40);
                ctx.lineWidth = 1;
                ctx.fillStyle = "white";
                ctx.font = "18px Verdana";
                ctx.fillText("m1", block_m1_x + 25, block_m1_y + 25);


                // M2
                ctx.fillStyle = '#0049FFFF';
                ctx.fillRect(block_m2_x, block_m2_y, 80, 40);
                ctx.strokeStyle = "black";
                ctx.lineWidth = 2;
                ctx.strokeRect(block_m2_x, block_m2_y, 80, 40);
                ctx.lineWidth = 1;
                ctx.fillStyle = "white";
                ctx.font = "18px Verdana";
                ctx.fillText("m2", block_m2_x + 25, block_m2_y + 25);

                ctx.restore();
                i++;
            }
            else {
                clearInterval(t);
                setup();
            }
        }

        let pocitadlo = 0;
        let zdr = function () {
            pocitadlo++;
            if (pocitadlo === 3 ) {
                clearInterval(zdrzanie);
                if(oldValuesX1.length > 0) {
                    t = setInterval(loop, frameDelay);
                }
            }
        }
        let zdrzanie = setInterval(zdr, 1000);
    }

    /* WEBSOCKETS START */
    /*
     */
    let language = "SK";
    document.getElementById("language").addEventListener("change", () => {
        language = this.value;
    })

    const socket = new WebSocket('wss://site196.webte.fei.stuba.sk:9000');

    let user = {};      // current user
    let editors = [];   // list of active editors

    const loginButtonSK = document.getElementById("buttonName-SK");
    const loginButtonEN = document.getElementById("buttonName-EN");
    const logoutButtonSK = document.getElementById("buttonLogout-SK");
    const logoutButtonEN = document.getElementById("buttonLogout-EN");
    const loginFormSK = document.getElementById("inputFormName-SK");
    const loginFormEN = document.getElementById("inputFormName-EN");
    const loggedInFormSK = document.getElementById("loggedInForm-SK");
    const loggedInFormEN = document.getElementById("loggedInForm-EN");
    const loginInputSK = document.getElementById("nameInput-SK");
    const loginInputEN = document.getElementById("nameInput-EN");
    const loginStatusSK = document.getElementById("loginStatusText-SK");
    const loginStatusEN = document.getElementById("loginStatusText-EN");
    const editorListSK = document.getElementById("editorList-SK");
    const editorListEN = document.getElementById("editorList-EN");
    const loginStatusSpectatorMessageSK = "Sledujete užívateľa menom: ";
    const editorLoggedInMessageSK = "Prihlásený pod menom: ";
    const editorInvalidNameMessageSK = "Nesprávne zadané meno";
    const visitorNotLoggedInMessageSK = "Môžte sa prihlásiť, alebo sledovať prihláseného užívateľa";
    const loginStatusSpectatorMessageEN = "You are spectating user named: ";
    const editorLoggedInMessageEN = "Logged in with name: ";
    const editorInvalidNameMessageEN = "Name is invalid";
    const visitorNotLoggedInMessageEN = "You can log in, or spectate a logged in user";

    loggedInFormSK.style.display = "none";
    loggedInFormEN.style.display = "none";


    // ws message listener
    socket.addEventListener("message", msg => {
        const data = JSON.parse(msg.data);
        if ('event' in data) {
            if (data.event === 'new_user') {
                // this is the current user
                user.id = data.id;
                user.role = data.role;
            }
            else if (data.event === 'change_role'){
                if (data.role === 'editor') {
                    editors.push({'id': data.id, 'role': data.role, 'name': data.name})
                    updateEditors();
                } else if (data.role === 'visitor' && data.old_role === 'editor'){
                    // editor logged out -> remove him from editors list
                    editors.forEach(editor => {
                        if (editor.id === data.id){
                            let index = editors.indexOf(editor);
                            if (index !== -1) {
                                editors.splice(index, 1);
                            }
                        }
                    })
                    updateEditors();
                    // logout any spectators of this editor
                    if (user.role === 'spectator' && user.spectated === data.id){
                        if (language === "SK"){
                            logoutButtonSK.click();
                        }
                        else if (language === "EN"){
                            logoutButtonEN.click()
                        }
                    }
                }
            }
            else if (user.role === 'spectator'){
                if (data.event === 'edit_calc') {
                    // update numeric output
                    document.getElementById("inputSK").innerText = data.input;
                    document.getElementById("inputEN").innerText = data.input;
                    if (data.result) {
                        document.getElementById("outputSK").innerText = data.result;
                        document.getElementById("outputEN").innerText = data.result;
                    } else {
                        document.getElementById("outputSK").innerText = "Chýba príkaz.";
                        document.getElementById("outputEN").innerText = "Input missing.";
                    }
                }
                else if (data.event === 'reset_graph' && !isDrawing) {
                    document.getElementById("rSK").value = data.input;
                    document.getElementById("rEN").value = data.input;
                    resetData();

                }
                else if (data.event === 'edit_graph') {
                    index = 0;
                    x1 = data.x1;
                    x2 = data.x2;
                    addData();
                }
                else if (data.event === 'edit_anim') {
                    //TODO update animacie


                }
            }
        }
    })

    // force logout before exiting / refreshing page
    window.addEventListener("beforeunload", () => {
        if (user.role === 'editor' || user.role === 'spectator'){
            if (language === "SK"){
                logoutButtonSK.click();
            }
            else if (language === "EN"){
                logoutButtonEN.click()
            }
        }
    })

    // is name already taken?
    const findInEditors = (name) => {
        let flag = false;
        editors.forEach(editor => {
            if (editor.name === name){
                flag = true;
            }
        })
        return flag;
    }

    // update list of editors
    const updateEditors = () => {
        if (editors) {
            let childSK = editorListSK.lastElementChild;
            let childEN = editorListEN.lastElementChild;
            while (childSK) {
                editorListSK.removeChild(childSK);
                childSK = editorListSK.lastElementChild;
            }
            while (childEN) {
                editorListEN.removeChild(childEN);
                childEN = editorListEN.lastElementChild;
            }
        }
        editors.forEach(editor => {
            // SK
            let liSK = document.createElement("li");
            liSK.innerText = editor.name;
            liSK.style.cursor = "pointer";
            // listener for each editor item -> switch to spectate the editor
            liSK.addEventListener("click", () => {
                let oldRole = user.role;
                user.role = 'spectator';
                user.spectated = editor.id;
                socket.send(JSON.stringify({'event': 'change_role', 'role': user.role, 'old_role': oldRole,
                    'spectated': user.spectated}));
                loginFormSK.style.display = 'none';
                loggedInFormSK.style.display = 'block';
                editorListSK.style.display = 'none';
                loginStatusSK.innerText = loginStatusSpectatorMessageSK+editor.name;
                loginFormEN.style.display = 'none';
                loggedInFormEN.style.display = 'block';
                editorListEN.style.display = 'none';
                loginStatusEN.innerText = loginStatusSpectatorMessageEN+editor.name;
                // spectator cannot edit -> disable all buttons and inputs
                inputButtonSK.disabled = true;
                inputButtonEN.disabled = true;
                inputRbuttonSK.disabled = true;
                inputRbuttonEN.disabled = true;
                emailButtonSK.disabled = true;
                emailButtonEN.disabled = true;
                document.getElementById("inputSK").disabled = true;
                document.getElementById("inputEN").disabled = true;
                document.getElementById("rSK").disabled = true;
                document.getElementById("rEN").disabled = true;
            })
            // EN
            let liEN = document.createElement("li");
            liEN.innerText = editor.name;
            liEN.style.cursor = "pointer";
            // listener for each editor item -> switch to spectate the editor
            liEN.addEventListener("click", () => {
                let oldRole = user.role;
                user.role = 'spectator';
                user.spectated = editor.id;
                socket.send(JSON.stringify({'event': 'change_role', 'role': user.role, 'old_role': oldRole,
                    'spectated': user.spectated}));
                loginFormSK.style.display = 'none';
                loggedInFormSK.style.display = 'block';
                editorListSK.style.display = 'none';
                loginStatusSK.innerText = loginStatusSpectatorMessageSK+editor.name;
                loginFormEN.style.display = 'none';
                loggedInFormEN.style.display = 'block';
                editorListEN.style.display = 'none';
                loginStatusEN.innerText = loginStatusSpectatorMessageEN+editor.name;
                // spectator cannot edit -> disable all buttons and inputs
                inputButtonSK.disabled = true;
                inputButtonEN.disabled = true;
                inputRbuttonSK.disabled = true;
                inputRbuttonEN.disabled = true;
                emailButtonSK.disabled = true;
                emailButtonEN.disabled = true;
                document.getElementById("inputSK").disabled = true;
                document.getElementById("inputEN").disabled = true;
                document.getElementById("rSK").disabled = true;
                document.getElementById("rEN").disabled = true;
            })
            editorListSK.appendChild(liSK);
            editorListEN.appendChild(liEN);
        })
    }

    // editor login SK
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
            loginFormEN.style.display = 'none';
            loggedInFormEN.style.display = 'block';
            editorListEN.style.display = 'none';
            loginStatusSK.innerText = editorLoggedInMessageSK+name;
            loginStatusEN.innerText = editorLoggedInMessageEN+name;
        } else {
            loginStatusSK.innerText = editorInvalidNameMessageSK;
            loginStatusEN.innerText = editorInvalidNameMessageEN;
        }
    })
    // editor login EN
    loginButtonEN.addEventListener("click", () =>{
        const data = new FormData(loginFormEN);
        let name = data.get('name');
        if (name && !findInEditors(name)){
            let oldRole = user.role;
            user.role = 'editor';
            user.name = name;
            socket.send(JSON.stringify({'event': 'change_role', 'role': user.role, 'name': name, 'old_role': oldRole}));
            loginFormSK.style.display = 'none';
            loggedInFormSK.style.display = 'block';
            editorListSK.style.display = 'none';
            loginFormEN.style.display = 'none';
            loggedInFormEN.style.display = 'block';
            editorListEN.style.display = 'none';
            loginStatusSK.innerText = editorLoggedInMessageSK+name;
            loginStatusEN.innerText = editorLoggedInMessageEN+name;
        } else {
            loginStatusSK.innerText = editorInvalidNameMessageSK;
            loginStatusEN.innerText = editorInvalidNameMessageEN;
        }
    })

    // editor or spectator logout SK
    logoutButtonSK.addEventListener("click", () =>{
        let oldRole = user.role;
        user.role = 'visitor';
        user.name = undefined;
        user.spectated = undefined;
        socket.send(JSON.stringify({'event': 'change_role', 'role': user.role, 'name': user.name, 'old_role': oldRole}));
        loggedInFormSK.style.display = 'none';
        loginFormSK.style.display = 'block';
        editorListSK.style.display = 'block';
        loggedInFormEN.style.display = 'none';
        loginFormEN.style.display = 'block';
        editorListEN.style.display = 'block';
        loginStatusSK.innerText = visitorNotLoggedInMessageSK;
        loginStatusEN.innerText = visitorNotLoggedInMessageEN;
        // enable all buttons and inputs
        inputButtonSK.disabled = false;
        inputButtonEN.disabled = false;
        inputRbuttonSK.disabled = false;
        inputRbuttonEN.disabled = false;
        emailButtonSK.disabled = false;
        emailButtonEN.disabled = false;
        document.getElementById("inputSK").disabled = false;
        document.getElementById("inputEN").disabled = false;
        document.getElementById("rSK").disabled = false;
        document.getElementById("rEN").disabled = false;
    })
    // editor or spectator logout EN
    logoutButtonEN.addEventListener("click", () =>{
        let oldRole = user.role;
        user.role = 'visitor';
        user.name = undefined;
        user.spectated = undefined;
        socket.send(JSON.stringify({'event': 'change_role', 'role': user.role, 'name': user.name, 'old_role': oldRole}));
        loggedInFormSK.style.display = 'none';
        loginFormSK.style.display = 'block';
        editorListSK.style.display = 'block';
        loggedInFormEN.style.display = 'none';
        loginFormEN.style.display = 'block';
        editorListEN.style.display = 'block';
        loginStatusSK.innerText = visitorNotLoggedInMessageSK;
        loginStatusEN.innerText = visitorNotLoggedInMessageEN;
        // enable all buttons and inputs
        inputButtonSK.disabled = false;
        inputButtonEN.disabled = false;
        inputRbuttonSK.disabled = false;
        inputRbuttonEN.disabled = false;
        emailButtonSK.disabled = false;
        emailButtonEN.disabled = false;
        document.getElementById("inputSK").disabled = false;
        document.getElementById("inputEN").disabled = false;
        document.getElementById("rSK").disabled = false;
        document.getElementById("rEN").disabled = false;
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
                        socket.send(JSON.stringify({'event': 'edit_calc', 'input': data.get('input'),
                            'result': result["output"]}));
                    })
                }
                else{
                    document.getElementById("outputSK").innerText = "Chýba príkaz.";
                    document.getElementById("outputEN").innerText = "Input missing.";
                    socket.send(JSON.stringify({'event': 'edit_calc', 'result': false}));
                }

            })

    })

    const inputRbuttonSK = document.getElementById("buttonR-SK");
    const inputRbuttonEN = document.getElementById("buttonR-EN");
    let timer;
    let index = 0;
    let r;

    let oldValuesX1 = [r];
    let oldValuesX2 = [r];
    let oldLabels = [0];

    let config = {
        type: 'line',
        data: {
            labels : oldLabels,
            datasets: [
                {
                    label: "X1",
                    data: oldValuesX1,
                    borderColor: 'rgb(194,20,51)',
                    // tension: 0.5,
                },
                {
                    label: "X2",
                    data: oldValuesX2,
                    borderColor: 'rgb(0,73,255)',
                    // tension: 0.5,
                }
            ]
        },
        options: {}
    }

    let graph = new Chart("graphCanvas", config);

    inputRbuttonSK.addEventListener("click", () => {
        if(isDrawing) {return;}
        resetData();
        const form = document.getElementById("inputFormR-SK");
        const data = new FormData(form);
        r = data.get('r');
        socket.send(JSON.stringify({
            'event': 'reset_graph', 'input': r
        }));
        fetch("api.php?api_key="+key+"&r="+r, {method: "GET", headers:{'content-type': 'application/json'}})
            .then(response => response.json())
            .then(result => {
                inputRbuttonSK.disabled = true;
                inputRbuttonEN.disabled = true;
                console.log(result);
                index = 0;
                x1 = result["x1"];
                x2 = result["x2"];
                addData();
               socket.send(JSON.stringify({
                   'event': 'edit_graph',
                   'x1': result["x1"], 'x2': result["x2"]
               }));
            })
    })

    inputRbuttonEN.addEventListener("click", () => {
        if(isDrawing) {return;}
        resetData();
        const form = document.getElementById("inputFormR-EN");
        const data = new FormData(form);
        r = data.get('r');
        fetch("api.php?api_key="+key+"&r="+r, {method: "GET", headers:{'content-type': 'application/json'}})
            .then(response => response.json())
            .then(result => {
                inputRbuttonEN.disabled = true;
                inputRbuttonSK.disabled = true;
                console.log(result);
                index = 0;
                x1 = result["x1"];
                x2 = result["x2"];
                addData();
                socket.send(JSON.stringify({
                    'event': 'edit_graph', 'input': r,
                    'x1': result["x1"], 'x2': result["x2"]
                }));
            })
    })

    let newDataX1;
    let newDataX2;
    let isDrawing = false;

    function addData() {
        if(isDrawing === false) {
            timer = setInterval(() => {
                isDrawing = true;
                newDataX1 = x1[index];
                newDataX2 = x2[index];
                oldValuesX1.push(newDataX1);
                oldValuesX2.push(newDataX2)
                oldLabels.push(index);
                graph.update();
                index++;
                // console.log(index, x1.length);
                // console.log(oldValuesX1);
                if (index > x1.length) {
                    clearInterval(timer);
                    isDrawing = false;
                    inputRbuttonSK.disabled = false;
                    inputRbuttonEN.disabled = false;
                }
            }, 5)
        }
    }

    function resetData(){
        // graph.destroy();
        index = 0;
        oldValuesX1 = [];
        oldValuesX2 = [];
        oldLabels = [];
        graph.data.datasets[0].data = oldValuesX1;
        graph.data.datasets[1].data = oldValuesX2;
        graph.data.labels = oldLabels;
        graph.update();
    }


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
