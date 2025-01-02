// ------------------------------------------------------------------------------------------

//                               Custommize printing here

//-------------------------------------------------------------------------------------------

$(document).ready(function(){

    var option = {
        importCSS: false,
        importStyle: false,
        loadCSS: ["css/index.css","css/printing.css",
        "AdminLTE-master/dist/css/AdminLTE.min.css","css/jquery-ui.css",
        "css/dataTables.jqueryui.min.css","css/mdb.min.css",
        "AdminLTE-master/dist/css/skins/skin-blue.min.css"],
        base: "https://gypman-tech.com",
    }
    $('.printtagwipline1a').click(function(){
        $('.container-fluid div.print-output').printThis(option);
    });
});

$(document).ready(function(){

    var option = {
        importCSS: false,
        importStyle: false,
        loadCSS: ["css/index.css","css/printing.css",
        "AdminLTE-master/dist/css/AdminLTE.min.css","css/jquery-ui.css",
        "css/dataTables.jqueryui.min.css","css/mdb.min.css",
        "AdminLTE-master/dist/css/skins/skin-blue.min.css"],
        base: "https://gypman-tech.com",
    }
    $('.tagsumprint').click(function(){
        $('.container-fluid div.print-output').printThis(option);
    });
});

$(document).ready(function(){
    var option = {
        importStyle: true,
        importCSS: true,
        loadCSS: ["css/index.css","css/printing.css",
        "AdminLTE-master/bower_components/bootstrap/dist/css/bootstrap.css",
        "AdminLTE-master/dist/css/AdminLTE.min.css","css/jquery-ui.css",
        "css/dataTables.jqueryui.min.css","css/mdb.min.css",
        "AdminLTE-master/dist/css/skins/skin-blue.min.css"],
        base: "http://localhost/gypman-tech",
    }
    $('#printbol').click(function(){
        $('.page-a4-layout').printThis(option);
    });
});

// Print PDF ปุ่มน่ารัก
// $(document).ready(function(){
//     var option = {
//         importStyle: true,
//         importCSS: true,
//         loadCSS: ["css/index.css","css/printing.css",
//         "AdminLTE-master/bower_components/bootstrap/dist/css/bootstrap.css",
//         "AdminLTE-master/dist/css/AdminLTE.min.css","css/jquery-ui.css",
//         "css/dataTables.jqueryui.min.css","css/mdb.min.css",
//         "AdminLTE-master/dist/css/skins/skin-blue.min.css"],
//         base: "http://localhost/gypman-tech",
//     }
//     $('#printreportcline').click(function(){
//         $('.container-fluid-cline div.print-output-cline').printThis(option);
//     });
// });

$(document).ready(function(){

    var option = {
        importCSS: false,
        importStyle: false,
        loadCSS: ["css/index.css","css/printing.css",
        "AdminLTE-master/dist/css/AdminLTE.min.css","css/jquery-ui.css",
        "css/dataTables.jqueryui.min.css","css/mdb.min.css",
        "AdminLTE-master/dist/css/skins/skin-blue.min.css"],
        base: "https://gypman-tech.com",
    }
    $('.printreportcline').click(function(){
        $('.container-fluid-cline div.print-output-cline').printThis(option);
    });
});


$(document).ready(function(){

    var option = {
        importCSS: false,
        importStyle: false,
        loadCSS: [
            "css/index.css","css/printing.css",
        "AdminLTE-master/dist/css/AdminLTE.min.css","css/jquery-ui.css",
        "css/dataTables.jqueryui.min.css","css/mdb.min.css",
        "AdminLTE-master/dist/css/skins/skin-blue.min.css"
    ],
        base: "https://gypman-tech.com",
    }
    $('.printreportact').click(function(){
        $('.container-fluid div.print-output').printThis(option);
    });
});
