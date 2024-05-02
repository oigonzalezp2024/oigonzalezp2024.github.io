
const cont = document.getElementsByTagName("rect")

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

export { proporcionAurea }