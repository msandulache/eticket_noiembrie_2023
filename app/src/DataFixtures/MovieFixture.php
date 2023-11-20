<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class MovieFixture extends Fixture
{

    public function __construct(protected ManagerRegistry $registry)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadPopularMovies($manager);
        $this->loadRomanianMovies($manager);
        $this->loadFrenchMovies($manager);
        $this->loadNowPlayingMovies($manager);

    }

    private function loadPopularMovies(ObjectManager $manager)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/popular', [
            'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5MWZiMjI2MzFjYmRkOTZiMzZlMWFhZDBiYjI3YmFmMSIsInN1YiI6IjY0NTUwNTAyZDQ4Y2VlMDBmY2VlYTBjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.UB6TNHT7P4Wce6O5hzDoc5sV3bf0Ox3W0Y7h4G6nroA',
                'accept' => 'application/json',
            ],
        ]);

        $body = $response->getBody()->getContents();
        $body = json_decode($body, true);
        $movies = $body['results'];

        $categoryRepository = new CategoryRepository($this->registry);
        $category = $categoryRepository->findOneByName('Popular');

        if(!empty($category)) {
            foreach($movies as $movie) {
                $manager->persist($this->getMovie($movie, $category));
            }

            $manager->flush();
        }
    }

    private function loadRomanianMovies(ObjectManager $manager)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api.themoviedb.org/3/discover/movie?include_adult=false&include_video=false&page=1&sort_by=popularity.desc&with_original_language=ro', [
            'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5MWZiMjI2MzFjYmRkOTZiMzZlMWFhZDBiYjI3YmFmMSIsInN1YiI6IjY0NTUwNTAyZDQ4Y2VlMDBmY2VlYTBjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.UB6TNHT7P4Wce6O5hzDoc5sV3bf0Ox3W0Y7h4G6nroA',
                'accept' => 'application/json',
            ],
        ]);

        $body = $response->getBody()->getContents();
        $body = json_decode($body, true);
        $movies = $body['results'];

        $categoryRepository = new CategoryRepository($this->registry);
        $category = $categoryRepository->findOneByName('Romanian Night');

        if(!empty($category)) {
            foreach($movies as $movie) {
                if(is_string($movie['backdrop_path']) && is_string($movie['poster_path'])) {
                    $manager->persist($this->getMovie($movie, $category));
                }
            }

            $manager->flush();
        }
    }

    private function loadFrenchMovies(ObjectManager $manager)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api.themoviedb.org/3/discover/movie?include_adult=false&include_video=false&page=1&sort_by=popularity.desc&with_original_language=fr', [
            'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5MWZiMjI2MzFjYmRkOTZiMzZlMWFhZDBiYjI3YmFmMSIsInN1YiI6IjY0NTUwNTAyZDQ4Y2VlMDBmY2VlYTBjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.UB6TNHT7P4Wce6O5hzDoc5sV3bf0Ox3W0Y7h4G6nroA',
                'accept' => 'application/json',
            ],
        ]);

        $body = $response->getBody()->getContents();
        $body = json_decode($body, true);
        $movies = $body['results'];

        $categoryRepository = new CategoryRepository($this->registry);
        $category = $categoryRepository->findOneByName('Films français');

        if(!empty($category)) {
            foreach($movies as $movie) {
                $manager->persist($this->getMovie($movie, $category));
            }

            $manager->flush();
        }
    }

    private function loadNowPlayingMovies(ObjectManager $manager)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/now_playing', [
            'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5MWZiMjI2MzFjYmRkOTZiMzZlMWFhZDBiYjI3YmFmMSIsInN1YiI6IjY0NTUwNTAyZDQ4Y2VlMDBmY2VlYTBjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.UB6TNHT7P4Wce6O5hzDoc5sV3bf0Ox3W0Y7h4G6nroA',
                'accept' => 'application/json',
            ],
        ]);

        $body = $response->getBody()->getContents();
        $body = json_decode($body, true);
        $movies = $body['results'];

        $categoryRepository = new CategoryRepository($this->registry);
        $category = $categoryRepository->findOneByName('Now playing');

        if(!empty($category)) {
            foreach($movies as $movie) {
                $manager->persist($this->getMovie($movie, $category));
            }

            $manager->flush();
        }
    }

    private function getMovie($movie, $category) {
        $movieObj = new Movie();

        $movieObj->setTmdbId($movie['id']);
        $movieObj->setTitle($movie['title']);
        $movieObj->setOriginalLanguage($movie['original_language']);
        $movieObj->setOriginalTitle($movie['original_title']);

        $movieObj->setOverview($movie['overview']);
        $movieObj->setGenreIds(implode(",", $movie['genre_ids']));
        $movieObj->setBackdropPath($movie['backdrop_path']);
        $movieObj->setPosterPath($movie['poster_path']);

        $movieObj->setAdult(($movie['adult'] == true ? 1 : 0));
        $movieObj->setVideo(($movie['video'] == true ? 1 : 0));
        $movieObj->setPopularity($movie['popularity']);
        $movieObj->setVoteAverage($movie['vote_average']);

        $movieObj->setVoteCount($movie['vote_count']);
        $movieObj->setCategoryId($category->getId());
        $movieObj->setReleaseDate(new \DateTime($movie['release_date']));
        $movieObj->setPrice(rand(250, 400) /100);

        return $movieObj;
    }
}
