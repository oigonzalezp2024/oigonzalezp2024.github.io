<?php
declare(strict_types=1);

require_once(__DIR__ . '/../fpdf/fpdf.php');

class PDFOrdenTaller extends FPDF {

    public function render(array $payload, string $formUrl, string $outputMode = 'I') {
        $this->SetMargins(10, 10, 10);
        $this->AddPage('P', 'A4');
        $this->SetAutoPageBreak(true, 15);
        $this->SetFont('Helvetica', '', 8);

        $enc = $payload['encabezado'] ?? [];
        $det = $payload['detalles'] ?? [];

        $this->renderHeaderSection($enc, $formUrl);
        $this->renderItemsTable($det);
        $this->renderSignaturesSection();

        $filename = 'ORDEN_TALLER_' . ($enc['numero_orden'] ?? 'ORDEN') . '.pdf';
        return $this->Output($outputMode, $filename);
    }

    private function renderHeaderSection(array $enc, string $formUrl): void {
        $this->SetFont('Helvetica', 'B', 14);
        $this->Cell(110, 8, $this->encodeText('HOJA DE TALLER Y CONTROL DE BODEGA'), 0, 0, 'L');

        $this->SetFillColor(230, 230, 230);
        $this->SetFont('Helvetica', 'B', 10);
        $estadoTxt = strtoupper((string)($enc['estado'] ?? 'PLANEACION'));
        $this->Cell(80, 8, $this->encodeText('ESTADO: ' . $estadoTxt), 1, 1, 'C', true);
        $this->Ln(3);

        // Bloque de datos generales de la orden
        $this->SetFont('Helvetica', 'B', 9);
        $this->Cell(45, 6, $this->encodeText("N° ORDEN: " . ($enc['numero_orden'] ?? '')), 1, 0, 'L');
        $this->Cell(45, 6, $this->encodeText("FECHA PEDIDO: " . ($enc['fecha_pedido'] ?? '')), 1, 0, 'L');
        $this->Cell(50, 6, $this->encodeText("FECHA ENTREGA: " . ($enc['fecha_entrega'] ?? '')), 1, 0, 'L');
        $this->Cell(50, 6, $this->encodeText("UNIDADES: " . ($enc['unidades'] ?? '1')), 1, 1, 'L');

        $this->Cell(45, 6, $this->encodeText("REF: " . ($enc['cod_producto'] ?? '')), 1, 0, 'L');
        $this->Cell(95, 6, $this->encodeText("PRODUCTO: " . ($enc['nombre_producto'] ?? '')), 1, 0, 'L');
        $this->Cell(50, 6, $this->encodeText("OPERARIO: " . ($enc['nombre_operario'] ?? 'SIN ASIGNAR')), 1, 1, 'L');
        $this->Ln(3);

        // Banner interactivo con hyperlink directo al formulario web
        $this->SetFillColor(37, 99, 235);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Helvetica', 'B', 9);
        $this->Cell(190, 8, $this->encodeText('>>> CLIC AQUÍ PARA REGISTRAR CONSUMO DE MATERIALES <<<'), 1, 1, 'C', true, $formUrl);
        
        $this->SetTextColor(0, 0, 0);
        $this->Ln(4);
    }

    private function renderItemsTable(array $items): void {
        $this->SetFont('Helvetica', 'B', 8);
        $this->SetFillColor(220, 220, 220);

        $wCod  = 25;
        $wDesc = 75;
        $wUnd  = 15;
        $wMed  = 25;
        $wEst  = 25;
        $wReal = 25;

        $this->Cell($wCod, 7, 'CODIGO', 1, 0, 'C', true);
        $this->Cell($wDesc, 7, $this->encodeText('DESCRIPCION MATERIAL'), 1, 0, 'C', true);
        $this->Cell($wUnd, 7, 'UND', 1, 0, 'C', true);
        $this->Cell($wMed, 7, 'MEDIDAS', 1, 0, 'C', true);
        $this->Cell($wEst, 7, 'CANT. EST.', 1, 0, 'C', true);
        $this->Cell($wReal, 7, 'CANT. REAL', 1, 1, 'C', true);

        $this->SetFont('Helvetica', '', 8);

        foreach ($items as $item) {
            if (!empty($item['es_destacado'])) {
                $this->SetTextColor(200, 0, 0);
                $this->SetFont('Helvetica', 'B', 8);
            } else {
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Helvetica', '', 8);
            }

            $cantConsumida = $item['cantidad_consumida'] !== null 
                ? number_format((float)$item['cantidad_consumida'], 2) 
                : '___';

            $this->Cell($wCod, 7, $this->encodeText((string)($item['codigo_material'] ?? '')), 1, 0, 'C');
            $this->Cell($wDesc, 7, $this->encodeText(substr((string)($item['descripcion_material'] ?? ''), 0, 40)), 1, 0, 'L');
            $this->Cell($wUnd, 7, $this->encodeText((string)($item['unidad_medida'] ?? '')), 1, 0, 'C');
            $this->Cell($wMed, 7, $this->encodeText((string)($item['medidas'] ?? '-')), 1, 0, 'C');
            $this->Cell($wEst, 7, number_format((float)($item['cantidad_estimada'] ?? 0), 2), 1, 0, 'R');
            $this->Cell($wReal, 7, $cantConsumida, 1, 1, 'C');
        }

        $this->SetTextColor(0, 0, 0);
        $this->Ln(10);
    }

    private function renderSignaturesSection(): void {
        if ($this->GetY() > 240) {
            $this->AddPage();
        }

        $this->Ln(15);
        $this->SetFont('Helvetica', 'B', 8);

        $this->Cell(85, 0, '', 'T', 0, 'C');
        $this->Cell(20, 0, '', 0, 0, 'C');
        $this->Cell(85, 0, '', 'T', 1, 'C');

        $this->Cell(85, 5, $this->encodeText('FIRMA Y CEDULA OPERARIO'), 0, 0, 'C');
        $this->Cell(20, 5, '', 0, 0, 'C');
        $this->Cell(85, 5, $this->encodeText('SUPERVISOR / CONTROL CALIDAD'), 0, 1, 'C');
    }

    private function encodeText(string $str): string {
        return mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
    }
}
