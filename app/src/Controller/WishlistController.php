<?php

namespace App\Controller;

use App\Api\TheMovieDataBase;
use App\Entity\Wishlist;
use App\Repository\WishlistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WishlistController extends AbstractController
{
    #[Route('/wishlist', name: 'app_wishlist')]
    public function index(WishlistRepository $wishlistRepository, TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('wishlist/index.html.twig', [
            'movies' => $this->getWishlistMovies($wishlistRepository, $theMovieDataBase),
        ]);
    }

    #[Route('/wishlist/add', name: 'app_add_to_wishlist')]
    public function add(Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        $wishlist = new Wishlist();
        $movieId = $request->get('movie_id');
        $wishlist->setMovieId($request->get('movie_id'));
        $wishlist->setUserId($request->get('user_id'));

        $entityManager->persist($wishlist);
        $entityManager->flush();

        return $this->redirect('/movie/' . $movieId);
    }

    #[Route('/wishlist/remove', name: 'app_remove_from_wishlist')]
    public function remove(): Response
    {
        return $this->render('wishlist/index.html.twig', [
            'controller_name' => 'WishlistController',
        ]);
    }

    private function getWishlistMovies($wishlistRepository, $theMovieDataBase)
    {
        $movies = [];
        $userId = $this->getUser()->getId();
        $movieIds = $wishlistRepository->getMovieIdsByUserId($userId);

        foreach ($movieIds as $movieId) {
            $movies[] = $theMovieDataBase->getMovie($movieId->getMovieId());
        }
//dd($movies);
        return $movies;
    }
}
