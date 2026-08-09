<?php
/**
 * Modulo de Evaluación con IA (Gemini API)
 * - Procesa exclusivamente MML + Métricas de Meta Ads
 * - Genera sugerencias de ajuste a la Matriz de Marco Lógico
 */

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: '');
define('GEMINI_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$input = json_decode(file_get_contents('php_input'), true);

$mml = $input['mml'] ?? null;
$metaInsights = $input['meta_insights'] ?? null;

if (!$mml || !$metaInsights) {
    http_response_code(400);
    echo json_encode(['error' => 'Se requiere el MML y las métricas de Meta para la evaluación.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!GEMINI_API_KEY) {
    http_response_code(500);
    echo json_encode(['error' => 'GEMINI_API_KEY no está configurada en las variables de entorno.'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Prompt estricto exigiendo salida estructurada en JSON
$systemPrompt = <<<EOT
Eres un Auditor Estratégico de Marketing Digital y Proyectos. Analiza la Matriz de Marco Lógico (MML) actual y los datos de rendimiento publicitario brindados por Meta Ads.

Tu tarea:
1. Evaluar si las Actividades y Componentes actuales se están alineando con los datos reales de Meta.
2. Identificar desviaciones o deficiencias operativas.
3. Sugerir correcciones inmediatas a las actividades execution.
4. Generar una versión mejorada/modificada de la sección "niveles" de la Matriz de Marco Lógico (MML) para cumplir con los objetivos.

DEBES responder ÚNICAMENTE en formato JSON válido estructurado exactamente así:
{
  "evaluacion_general": "Resumen ejecutivo del desempeño según los datos de Meta",
  "hallazgos": ["Punto 1", "Punto 2"],
  "actividades_sugeridas": ["Sugerencia 1", "Sugerencia 2"],
  "nueva_mml": {
    "niveles": [ ... MML con las correcciones o mejoras propuestas ... ]
  }
}
EOT;

$payload = [
    'contents' => [
        [
            'role' => 'user',
            'parts' => [
                ['text' => $systemPrompt . "\n\nDATOS MML:\n" . json_encode($mml, JSON_UNESCAPED_UNICODE) . "\n\nMETRICAS META ADS:\n" . json_encode($metaInsights, JSON_UNESCAPED_UNICODE)]
            ]
        ]
    ],
    'generationConfig' => [
        'responseMimeType' => 'application/json',
        'temperature' => 0.2
    ]
];

$url = GEMINI_ENDPOINT . '?key=' . GEMINI_API_KEY;

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS     => json_encode($payload)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError || $httpCode >= 400) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al comunicarse con la API de IA.', 'details' => $response], JSON_UNESCAPED_UNICODE);
    exit;
}

$decoded = json_decode($response, true);
$aiContentRaw = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
$aiContentParsed = json_decode($aiContentRaw, true);

echo json_encode([
    'status' => 'success',
    'analysis' => $aiContentParsed
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
