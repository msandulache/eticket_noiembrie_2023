<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/popular', name: 'app_category_popular')]
    public function popular(
        MovieRepository    $movieRepository,
        CategoryRepository $categoryRepository
    ): Response
    {
        $movies = [];
        $category = $categoryRepository->findOneByName('Popular');
        if (!empty($category)) {
            $movies = $movieRepository->findByCategoryId($category->getId());
        }

        return $this->render('category/list.html.twig', [
            'categoryName' => 'Popular Movies',
            'movies' => $movies,
        ]);
    }

    #[Route('/category/top-rated', name: 'app_category_top_rated')]
    public function topRated(
        MovieRepository    $movieRepository
    ): Response
    {
        $movies = $movieRepository->findAllTopRated();

        return $this->render('category/list.html.twig', [
            'categoryName' => 'Top Rated Movies',
            'movies' => $movies,
        ]);
    }

    #[Route('/category/romanian-night', name: 'app_category_romanian_night')]
    public function romanianNight(
        MovieRepository    $movieRepository,
        CategoryRepository $categoryRepository
    ): Response
    {
        $movies = [];
        $category = $categoryRepository->findOneByName('Romanian Night');
        if (!empty($category)) {
            $movies = $movieRepository->findByCategoryId($category->getId());
        }

        return $this->render('category/list.html.twig', [
            'categoryName' => 'Romanian Night',
            'movies' => $movies,
        ]);
    }

    #[Route('/category/films-francais', name: 'app_category_films_francais')]
    public function filmsFrancais(
        MovieRepository    $movieRepository,
        CategoryRepository $categoryRepository
    ): Response
    {
        $movies = [];
        $category = $categoryRepository->findOneByName('Films français');
        if(!empty($category)) {
            $movies = $movieRepository->findByCategoryId($category->getId());
        }

        return $this->render('category/list.html.twig', [
            'categoryName' => 'Films Francais',
            'movies' => $movies,
        ]);
    }
}
