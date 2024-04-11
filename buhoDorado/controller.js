function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
            let data = pair[1];
            for (let i = 0; i < data.length; i++) {
                data = data.replace("%2B", " ");
                data =  data.replace("%20", " ");
                data =  data.replace("%40", "@");
            }
            for (let i = 0; i < data.length; i++) {
                data = data.replace("%C3%B1", "ñ");
                data = data.replace("%C3%91", "Ñ");
            }
            return data.trim();
        }
    }
    return false;
}

function myFunction() {
    var x = document.getElementById("myLinks");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}

function desapareceMercadeo() {
    const element = document.getElementsByClassName("logotipo");
    const formClient = document.getElementsByClassName("formClient");
    const mercadeo = document.getElementsByClassName("mercadeo");
    mercadeo[0].style.display = "none";
    formClient[0].style.display = "none"
    for (let i of element) {
        i.style.display = "none";
    }
}

document.addEventListener("DOMContentLoaded", () => {

    const tipoDocumento = document.getElementById("tipoDocumento");
    const nacionalidad = document.getElementById("nacionalidad");
    const numeroDocumento = document.getElementById("numeroDocumento");
    const nombre = document.getElementById("nombre");
    const primerApellido = document.getElementById("primerApellido");
    const segundoApellido = document.getElementById("segundoApellido");
    const telefonoMovil = document.getElementById("telefonoMovil");
    const email = document.getElementById("email");
    const restr1 = document.getElementById("restr1");

    const tipoDocumentor = document.getElementById("tipoDocumentor");
    const nacionalidadr = document.getElementById("nacionalidadr");
    const numeroDocumentor = document.getElementById("numeroDocumentor");
    const nombrer = document.getElementById("nombrer");
    const primerApellidor = document.getElementById("primerApellidor");
    const segundoApellidor = document.getElementById("segundoApellidor");
    const telefonoMovilr = document.getElementById("telefonoMovilr");
    const emailr = document.getElementById("emailr");

    const destinatario = document.getElementById("destinatario");
    const remitente = document.getElementById("remitente");
    const btnMenu = document.getElementById("btnMenu");

    tipoDocumento.value = getQueryVariable("tipoDocumento");
    nacionalidad.value = getQueryVariable("nacionalidad");
    numeroDocumento.value = getQueryVariable("numeroDocumento");
    nombre.value = getQueryVariable("nombre");
    primerApellido.value = getQueryVariable("primerApellido");
    segundoApellido.value = getQueryVariable("segundoApellido");
    telefonoMovil.value = getQueryVariable("telefonoMovil");
    email.value = getQueryVariable("email");
    restr1.value = getQueryVariable("restr1");

    tipoDocumentor.value = getQueryVariable("tipoDocumentor");
    nacionalidadr.value = getQueryVariable("nacionalidadr");
    numeroDocumentor.value = getQueryVariable("numeroDocumentor");
    nombrer.value = getQueryVariable("nombrer");
    primerApellidor.value = getQueryVariable("primerApellidor");
    segundoApellidor.value = getQueryVariable("segundoApellidor");
    telefonoMovilr.value = getQueryVariable("telefonoMovilr");
    emailr.value = getQueryVariable("emailr");

    btnMenu.addEventListener("click", () => {
        myFunction();
    });

    if (tipoDocumento.value == "false") {
        tipoDocumento.value = "";
        nacionalidad.value = "";
        numeroDocumento.value = "";
        nombre.value = "";
        primerApellido.value = "";
        segundoApellido.value = "";
        telefonoMovil.value = "";
        email.value = "";
        restr1.value = "";
        remitente.style.display = "block";
    } else if (tipoDocumentor.value == "false") {
        desapareceMercadeo();
        tipoDocumentor.value = "";
        nacionalidadr.value = "";
        numeroDocumentor.value = "";
        nombrer.value = "";
        primerApellidor.value = "";
        segundoApellidor.value = "";
        telefonoMovilr.value = "";
        emailr.value = "";

        emailr.setAttribute("type","email")

        remitente.style.display = "none";
        destinatario.style.display = "block";
    } else {

        //let data = window.location.href.replace("http://127.0.0.1:5500/buhoDorado/index.html?", ""); // DEVELOP
        //data = data.replace("http://127.0.0.1:5500/buhoDorado/?", "");                               // DEVELOP

        let data = window.location.href.replace("https://oigonzalezp2024.github.io/buhoDorado/index.html?", ""); // MAIN
        data = data.replace("https://oigonzalezp2024.github.io/buhoDorado/?", "");                               // MAIN

        for (let i = 0; i < data.length; i++) {
            data = data.replace("=", ":%20").replace("&", "%0A");
        }

        for (let i = 0; i < data.length; i++) {
            data = data.replace("%2B", "%20").replace("%20%20", "%20");
        }

        for (let i = 0; i < data.length; i++) {
            data = data.replace("%40", "@");
        }

        for (let i = 0; i < data.length; i++) {
            data = data.replace("%C3%B1", "ñ");
            data = data.replace("%C3%91", "Ñ");
        }

        data = data.replace("tipoDocumento", "%0A*Remitente:*%0A*Tipo%20de%20documento*");
        data = data.replace("nacionalidad", "*Nacionalidad*");
        data = data.replace("numeroDocumento", "*Número%20de%20documento*");
        data = data.replace("nombre", "*Nombre*");
        data = data.replace("primerApellido", "*Primer%20apellido*");
        data = data.replace("segundoApellido", "*Segundo%20apellido*");
        data = data.replace("telefonoMovil", "*Teléfono%20móvil*");
        data = data.replace("email", "*Correo*");

        data = data.replace("tipoDocumentor", "%0A*Destinatario:*%0A*Tipo%20de%20documento*");
        data = data.replace("nacionalidadr", "*Nacionalidad*");
        data = data.replace("numeroDocumentor", "*Numero%20de%20documento*");
        data = data.replace("nombrer", "*Nombre*");
        data = data.replace("primerApellidor", "*Primer%20apellido*");
        data = data.replace("segundoApellidor", "*Segundo%20apellido*");
        data = data.replace("telefonoMovilr", "*Teléfono%20móvil*");
        data = data.replace("emailr", "*Correo*");

        window.location.href = "https://api.whatsapp.com/send?phone=34637232468&text=Hola%20Manuel,%20Vi%20tu%20página%20Necesito%20hacer%20una%20transferencia.%0A" + data;
    }
});
