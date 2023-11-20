<?php

namespace App\Controller;

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

}
