<?php

namespace App\Controller;

use App\Api\TheMovieDataBase;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movie/index.html.twig', [
            'nowPlayingMovies' => $theMovieDataBase->getNowPlayingMovies(8),
             'popularMovies' => $theMovieDataBase->getPopularMovies(8)
        ]);
    }

    #[Route('/movie/{id}', name: 'app_movie')]
    public function show(int $id, MovieRepository $movieRepository, GenreRepository $genreRepository): Response
    {
        $movie = $movieRepository->find($id);

        $genres = null;
        $genresIds = $movie->getGenreIds();
        if(!empty($genresIds)) {
            $genres = $genreRepository->findByTmdbIds($movie->getGenreIds());
        }

        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/' . $movie->getTmdbId() . '/external_ids', [
            'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5MWZiMjI2MzFjYmRkOTZiMzZlMWFhZDBiYjI3YmFmMSIsInN1YiI6IjY0NTUwNTAyZDQ4Y2VlMDBmY2VlYTBjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.UB6TNHT7P4Wce6O5hzDoc5sV3bf0Ox3W0Y7h4G6nroA',
                'accept' => 'application/json',
            ],
        ]);

        $body = $response->getBody()->getContents();
        $externalIds = json_decode($body, true);

        $recommendedMovies = $this->getRecommendations($movie->getTmdbId());
        $cast = $this->getCast($movie->getTmdbId());
        $videos = $this->getVideos($movie->getTmdbId());
        $keywords = $this->getKeywords($movie->getTmdbId());

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'genres' => $genres,
            'recommendedMovies' => $recommendedMovies,
            'externalIds' => $externalIds,
            'cast' => $cast,
            'videos' => $videos,
            'keywords' => $keywords
        ]);
    }

    private function getCast($movieId)
    {
        $actors = [];

        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/' . $movieId .'/credits?language=en-US', [
            'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5MWZiMjI2MzFjYmRkOTZiMzZlMWFhZDBiYjI3YmFmMSIsInN1YiI6IjY0NTUwNTAyZDQ4Y2VlMDBmY2VlYTBjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.UB6TNHT7P4Wce6O5hzDoc5sV3bf0Ox3W0Y7h4G6nroA',
                'accept' => 'application/json',
            ],
        ]);

        $body = $response->getBody();
        $result = json_decode($body, true);

        $cast = $result['cast'];

        return array_slice($cast,0, 5);
    }

    private function getVideos($movieId)
    {
        $results = [];

        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/' . $movieId . '/videos?language=en-US', [
            'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5MWZiMjI2MzFjYmRkOTZiMzZlMWFhZDBiYjI3YmFmMSIsInN1YiI6IjY0NTUwNTAyZDQ4Y2VlMDBmY2VlYTBjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.UB6TNHT7P4Wce6O5hzDoc5sV3bf0Ox3W0Y7h4G6nroA',
                'accept' => 'application/json',
            ],
        ]);

        $body = $response->getBody();
        $result = json_decode($body, true);

        $videos = $result['results'];

        foreach($videos as $video) {
            $results[] = $video['key'];
        }

        return array_slice($results,0, 3);
    }

    private function getKeywords($movieId)
    {
        $results = [];

        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/' . $movieId . '/keywords?language=en-US', [
            'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5MWZiMjI2MzFjYmRkOTZiMzZlMWFhZDBiYjI3YmFmMSIsInN1YiI6IjY0NTUwNTAyZDQ4Y2VlMDBmY2VlYTBjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.UB6TNHT7P4Wce6O5hzDoc5sV3bf0Ox3W0Y7h4G6nroA',
                'accept' => 'application/json',
            ],
        ]);

        $body = $response->getBody();
        $result = json_decode($body, true);

        $keywords = $result['keywords'];

        foreach($keywords as $keyword) {
            $results[] = $keyword['name'];
        }

        return array_slice($results,0, 5);
    }

    private function getRecommendations($movieId)
    {
        $results = [];

        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/' . $movieId . '/recommendations?language=en-US', [
            'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5MWZiMjI2MzFjYmRkOTZiMzZlMWFhZDBiYjI3YmFmMSIsInN1YiI6IjY0NTUwNTAyZDQ4Y2VlMDBmY2VlYTBjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.UB6TNHT7P4Wce6O5hzDoc5sV3bf0Ox3W0Y7h4G6nroA',
                'accept' => 'application/json',
            ],
        ]);

        $body = $response->getBody();
        $result = json_decode($body, true);

        $movies = $result['results'];

        return array_slice($movies,0, 4);
    }

}
