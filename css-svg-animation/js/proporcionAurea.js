
var cont = document.getElementsByTagName("rect")

function fibonacci(n, d, x, y) {

	cont[n].style.x = x
	cont[n].style.y = y
	cont[n].style.width = d
	cont[n].style.height = d
	cont[n].style.stroke = "#00ffff11"
	cont[n].style.fill = "#ff000000"

}

function proporcionAurea(nodo, d, x, y) {
	fibonacci(nodo, (d * 100) / (13 * 100) * 8, x, y)
	fibonacci(nodo + 1, (d * 100) / (13 * 100) * 5, x + (d * 100) / (13 * 100) * 8, y)
	fibonacci(nodo + 2, (d * 100) / (13 * 100) * 5, x + (d * 100) / (13 * 100) * 8, y)
	fibonacci(nodo + 3, (d * 100) / (13 * 100), x + (d * 100) / (13 * 100) * 8, y + (d * 100) / (13 * 100) * 5)
	fibonacci(nodo + 4, (d * 100) / (13 * 100), x + (d * 100) / (13 * 100) * 9, y + (d * 100) / (13 * 100) * 5)
	fibonacci(nodo + 5, (d * 100) / (13 * 100) * 3, x + (d * 100) / (13 * 100) * 10, y + (d * 100) / (13 * 100) * 5)
	fibonacci(nodo + 6, (d * 100) / (13 * 100) * 2, x + (d * 100) / (13 * 100) * 8, y + (d * 100) / (13 * 100) * 6)
	fibonacci(nodo + 7, d, x, y + (d * 100) / (13 * 100) * 8)
}

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




