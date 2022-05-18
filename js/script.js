
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


