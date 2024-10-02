<?php
require_once '../services/DictionaryService.php';
require_once '../utils/helpers.php';
require_once '../vendor/autoload.php';

header('Content-Type: application/json');

$requestMethod = 
$_SERVER['REQUEST_METHOD'];
$path = 
parse_url($_SERVER['REQUEST_URI'],
PHP_URL_PATH);

$secretKey = 'chaveSuperSecretadaCodesh123@';

switch ($path) {
    case '/':
        if ($requestMethod == 'GET') {
            echo json_encode(["message" => "Fullstack Challenge 🏅 - Dictionary"]);
        }
        break;

    case '/auth/signup':
        if ($requestMethod == 'POST') {
            $users = readJsonFile('users');
            $data = json_decode(file_get_contents('php:// input'), true);

            // verificando se o email já está cadastrado
            foreach ($users as $user) {
                if ($user['email'] == $data['email']) {
                    http_response_code(400);
                    echo json_encode(["message" => "E-mail já cadastrado"]);
                    exit;
                }
            }

            // Adicionando o novo usuário
            $newUser = [
                'id' => uniqid(),
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'],
                PASSWORD_DEFAULT) 
            ];
            $users[] = $newUser;
            writeJsonFile('users', $users);
            echo json_encode($newUser);
        }
        break;

    case '/auth/signin':
        if ($requestMethod == 'POST'){
            $users = readJsonFile('users');
            $data = json_decode(file_get_contents('php://input'), true);

            // Buscando o usuário pelo email
            foreach ($users as $user) {
                if ($user['email'] == $data['email'] && password_verify($data['password'], $user['password'])) {
                    // gerando token
                    $token = generateJwtToken($user);
                    echo json_encode(['id' => $user['id'], 'name' => $user['name'], 'token' => "Bearer {$token}"]);
                    exit; 
                }
            }

            http_response_code(401);
            echo json_encode(["message" => "E-mail ou senha inválidos"]);
        }
        break;

        case '/entries/en/word':
            if ($requestMethod == 'GET') {
                $term = $_GET['term'] ?? null;

                if($term) {
                    $response = DictionaryService::fetchWord($term);
                }

                    if(DictionaryService::isError($response)){
                        http_response_code(404);
                        echo json_encode(["message" => "Palavra não encontrada"]);
                    }else { 
                        echo json_encode($response);
                    } 
                
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Parâmetro 'term' é obrigatório"]);
            }
            break;

            default:
                http_response_code(404);
                echo json_encode(["message" => "Rota não encontrada"]);

}
