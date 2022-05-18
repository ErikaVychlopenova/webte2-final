
function pdfFromHTML() {
    let pdf = new jsPDF('p', 'pt', 'letter');
    let source = $('#content')[0];

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
