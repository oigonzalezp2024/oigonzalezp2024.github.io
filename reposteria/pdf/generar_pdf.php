<?php
require('fpdf/fpdf.php');

$data = json_decode(file_get_contents('productos.json'), true);

$pdf = new FPDF();
$pdf->AddPage();

// Encabezado
$empresa = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $data['empresa']);
$pdf->SetTextColor(97, 64, 40);
$pdf->SetFont('Arial', 'B', 24);
$pdf->Cell(0, 15, $empresa, 0, 1, 'C');
$pdf->Ln(10);

foreach ($data['productos'] as $prod) {

    $nombre = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $prod['nombre']);
    $descripcion = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $prod['descripcion']);

    // 1. Validar espacio antes de insertar para evitar cortes de página
    if ($pdf->GetY() > 220) {
        $pdf->AddPage();
    }

    // 2. Insertar Imagen (Ajustada a 60mm de ancho)
    if (file_exists($prod['imagen'])) {
        $pdf->Image($prod['imagen'], 10, $pdf->GetY(), 60);
    }

    // 3. Posicionar texto al lado de la imagen
    $pdf->SetX(75);
    $pdf->SetTextColor(43, 31, 29);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, $nombre, 0, 1);
    
    $pdf->SetX(75);
    $pdf->SetFont('Arial', '', 11);
    $pdf->MultiCell(120, 5, $descripcion);
    
    // 4. Botón con enlace
    $pdf->Ln(5);
    $pdf->SetX(75);
    $pdf->SetFillColor(176, 130, 87);
    $pdf->SetTextColor(255, 255, 255);
    $link = "https://wa.me/" . $data['whatsapp'] . "?text=Hola!%20Quiero%20pedir%20" . urlencode($prod['nombre']);
    $pdf->Cell(40, 8, 'Pedir por WhatsApp', 0, 1, 'C', true, $link);
    
    // Espacio entre productos
    $pdf->Ln(20); 
}

$pdf->Output('I', 'Catalogo_Paluv.pdf');
