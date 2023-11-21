<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(MovieRepository $movieRepository): Response
    {
        $nowPlayingMovies = $movieRepository->findByCategoryId(100, 8);
        $popularMovies = $movieRepository->findByCategoryId(97, 8);

        return $this->render('movie/index.html.twig', [
            'nowPlayingMovies' => $nowPlayingMovies,
            'popularMovies' => $popularMovies
        ]);
    }

    #[Route('/movie/{id}', name: 'app_movie')]
    public function show(int $id, MovieRepository $movieRepository, GenreRepository $genreRepository): Response
    {
        $movie = $movieRepository->find($id);
        $genres = $genreRepository->findByTmdbIds($movie->getGenreIds());

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'genres' => $genres
        ]);
    }

}
