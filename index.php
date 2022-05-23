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
    <p id="loginStatusText-SK">Nie ste prihlásený</p>
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
        <input id="r" name="r" type="number" step="0.01">
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
    </form>
    <form id="loggedInForm-EN">
        <button type="button" id="buttonLogout-EN">Log out</button>
    </form>
    <p id="loginStatusText-EN">You are not logged in</p>
    <ul id="editorList-EN"></ul>

</div>

<canvas id="graphCanvas" style="width:100%;max-width:700px"></canvas>
<canvas id="animationCanvas"></canvas>

<script>
    let language = "SK";
    document.getElementById("language").addEventListener("change", () => {
        language = this.value;
        setLanguagesWs();
        if (user.role === "visitor"){
            loggedInForm.style.display = 'none';
        }
    })
    /* WEBSOCKETS START */
    /*
     */
    const socket = new WebSocket('wss://site196.webte.fei.stuba.sk:9000');

    let user = {};      // current user
    let editors = [];   // list of active editors

    let loginForm;
    let loggedInForm;
    let loginButton;
    let loginInput;
    let loginStatus;
    let editorList;
    let logoutButton;
    let loginStatusSpectatorMessage;
    let editorLoggedInMessage;
    let editorInvalidNameMessage;
    let visitorNotLoggedInMessage;

    // set language for inputs buttons and other
    const setLanguagesWs = () => {
        if (language === 'SK') {
            loginForm = document.getElementById("inputFormName-SK");
            loggedInForm = document.getElementById("loggedInForm-SK");
            loginButton = document.getElementById("buttonName-SK");
            loginInput = document.getElementById("nameInput-SK");
            loginStatus = document.getElementById("loginStatusText-SK");
            editorList = document.getElementById("editorList-SK");
            logoutButton = document.getElementById("buttonLogout-SK");
            loginStatusSpectatorMessage = "Sledujete užívateľa menom: ";
            editorLoggedInMessage = "Prihlásený pod menom: ";
            editorInvalidNameMessage = "Nesprávne zadané meno";
            visitorNotLoggedInMessage = "Môžte sa prihlásiť, alebo sledovať prihláseného užívateľa";
        }
        else if (language === 'EN') {
            loginForm = document.getElementById("inputFormName-EN");
            loggedInForm = document.getElementById("loggedInForm-EN");
            loginButton = document.getElementById("buttonName-EN");
            loginInput = document.getElementById("nameInput-EN");
            loginStatus = document.getElementById("loginStatusText-EN");
            editorList = document.getElementById("editorList-EN");
            logoutButton = document.getElementById("buttonLogout-EN");
            loginStatusSpectatorMessage = "You are spectating user named: ";
            editorLoggedInMessage = "Logged in with name: ";
            editorInvalidNameMessage = "Name is invalid";
            visitorNotLoggedInMessage = "You can log in, or spectate a logged in user";
        }
    }

    setLanguagesWs();
    loggedInForm.style.display = 'none';

    /*const loginFormSK = document.getElementById("inputFormName-SK");
    const loggedInFormSK = document.getElementById("loggedInForm-SK");
    const loginButtonSK = document.getElementById("buttonName-SK");
    const loginInputSK = document.getElementById("nameInput-SK");
    const loginStatusSK = document.getElementById("loginStatusText-SK");
    const editorListSK = document.getElementById("editorList-SK");
    const logoutButtonSK = document.getElementById("buttonLogout-SK");*/

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
                        logoutButton.click();
                    }
                }
            }
            else if (user.role === 'spectator'){
                if (data.event === 'edit_calc') {
                    // update numeric output
                    document.getElementById("input").innerText = data.input;
                    if (data.result) {
                        document.getElementById("outputSK").innerText = data.result;
                        document.getElementById("outputEN").innerText = data.result;
                    } else {
                        document.getElementById("outputSK").innerText = "Chýba príkaz.";
                        document.getElementById("outputEN").innerText = "Input missing.";
                    }
                }
                else if (data.event === 'edit_graph') {
                    // TODO resetovanie, opravit vykreslovanie nasledovnych po prvom
                    document.getElementById("r").innerText = data.input;
                    x1 = data.x1;
                    x2 = data.x2;
                    timer = setInterval(addData, 10);
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
            logoutButton.click();
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
            let child = editorList.lastElementChild;
            while (child) {
                editorList.removeChild(child);
                child = editorList.lastElementChild;
            }
        }
        editors.forEach(editor => {
            let li = document.createElement("li");
            li.innerText = editor.name;
            li.style.cursor = "pointer";
            // listener for each editor item -> switch to spectate the editor
            li.addEventListener("click", () => {
                let oldRole = user.role;
                user.role = 'spectator';
                user.spectated = editor.id;
                socket.send(JSON.stringify({'event': 'change_role', 'role': user.role, 'old_role': oldRole,
                    'spectated': user.spectated}));
                loginForm.style.display = 'none';
                loggedInForm.style.display = 'block';
                editorList.style.display = 'none';
                loginStatus.innerText = loginStatusSpectatorMessage+editor.name;
                // spectator cannot edit -> disable all buttons and inputs
                inputButtonSK.disabled = true;
                inputButtonEN.disabled = true;
                inputRbuttonSK.disabled = true;
                inputRbuttonEN.disabled = true;
                emailButtonSK.disabled = true;
                emailButtonEN.disabled = true;
                document.getElementById("input").disabled = true;
                document.getElementById("r").disabled = true;
            })
            editorList.appendChild(li);
        })
    }

    // editor login
    loginButton.addEventListener("click", () =>{
        const data = new FormData(loginForm);
        let name = data.get('name');
        if (name && !findInEditors(name)){
            let oldRole = user.role;
            user.role = 'editor';
            user.name = name;
            socket.send(JSON.stringify({'event': 'change_role', 'role': user.role, 'name': name, 'old_role': oldRole}));
            loginForm.style.display = 'none';
            loggedInForm.style.display = 'block';
            editorList.style.display = 'none';
            loginStatus.innerText = editorLoggedInMessage+name;
        } else {
            loginStatus.innerText = editorInvalidNameMessage;
        }
    })

    // editor or spectator logout
    logoutButton.addEventListener("click", () =>{
        let oldRole = user.role;
        user.role = 'visitor';
        user.name = undefined;
        user.spectated = undefined;
        socket.send(JSON.stringify({'event': 'change_role', 'role': user.role, 'name': user.name, 'old_role': oldRole}));
        loggedInForm.style.display = 'none';
        loginForm.style.display = 'block';
        editorList.style.display = 'block';
        loginStatus.innerText = visitorNotLoggedInMessage;
        // enable all buttons and inputs
        inputButtonSK.disabled = false;
        inputButtonEN.disabled = false;
        inputRbuttonSK.disabled = false;
        inputRbuttonEN.disabled = false;
        emailButtonSK.disabled = false;
        emailButtonEN.disabled = false;
        document.getElementById("input").disabled = false;
        document.getElementById("r").disabled = false;
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
                   'event': 'edit_graph', 'input': r,
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
                console.log(index, x1.length);
                console.log(oldValuesX1);
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
