<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // Ajusta según el dominio de tu frontend
header('Access-Control-Allow-Methods: GET, POST');

// --- CONFIGURACIÓN DE TU APP EN META DEVELOPERS ---
define('META_APP_ID', 'TU_APP_ID_AQUI');
define('META_APP_SECRET', 'TU_APP_SECRET_AQUI');
define('GRAPH_VERSION', 'v19.0');

// Token de Acceso (Puedes usar un User Access Token o System User Token con permiso ads_read)
$accessToken = $_ENV['META_ACCESS_TOKEN'] ?? 'TU_ACCESS_TOKEN_PROVISIONAL_AQUI';
$actAccountId = $_ENV['META_AD_ACCOUNT_ID'] ?? 'act_1234567890'; // Debe incluir el prefijo 'act_'

/**
 * Función helper para realizar peticiones cURL a la Graph API de Meta
 */
function callMetaGraphAPI($endpoint, $params = []) {
    $queryString = http_build_query($params);
    $url = "https://graph.facebook.com/" . GRAPH_VERSION . "/{$endpoint}?{$queryString}";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => true
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['error' => true, 'message' => "cURL Error: {$error}"];
    }

    $data = json_decode($response, true);
    
    if ($httpCode !== 200) {
        return [
            'error' => true, 
            'code' => $httpCode,
            'message' => $data['error']['message'] ?? 'Error desconocido en Meta API'
        ];
    }

    return $data;
}

// --- ACCIÓN: OBTENER INSIGHTS DE ANUNCIOS ---
$action = $_GET['action'] ?? 'get_insights';

if ($action === 'get_insights') {
    // Definimos los campos que necesitamos extraer
    $fields = implode(',', [
        'ad_id',
        'ad_name',
        'configured_status',
        'spend',
        'impressions',
        'clicks',
        'inline_link_clicks'
    ]);

    $params = [
        'level' => 'ad',
        'fields' => $fields,
        'date_preset' => 'this_month', // Puedes usar: today, yesterday, last_30d, etc.
        'access_token' => $accessToken
    ];

    $result = callMetaGraphAPI("{$actAccountId}/insights", $params);

    if (isset($result['error'])) {
        http_response_code(400);
        echo json_encode($result);
        exit;
    }

    // Normalizar la respuesta para que coincida exactamente con la estructura de tu JS (adsData)
    $normalizedAds = [];
    if (!empty($result['data'])) {
        foreach ($result['data'] as $item) {
            $normalizedAds[] = [
                'id'          => $item['ad_id'] ?? '',
                'nombre'      => $item['ad_name'] ?? 'Anuncio sin nombre',
                'estado'      => (isset($item['configured_status']) && $item['configured_status'] === 'ACTIVE') ? 'active' : 'inactive',
                'gastado'     => isset($item['spend']) ? (float)$item['spend'] : 0.0,
                'impresiones' => isset($item['impressions']) ? (int)$item['impressions'] : 0,
                'clics'       => isset($item['clicks']) ? (int)$item['clicks'] : 0,
                'visitas'     => isset($item['inline_link_clicks']) ? (int)$item['inline_link_clicks'] : 0
            ];
        }
    }

    echo json_encode([
        'success' => true,
        'count'   => count($normalizedAds),
        'data'    => $normalizedAds
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Acción no válida
http_response_code(400);
echo json_encode(['error' => true, 'message' => 'Acción no válida']);
