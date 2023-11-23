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

    #[Route('/now-playing', name: 'app_now_playing')]
    public function nowPlaying(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movie/list.html.twig', [
            'title' => 'Now Playing',
            'movies' => $theMovieDataBase->getNowPlayingMovies()
        ]);
    }

    #[Route('/popular', name: 'app_popular')]
    public function popular(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movie/list.html.twig', [
            'title' => 'Popular movies',
            'movies' => $theMovieDataBase->getPopularMovies()
        ]);
    }

    #[Route('/top-rated', name: 'app_top_rated')]
    public function topRated(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movie/list.html.twig', [
            'title' => 'Top rated movies',
            'movies' => $theMovieDataBase->getTopRatedMovies()
        ]);
    }

    #[Route('/romance', name: 'app_romance')]
    public function romance(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movie/list.html.twig', [
            'title' => 'Romance night',
            'movies' => $theMovieDataBase->getRomanceMovies()
        ]);
    }

    #[Route('/family', name: 'app_family')]
    public function family(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movie/list.html.twig', [
            'title' => 'Family movies',
            'movies' => $theMovieDataBase->getFamilyMovies()
        ]);
    }

    #[Route('/movie/{id}', name: 'app_movie')]
    public function movie(int $id, TheMovieDataBase $theMovieDataBase): Response
    {
        $movie = $theMovieDataBase->getMovie($id);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }
}
