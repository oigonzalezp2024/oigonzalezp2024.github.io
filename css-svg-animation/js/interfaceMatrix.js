
var cont = document.getElementsByTagName("rect")

function move1(px, py) {
	cont[0].style.x = px
	cont[0].style.y = py
	cont[1].style.x = px - 5
	cont[1].style.y = py
	cont[2].style.x = px + 5
	cont[2].style.y = py
	cont[3].style.x = px
	cont[3].style.y = py - 5
	cont[4].style.x = px
	cont[4].style.y = py + 5

	cont[0].style.stroke = "#00ff0055"
	cont[1].style.stroke = "#ff0000"
	cont[2].style.stroke = "#ff0000"
	cont[3].style.stroke = "#ff0000"
	cont[4].style.stroke = "#ff0000"
}


function move2(px, py) {
	cont[0].style.x = px
	cont[0].style.y = py
	cont[1].style.x = px - 5
	cont[1].style.y = py - 1
	cont[2].style.x = px + 5
	cont[2].style.y = py + 1
	cont[3].style.x = px + 1
	cont[3].style.y = py - 5
	cont[4].style.x = px - 1
	cont[4].style.y = py + 5
}

function move3(px, py) {
	cont[0].style.x = px
	cont[0].style.y = py
	cont[1].style.x = px - 5
	cont[1].style.y = py - 2
	cont[2].style.x = px + 5
	cont[2].style.y = py + 2
	cont[3].style.x = px + 2
	cont[3].style.y = py - 5
	cont[4].style.x = px - 2
	cont[4].style.y = py + 5
}

function move4(px, py) {
	cont[0].style.x = px
	cont[0].style.y = py
	cont[1].style.x = px - 5
	cont[1].style.y = py - 3
	cont[2].style.x = px + 5
	cont[2].style.y = py + 3
	cont[3].style.x = px + 3
	cont[3].style.y = py - 5
	cont[4].style.x = px - 3
	cont[4].style.y = py + 5
}

function move5(px, py) {
	cont[0].style.x = px
	cont[0].style.y = py
	cont[1].style.x = px - 5
	cont[1].style.y = py - 4
	cont[2].style.x = px + 5
	cont[2].style.y = py + 4
	cont[3].style.x = px + 4
	cont[3].style.y = py - 5
	cont[4].style.x = px - 4
	cont[4].style.y = py + 5
}

function move6(px, py) {
	cont[0].style.x = px
	cont[0].style.y = py
	cont[1].style.x = px - 5
	cont[1].style.y = py - 5
	cont[2].style.x = px + 5
	cont[2].style.y = py + 5
	cont[3].style.x = px + 5
	cont[3].style.y = py - 5
	cont[4].style.x = px - 5
	cont[4].style.y = py + 5
}

function move7(px, py) {
	cont[5].style.x = px
	cont[5].style.y = py
	cont[5].style.height = 1
	cont[5].style.width = 1
}

function move8(px, py) {
	cont[5].style.x = px
	cont[5].style.y = py
	cont[5].style.height = 1
	cont[5].style.width = 100
}

function move9(px, py) {

	cont[5].style.x = 52
	cont[5].style.y = 35
	cont[5].style.width = 250
	cont[5].style.height = 500
	cont[5].style.stroke = "#00ff0055"
	cont[5].style.fill = "#11ff1111"

}

function title() {
	cont[6].style.x = 52
	cont[6].style.y = 15
	cont[6].style.width = 3
	cont[6].style.height = 14
	cont[6].style.stroke = "#99ff9922"
	cont[6].style.fill = "#99ff9922"

	cont[7].style.x = 58
	cont[7].style.y = 15
	cont[7].style.width = 6
	cont[7].style.height = 14
	cont[7].style.stroke = "#99ff9933"
	cont[7].style.fill = "#99ff9933"

	cont[8].style.x = 67
	cont[8].style.y = 15
	cont[8].style.width = 9
	cont[8].style.height = 14
	cont[8].style.stroke = "#99ff9944"
	cont[8].style.fill = "#99ff9944"

	cont[9].style.x = 79
	cont[9].style.y = 15
	cont[9].style.width = 12
	cont[9].style.height = 14
	cont[9].style.stroke = "#99ff9955"
	cont[9].style.fill = "#99ff9955"

	cont[10].style.x = 95
	cont[10].style.y = 15
	cont[10].style.width = 15
	cont[10].style.height = 14
	cont[10].style.stroke = "#99ff9966"
	cont[10].style.fill = "#99ff9966"

	cont[12].style.x = 308
	cont[12].style.y = 15
	cont[12].style.width = 14
	cont[12].style.height = 14
	cont[12].style.stroke = "#99ff9977"
	cont[12].style.fill = "#99ff9977"

	cont[13].style.x = 268
	cont[13].style.y = 15
	cont[13].style.width = 14
	cont[13].style.height = 14
	cont[13].style.stroke = "#99ff9977"
	cont[13].style.fill = "#99ff9977"

	cont[14].style.x = 288
	cont[14].style.y = 15
	cont[14].style.width = 14
	cont[14].style.height = 14
	cont[14].style.stroke = "#99ff9977"
	cont[14].style.fill = "#99ff9977"

	cont[11].style.x = 115
	cont[11].style.y = 15
	cont[11].style.width = 147
	cont[11].style.height = 14
	cont[11].style.stroke = "#99ff9977"
	cont[11].style.fill = "#99ff9977"
}

function barraLateral(x, y) {
	cont[20].style.x = x
	cont[20].style.y = y
	cont[20].style.width = 14
	cont[20].style.height = 5
	cont[20].style.stroke = "#99ff9977"
	cont[20].style.fill = "#99ff9977"

	cont[21].style.x = x
	cont[21].style.y = y + 10
	cont[21].style.width = 14
	cont[21].style.height = 5
	cont[21].style.stroke = "#99ff9977"
	cont[21].style.fill = "#99ff9977"

	cont[22].style.x = x
	cont[22].style.y = y + 20
	cont[22].style.width = 14
	cont[22].style.height = 5
	cont[22].style.stroke = "#99ff9977"
	cont[22].style.fill = "#99ff9977"

	cont[23].style.x = x
	cont[23].style.y = y + 30
	cont[23].style.width = 14
	cont[23].style.height = 470
	cont[23].style.stroke = "#99ff9977"
	cont[23].style.fill = "#99ff9977"
}

function decoracion(x, y) {
	cont[24].style.x = x
	cont[24].style.y = y
	cont[24].style.width = 176
	cont[24].style.height = 176
	cont[24].style.stroke = "#99ff9922"
	cont[24].style.fill = "#99ff9900"

	cont[25].style.x = x - 176
	cont[25].style.y = y - 176
	cont[25].style.width = 176
	cont[25].style.height = 176
	cont[25].style.stroke = "#99ff9922"
	cont[25].style.fill = "#99ff9900"

	cont[26].style.x = x - 50 - 35 - 3
	cont[26].style.y = y - 50 - 35 - 3
	cont[26].style.width = 176
	cont[26].style.height = 176
	cont[26].style.stroke = "#99ff9922"
	cont[26].style.fill = "#99ff9900"

}

function nodo(n, x, y) {

	cont[n].style.x = x - 50 - 35
	cont[n].style.y = y - 50 - 35
	cont[n].style.width = 3
	cont[n].style.height = 3
	cont[n].style.stroke = "#99ff99"
	cont[n].style.fill = "#99ff9900"

	cont[n + 1].style.x = x - 50 - 35 - 9
	cont[n + 1].style.y = y - 50 - 35 - 9
	cont[n + 1].style.width = 3
	cont[n + 1].style.height = 3
	cont[n + 1].style.stroke = "#99ff99"
	cont[n + 1].style.fill = "#99ff9900"

	cont[n + 2].style.x = x - 50 - 35 - 9
	cont[n + 2].style.y = y - 50 - 35
	cont[n + 2].style.width = 3
	cont[n + 2].style.height = 3
	cont[n + 2].style.stroke = "#99ff99"
	cont[n + 2].style.fill = "#99ff9900"

	cont[n + 3].style.x = x - 50 - 35
	cont[n + 3].style.y = y - 50 - 35 - 9
	cont[n + 3].style.width = 3
	cont[n + 3].style.height = 3
	cont[n + 3].style.stroke = "#99ff99"
	cont[n + 3].style.fill = "#99ff9900"

	cont[n + 4].style.x = x - 50 - 35 - 12
	cont[n + 4].style.y = y - 50 - 35 - 12
	cont[n + 4].style.width = 72
	cont[n + 4].style.height = 72
	cont[n + 4].style.stroke = "#99ff9950"
	cont[n + 4].style.fill = "#99ff9900"

}

function data(n, x, y) {

	cont[n].style.x = x - 50 - 35
	cont[n].style.y = y - 50 - 35
	cont[n].style.width = 3
	cont[n].style.height = 3
	cont[n].style.stroke = "#99ff9988"
	cont[n].style.fill = "#99ff9900"

	cont[n + 1].style.x = x - 50 - 35 - 9
	cont[n + 1].style.y = y - 50 - 35 - 9
	cont[n + 1].style.width = 3
	cont[n + 1].style.height = 3
	cont[n + 1].style.stroke = "#99ff9988"
	cont[n + 1].style.fill = "#99ff9900"

	cont[n + 2].style.x = x - 50 - 35 - 9
	cont[n + 2].style.y = y - 50 - 35
	cont[n + 2].style.width = 3
	cont[n + 2].style.height = 3
	cont[n + 2].style.stroke = "#99ff9977"
	cont[n + 2].style.fill = "#99ff9900"

	cont[n + 3].style.x = x - 50 - 35
	cont[n + 3].style.y = y - 50 - 35 - 9
	cont[n + 3].style.width = 3
	cont[n + 3].style.height = 3
	cont[n + 3].style.stroke = "#99ff9977"
	cont[n + 3].style.fill = "#99ff9900"

	cont[n + 4].style.x = x - 50 - 35 - 12
	cont[n + 4].style.y = y - 50 - 35 - 12
	cont[n + 4].style.width = 18
	cont[n + 4].style.height = 18
	cont[n + 4].style.stroke = "#99ff9977"
	cont[n + 4].style.fill = "#99ff9900"

}

function personaje(n, x, y) {

	cont[n].style.x = x - 50 - 35
	cont[n].style.y = y - 50 - 35
	cont[n].style.width = 3
	cont[n].style.height = 3
	cont[n].style.stroke = "#ff0000"
	cont[n].style.fill = "#ff0000"

}

export {
	move1, move2, move3, move4, move5,
	move9, title, barraLateral,
	move6, nodo, decoracion
}