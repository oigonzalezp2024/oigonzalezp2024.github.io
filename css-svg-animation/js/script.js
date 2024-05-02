
import {
    move1,
    move2,
    move3,
    move4,
    move5,
    move9, title, barraLateral,
    move6, nodo, decoracion
} from "./interfaceMatrix.js"

import { proporcionAurea } from "./proporcionAurea.js"

function funTexto(p, x, y) {
    var conten = document.getElementsByTagName("svg")
    var text = document.getElementsByTagName("text")
    var data = [
        {
            tag: "text",
            x: "86",
            y: "391",
            textNode: "Cúcuta-Colombia"
        },
        {
            tag: "text",
            x: "86",
            y: "410",
            textNode: "2024-04-17"
        }
    ]

    const cadena = `<text x="86" y="70">Developer</text>
                 <text x="86" y="131">Status: Available</text>
                 <text x="86" y="184">Óscar González</text>
                 <text x="86" y="200">Fullstack developer</text>
                 <text x="86" y="262">ETL development</text>
                 <text x="86" y="311">Python, C, C++,</text>
                 <text x="86" y="330">Javascript y PHP</text>
                 <text x="86" y="391">Cúcuta-Colombia</text>
                 <text x="86" y="410">2024-04-17</text>`
    conten[0].innerHTML = conten[0].innerHTML + cadena;

    for (let i of data) {
        let tag = document.createElementNS("http://www.w3.org/1999/xhtml",i['tag'])
        let textNode = document.createTextNode(i['textNode'])
        tag.setAttribute("x", i['x'])
        tag.setAttribute("y", i['y'])
        tag.setAttribute("style", `fill:#ffffff90; stroke:#ff000090;`)
        tag.appendChild(textNode)
        document.querySelector("#opera").appendChild(tag)      
    }

    for (let i = 0; text.length > i; i++) {
        text[i].style.fill = "#ffffff90"
        text[i].style.stroke = "#ff000090"
    }
}

var cont = document.getElementById("opera")
var cadena = ""
for (let i = 0; 379 > i; i = i + 1) {
    cadena += "<rect></rect>";
}
cont.innerHTML = cadena;



setTimeout(function () { move1(0, 0) }, 3000, "javascript");
setTimeout(function () { move1(5, 5) }, 3200, "javascript");
setTimeout(function () { move1(20, 20) }, 3300, "javascript");
setTimeout(function () { move1(40, 40) }, 3400, "javascript");

let x = 40
let y = 40

setTimeout(function () { move1(x, y) }, 3500, "javascript");
setTimeout(function () { move2(x, y) }, 3600, "javascript");
setTimeout(function () { move3(x, y) }, 3700, "javascript");
setTimeout(function () { move4(x, y) }, 3800, "javascript");
setTimeout(function () { move5(x, y) }, 3900, "javascript");
setTimeout(function () { move6(x, y) }, 4000, "javascript");

setTimeout(function () { move9(52, 40) }, 4200, "javascript");
setTimeout(function () { title() }, 4400, "javascript");
setTimeout(function () { barraLateral(308, 35) }, 4500, "javascript");

setTimeout(function () { move6(40, 40) }, 4400, "javascript");
setTimeout(function () { move6(40, 35) }, 4500, "javascript");
setTimeout(function () { move6(40, 30) }, 4600, "javascript");
setTimeout(function () { move6(40, 25) }, 4700, "javascript");
setTimeout(function () { move6(40, 20) }, 4800, "javascript");

setTimeout(function () { nodo(62, 239, 77) }, 5000, "javascript");
setTimeout(function () { nodo(67, 327, -11) }, 5100, "javascript");
setTimeout(function () { nodo(27, 327, 77) }, 5200, "javascript");
setTimeout(function () { nodo(57, 327, 165) }, 5300, "javascript");
setTimeout(function () { nodo(32, 415, 77) }, 5400, "javascript");
setTimeout(function () { nodo(37, 415, 165) }, 5500, "javascript");
setTimeout(function () { nodo(52, 415, 253) }, 5600, "javascript");
setTimeout(function () { nodo(42, 503, 165) }, 5700, "javascript");
setTimeout(function () { decoracion(327, 77) }, 7000, "javascript");
setTimeout(function () { funTexto(50, 50) }, 8000, "javascript");

proporcionAurea(102, 30, 57, 40)
proporcionAurea(110, 50, 87, 40)
proporcionAurea(119, 80, 137, 40)
proporcionAurea(127, 50, 217, 40)
proporcionAurea(135, 30, 267, 40)
proporcionAurea(143, 30, 57, 121)
proporcionAurea(151, 50, 87, 170)
proporcionAurea(159, 80, 137, 251)
proporcionAurea(167, 50, 217, 170)
proporcionAurea(175, 30, 267, 121)
proporcionAurea(183, 30, 57, 251)
proporcionAurea(191, 50, 87, 300)
proporcionAurea(207, 50, 217, 300)
proporcionAurea(215, 30, 267, 251)
proporcionAurea(323, 30, 57, 331)
proporcionAurea(331, 30, 267, 331)
proporcionAurea(339, 30, 57, 380)
proporcionAurea(347, 50, 87, 380)
proporcionAurea(355, 80, 137, 380)
proporcionAurea(363, 50, 217, 380)
proporcionAurea(371, 30, 267, 380)
