<?php
class DictionaryService {
    // criando função para buscar palavras na api externa com suporte a busca e paginação
    public static function fetchWords($searchTerm = '', $page = 1, $limit = 10) {
        $apiUrl = "https:api.dictionaryapi.dev/api/v2/entries/en/" .urlencode($searchTerm);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Contente-Type: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        $words = json_decode($response, true);

        $offset = ($page - 1 ) * $limit;
        $pagedWords = array_slice($words, $offset, $limit);

        return [
            'results' => $pagedWords,
            'totalDocs'=> count($words),
            'page' => $page,
            'totalPages' => ceil(count($words) / $limit),
            'hasNext' => $page < ceil(count($words) / $limit),
            'hasPrev' => $page > 1
        ];
    }    

    public static function isError($response) {
        if (is_array($response) && 
            isset($response['title']) && 
            $response['title'] === "No definitions Found "){
            return true;
        }
        return false;
    }


    public static function fetchWord($term) {
        $apiUrl = "https://api.dictionaryapi.dev/api/v2/entries/en/" . urlencode($term);

        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Contente-Type: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        $words = json_decode($response, true);

        return $words;


    }


}