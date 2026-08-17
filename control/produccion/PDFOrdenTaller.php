<?php
/**
 * PDFOrdenTaller.php - PDF estricto para Planta / Operarios
 */
require_once('fpdf/fpdf.php');

class PDFOrdenTaller extends FPDF {

    public function render(array $payload, string $outputMode = 'I') {
        $this->AddPage('P', 'A4');
        $this->SetAutoPageBreak(true, 15);

        $this->renderHeaderSection($payload['encabezado'] ?? []);
        $this->renderItemsTable($payload['detalles'] ?? []);

        $filename = 'HOJA_TALLER_ORDEN_' . ($payload['encabezado']['numero_orden'] ?? 'S_N') . '.pdf';
        return $this->Output($outputMode, $filename);
    }

    private function renderHeaderSection(array $enc): void {
        // Título y Estado
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(120, 8, $this->encodeText('HOJA DE RUTA / ORDEN DE TALLER'), 0, 0, 'L');
        
        $this->SetFillColor(220, 220, 220);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(70, 8, $this->encodeText('ESTADO: ' . strtoupper($enc['estado'] ?? 'PLANEACION')), 1, 1, 'C', true);
        $this->Ln(3);

        // Datos de Identificación Operativa
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(45, 6, $this->encodeText("N° ORDEN: " . ($enc['numero_orden'] ?? '')), 1, 0, 'L');
        $this->Cell(75, 6, $this->encodeText("FECHA ENTREGA: " . ($enc['fecha_entrega'] ?? '')), 1, 0, 'L');
        $this->Cell(70, 6, $this->encodeText("OPERARIO ASIGNADO: " . ($enc['operario'] ?? 'SIN ASIGNAR')), 1, 1, 'L');

        // Producto a Fabricar
        $this->Cell(45, 6, $this->encodeText("REF: " . ($enc['cod_producto'] ?? '')), 1, 0, 'L');
        $this->Cell(145, 6, $this->encodeText("PRODUCTO: " . ($enc['nombre_producto'] ?? '')), 1, 1, 'L');
        
        $this->Ln(5);
    }

    private function renderItemsTable(array $items): void {
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(230, 230, 230);
        
        // Cabecera simplificada: Solo Material, Unidad, Medidas y Cantidad
        $this->Cell(30, 7, $this->encodeText('CÓDIGO'), 1, 0, 'C', true);
        $this->Cell(90, 7, $this->encodeText('DESCRIPCIÓN DEL MATERIAL'), 1, 0, 'C', true);
        $this->Cell(25, 7, $this->encodeText('UNIDAD'), 1, 0, 'C', true);
        $this->Cell(25, 7, $this->encodeText('MEDIDAS'), 1, 0, 'C', true);
        $this->Cell(20, 7, $this->encodeText('CANT.'), 1, 1, 'C', true);

        $this->SetFont('Arial', '', 9);

        foreach ($items as $item) {
            if (!empty($item['es_destacado'])) {
                $this->SetTextColor(200, 0, 0);
                $this->SetFont('Arial', 'B', 9);
            } else {
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Arial', '', 9);
            }

            $this->Cell(30, 7, $this->encodeText($item['codigo'] ?? ''), 1);
            $this->Cell(90, 7, $this->encodeText(substr($item['descripcion'] ?? '', 0, 50)), 1);
            $this->Cell(25, 7, $this->encodeText($item['unidad'] ?? ''), 1, 0, 'C');
            $this->Cell(25, 7, $this->encodeText($item['medidas'] ?? '-'), 1, 0, 'C');
            $this->Cell(20, 7, $this->encodeText($item['cantidad'] ?? '0'), 1, 1, 'R');
        }

        $this->SetTextColor(0, 0, 0);
    }

    private function encodeText(string $str): string {
        return mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
    }
}
