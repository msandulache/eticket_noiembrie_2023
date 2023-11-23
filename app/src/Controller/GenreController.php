<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    #[Route('/genre', name: 'app_genre')]
    public function index(GenreRepository $genreRepository): Response
    {
        return $this->render('genre/list.html.twig', [
            'genres' => $genreRepository->findAll(),
        ]);
    }
}
