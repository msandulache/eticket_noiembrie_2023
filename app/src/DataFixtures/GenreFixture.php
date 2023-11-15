<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GenreFixture extends Fixture
{
    public function load(ObjectManager $manager) {

        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://api.themoviedb.org/3/genre/movie/list?language=en', [
            'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5MWZiMjI2MzFjYmRkOTZiMzZlMWFhZDBiYjI3YmFmMSIsInN1YiI6IjY0NTUwNTAyZDQ4Y2VlMDBmY2VlYTBjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.UB6TNHT7P4Wce6O5hzDoc5sV3bf0Ox3W0Y7h4G6nroA',
                'accept' => 'application/json',
            ],
        ]);

        $body = $response->getBody()->getContents();
        $body = json_decode($body, true);
        $genres = $body['genres'];

        foreach($genres as $genre) {
            $manager->persist($this->getGenre($genre));
        }

        $manager->flush();
    }

    private function getGenre($genre) {
        $genreObj = new Genre();
        $genreObj->setTmdbId($genre['id']);
        $genreObj->setName($genre['name']);

        return $genreObj;
    }
}
