<?php

require_once '../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use \Exception;

function generateJwtToken($user) {
    // obs: como é um teste, não criei varaveis de ambiente para gerenciar as keys
    $secretKey = 'chaveSuperSecretadaCodesh123@';
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600;
    $payload = [
        'iat' => $issuedAt,
        'exp' => $expirationTime,
        'data' => $user
    ];

    return 
    \Firebase\JWT\JWT ::encode($payload, $secretKey, $user); 
}

function validateJwtToken($token, $secretKey) {
    try {
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256' ));

        if (isset($decoded->data)) {
            return (array) $decoded->data;
        }
        return null;
        
    } catch (Exception $e) {
        return null;
    }
}

function readJsonFile($filename) {
    $path = __DIR__ ."/../../data/{$filename}.json";
    if (file_exists($path)) {
        $content = file_get_contents($path);
        return json_decode($content, true);
    }
    return [];
}

function writeJsonFile($filename, $data) {
    $path = __DIR__ ."/../data/{$filename}.json";
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
}



