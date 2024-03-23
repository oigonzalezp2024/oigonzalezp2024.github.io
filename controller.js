function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if(pair[0] == variable) {
            return pair[1];
        }
    }
    return false;
 }

document.addEventListener("DOMContentLoaded",() => {

    const tipoDocumento  = document.getElementById("tipoDocumento"); 
    const nacionalidad = document.getElementById("nacionalidad");
    const numeroDocumento = document.getElementById("numeroDocumento");
    const nombre = document.getElementById("nombre");
    const primerApellido = document.getElementById("primerApellido");
    const segundoApellido = document.getElementById("segundoApellido");
    const telefonoMovil = document.getElementById("telefonoMovil");
    const email = document.getElementById("email");
    const restr1 = document.getElementById("restr1");
    const restr2 = document.getElementById("restr2");
    const restr3 = document.getElementById("restr3"); 

    tipoDocumento.value = getQueryVariable("tipoDocumento");
    nacionalidad.value = getQueryVariable("nacionalidad");
    numeroDocumento.value = getQueryVariable("numeroDocumento");
    nombre.value = getQueryVariable("nombre");
    primerApellido.value = getQueryVariable("primerApellido");
    segundoApellido.value = getQueryVariable("segundoApellido");
    telefonoMovil.value = getQueryVariable("telefonoMovil");
    email.value = getQueryVariable("email");
    restr1.value = getQueryVariable("restr1");
    restr2.value = getQueryVariable("restr2");
    restr3.value = getQueryVariable("restr3");

    let data = window.location.href.replace("https://oigonzalezp2024.github.io/naturgy?",""); // MAIN
    //let data = window.location.href.replace("http://127.0.0.1:5500/naturgy.html?",""); // DEVELOP
    
    for(let i=0; i < data.length; i++) {
        data = data.replace("=",":%20").replace("&",",%0A");
    }
    
    data = data.replace("tipoDocumento","*tipoDocumento*");
    data = data.replace("nacionalidad","*nacionalidad*");
    data = data.replace("numeroDocumento","*numeroDocumento*");
    data = data.replace("nombre","*nombre*");
    data = data.replace("primerApellido","*primerApellido*");
    data = data.replace("segundoApellido","*segundoApellido*");
    data = data.replace("telefonoMovil","*telefonoMovil*");
    data = data.replace("email","*email*");

    if(tipoDocumento.value !== false && tipoDocumento.value !=="" && tipoDocumento.value !=="false"){
        window.location.href = "https://api.whatsapp.com/send?phone=34637232468&text=Hola%20Oscar,%20necesito%20asesoría.%0A%20Por%20favor%20consulta%20si%20puedo%20pasarme%20a%20Naturgy,%0A%20Mis%20datos%20son:%0A"+data;
    }else if(tipoDocumento.value == "false"){
        tipoDocumento.value = "";
        nacionalidad.value = "";
        numeroDocumento.value = "";
        nombre.value = "";
        primerApellido.value = "";
        segundoApellido.value = "";
        telefonoMovil.value = "";
        email.value = "";
        restr1.value = "";
        restr2.value = "";
        restr3.value = "";
    }
});
