
function checkChoiceValue(){
    let graph = document.getElementById("checkGraph");
    let anim = document.getElementById("checkAnim");

    let graphCanvas = document.getElementById("graphCanvas");
    let animCanvas = document.getElementById("animationCanvas");

    if(graph.checked === true){ graphCanvas.style.display = "block";}
    else { graphCanvas.style.display = "none";}

    if(anim.checked === true){animCanvas.style.display = "block";}
    else { animCanvas.style.display = "none";}
}

function selectLang(value){
    let nadpis = document.getElementById("lang");
    let divSK = document.getElementById("langSK");
    let divEN = document.getElementById("langEN");
    let main = document.getElementById("main");
    let desc = document.getElementById("desc");

    if(value === "SK"){
        nadpis.innerHTML = "Tlmič automobilu";
        main.innerHTML = "Hlavná stránka";
        desc.innerHTML = "Popis API";
        divSK.style.display = "block";
        divEN.style.display = "none";

    }
    else if(value === "EN"){
        nadpis.innerHTML = "Car suspension";
        main.innerHTML = "Main site";
        desc.innerHTML = "API description";
        divSK.style.display = "none";
        divEN.style.display = "block";
    }
}

inputButtonEN.addEventListener("click", () =>{
    const form = document.getElementById("inputFormEN");
    const data = new FormData(form);
    let json = jsonify(data);
    const request = new Request("api.php?api_key="+key,
        {
            method: "POST",
            body : json
        });
    fetch(request)
        .then(response => {
            if (response.status === 200) {
                response.json().then(result => {
                    document.getElementById("outputSK").innerText = result["output"];
                    document.getElementById("outputEN").innerText = result["output"];
                })
            } else {
                document.getElementById("outputSK").innerText = "Chýba príkaz.";
                document.getElementById("outputEN").innerText = "Input missing.";
            }
        })
})

inputRbuttonEN.addEventListener("click", () => {
    const form = document.getElementById("inputFormR-EN");
    const data = new FormData(form);

    fetch("api.php?api_key="+key+"&r="+data.get('r'), {method: "GET"})
        .then(response => {
            if(response.status === 200){
                response.json().then(result => {
                    console.log(result);
                })
            }
        })
})

emailButtonEN.addEventListener("click", () => {
    fetch("api.php?api_key="+key+"&email="+email, {method: "GET"})
        .then(response => {
            response.json().then(result => {
                console.log(result);
                document.getElementById("emailResponseSK").innerText = result["message"];
                document.getElementById("emailResponseEN").innerText = result["message"];
            })
        })
})









