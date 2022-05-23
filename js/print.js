
let lang;

function selectLangAPI(value){

    lang = value;
    let divSK = document.getElementById("contentSK");
    let divEN = document.getElementById("contentEN");

    if(value === "SK"){
        divSK.style.display = "block";
        divEN.style.display = "none";

    }
    if(value === "EN"){
        divEN.style.display = "block";
        divSK.style.display = "none";
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
