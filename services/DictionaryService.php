<?php

class DictionaryService {
    // criando função para buscar palavras na api externa
    public static function fetchWord($word) {
        $apiUrl = "https:api.dictionaryapi.dev/api/v2/entreties/en/$word";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'Content -Type : application/ json']);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

     // Verificando se  api está retornando erro
     public static function isError($response) {
        return isset($response['title']) && $response['title'] === 'no Definitions Found';
     }
}