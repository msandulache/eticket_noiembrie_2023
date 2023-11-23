<?php

namespace App\Api;

use GuzzleHttp\Client;

class TheMovieDataBase
{
    protected const URL = 'https://api.themoviedb.org/3';
    protected const BEARER = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5MWZiMjI2MzFjYmRkOTZiMzZlMWFhZDBiYjI3YmFmMSIsInN1YiI6IjY0NTUwNTAyZDQ4Y2VlMDBmY2VlYTBjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.UB6TNHT7P4Wce6O5hzDoc5sV3bf0Ox3W0Y7h4G6nroA';
    protected const LANGUAGE = 'en-US';
    protected const STATUS_CODE_OK = 200;
    protected const REASON_PHRASE_OK = 'OK';

    protected string $images_base_url = '';
    protected string $images_secure_base_url = '';
    protected string $images_backdrop_size = '';
    protected string $images_logo_size = '';
    protected string $images_poster_size = '';
    protected array $genres;

    public function __construct() {

        $response = $this->get('/configuration');
        $genreReponse = $this->get('/genre/movie/list');
        if(isset($genreReponse['genres'])) {
            $this->genres = $genreReponse['genres'];
        }
        $this->images_base_url = $response['images']['base_url'] ?? $response['images']['base_url'];
        $this->images_secure_base_url = $response['images']['secure_base_url'] ?? $response['images']['secure_base_url'];
        $this->images_backdrop_size = $response['images']['backdrop_sizes'][0] ?? $response['images']['backdrop_sizes'][0];
    }

    public function getNowPlayingMovies(?int $limit = null)
    {
        return $this->getMovies('now_playing', $limit);
    }

    public function getPopularMovies(?int $limit = null)
    {
        return $this->getMovies('popular', $limit);
    }

    private function getMovies(string $queryString, ?int$limit = null): array
    {
        $movies = [];
        $response = $this->get('/movie/' . $queryString);

        if(isset($response['results'])) {
            foreach($response['results'] as $result) {
                if(isset($result['title']) && $result['poster_path'] && $result['overview']) {
                    $movies[] = [
                        'id' => $result['id'],
                        'title' => $result['title'],
                        'backdrop_path' => $this->images_base_url . $this->images_backdrop_size . $result['backdrop_path'],
                        'vote_average' => number_format($result['vote_average'],1),
                        'genres' => $this->getGenres($result['genre_ids'], 2),
                        'price' => number_format((rand(120, 570) / 100), 2)
                    ];
                }
            }
        }

        if(!empty($limit)) {
            return array_slice($movies, 0, $limit);
        }

        return $movies;
    }

    private function getGenres($genre_ids, $limit = null)
    {
        $genreList = [];
        foreach($this->genres as $genre) {
            if(in_array($genre['id'], $genre_ids)) {
                $genreList[] = $genre['name'];
            }
        }

        if(!empty($limit)) {
            $genreList = array_slice($genreList, 0, $limit);
        }

        return implode(', ', $genreList);
    }

    private function get($queryString)
    {
        $client = new Client();

        $response = $client->request('GET', self::URL  . $queryString . '?language=' . self::LANGUAGE, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::BEARER,
                'accept' => 'application/json',
            ],
        ]);

        if($this->isValid($response)) {
            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);

            return $body;
        } else {
            throw new InvalidResponseException('Error: Response is not valid');
        }
    }


    private function isValid($response)
    {
        if(
            $response->getStatusCode() == self::STATUS_CODE_OK &&
            $response->getReasonPhrase() == self::REASON_PHRASE_OK
        ) {
            return true;
        } else {
            return false;
        }
    }
}