
let lang = "SK";

function selectLangAPI(value){

    lang = value;
    let nadpis = document.getElementById("lang");
    let divSK = document.getElementById("contentSK");
    let divEN = document.getElementById("contentEN");
    let main = document.getElementById("main");
    let desc = document.getElementById("desc");
    let doc = document.getElementById("doc");

    if(value === "SK"){
        nadpis.innerHTML = "Tlmič automobilu";
        divSK.style.display = "block";
        divEN.style.display = "none";
        main.innerHTML = "Hlavná stránka";
        desc.innerHTML = "Popis API";
        doc.innerHTML = "Dokumentácia";

    }
    if(value === "EN"){
        nadpis.innerHTML = "Car suspension";
        divEN.style.display = "block";
        divSK.style.display = "none";
        main.innerHTML = "Main site";
        desc.innerHTML = "API description";
        doc.innerHTML = "Documentation";
    }
}

function selectLangDOC(value){

    lang = value;
    let nadpis = document.getElementById("lang");
    let divSK = document.getElementById("contentSK");
    let divEN = document.getElementById("contentEN");
    let main = document.getElementById("main");
    let desc = document.getElementById("desc");
    let doc = document.getElementById("doc");

    if(value === "SK"){
        nadpis.innerHTML = "Dokumentácia";
        divSK.style.display = "block";
        divEN.style.display = "none";
        main.innerHTML = "Hlavná stránka";
        desc.innerHTML = "Popis API";
        doc.innerHTML = "Dokumentácia";

    }
    if(value === "EN"){
        nadpis.innerHTML = "Documentation";
        divEN.style.display = "block";
        divSK.style.display = "none";
        main.innerHTML = "Main site";
        desc.innerHTML = "API description";
        doc.innerHTML = "Documentation";
    }
}

function pdfFromHTML() {
    let pdf = new jsPDF('p', 'pt', 'letter');
    // let source = $('#contentEN')[0];
    let source;
    if(lang === "SK") {
        source = $('#contentSK')[0];
    }
    else{
        source = $('#contentEN')[0];
    }
    let specialElementHandlers = {
        '#bypassme': function (element, renderer) {
            return true
        }
    };
    let margins = {
        top: 80,
        bottom: 60,
        left: 40,
        width: 522
    };
    pdf.fromHTML(
        source,
        margins.left,
        margins.top, {
            'width': margins.width,
            'elementHandlers': specialElementHandlers
        },

        function (dispose) {
            pdf.save('api.pdf');
        }, margins
    );
}
