<?php
namespace App\Controllers;
use App\Services\ExternalApiService;
use App\Models\Favorites;
use App\Services\HttpResponse;
use DateTime;

class FilmsController {
    private $httpResponse;

    public function __construct() {
        $this->httpResponse = new HttpResponse();
    }
    
    public function index() {
        $service = new ExternalApiService('/api/films');
        $response = $service->index();
        if ($response['code'] === 200) {
            $result = [];
    
            foreach ($response['response']['results'] as $item) {
                $url = $item['url'];
                $parts = explode('/', trim($url, '/'));
                $lastNumber = end($parts);
                
                $date = new DateTime($item['release_date']);
                $formattedDate = $date->format('d/m/Y');

                $result[] = [
                    'title' => $item['title'],
                    'release_date' => $formattedDate,
                    'id' => (int)$lastNumber,
                    'favorite' => false
                ];
            }

            $favoritesModel = new Favorites();
            $allFavorites = $favoritesModel->getAllFavorites();
            $allFavoritesIds = array_column($allFavorites, 'id'); 

            foreach ($result as $key => $value) {
                if (in_array($value['id'], $allFavoritesIds)) {
                    $result[$key]['favorite'] = true;
                }
            }
           
            $catalog = ['count' => $response['response']['count'], 'catalog' => $result];

            $this->httpResponse->init(200, $catalog);
        }else{
            $this->httpResponse->init(404, $response);
        }
    }

    public function show($id) {
        $service = new ExternalApiService("/api/films/{$id}");
        $response = $service->index();
        if ($response['code'] === 200) {

            $item = $response['response'];
            
            $result = [];
            foreach ($item['characters'] as $row) {
                $parts = explode('/', $row);
                if (isset($parts[5])) {
                    $result[] = 'https://swapi.dev/api/people/' . $parts[5];
                }
            }
    
            $serivceCharacteres = new ExternalApiService('');
            $charactersData = $serivceCharacteres->MultiCurl($result);

            $date = new DateTime($item['release_date']);
            $formattedDate = $date->format('d/m/Y');
           
            $current_date = new DateTime(); 
            $interval = $date->diff($current_date);
            $data_age  = $interval->y . " years, " . $interval->m . " months, " . $interval->d . " days";

            $film = [
                'title' => $item['title'],
                'episode_id' => $item['episode_id'],
                'opening_crawl' => $item['opening_crawl'],
                'release_date' => $formattedDate,
                'director' => $item['director'],
                'producer' => $item['producer'],
                'characters' => $charactersData,
                'film_age' => $data_age
            ];
            $this->httpResponse->init(200, $film);
        } else {
            $this->httpResponse->init(404, $response['response']);
        }
    }

    public function favorites_store($request){
        $favoritesModel = new Favorites();
        $response = $favoritesModel->registerFavorites((int)$request['id']);
        if($response){
            $this->httpResponse->init(201, ["message" => "Cadastrado com Sucesso"]);
        } else {
            $this->httpResponse->init(409, ["message" => "Falha ao cadastrar"]);
        }
    }

    public function favorites_destroy($id){
        $favoritesModel = new Favorites();
        $response = $favoritesModel->deleteFavorites($id);
        if($response){
            $this->httpResponse->init(204, null);
        } else {
            $this->httpResponse->init(409, ["message" => "Erro ao Excluir"]);
        }
    }
}
