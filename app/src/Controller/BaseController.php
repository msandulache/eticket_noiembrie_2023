<?php

namespace App\Controller;

use App\Repository\WishlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    public function navbar(WishlistRepository $wishlistRepository) {

        $userId = $this->getUser()->getId();

        return $this->render('base/navbar.html.twig', [
            'wishlist' => $wishlistRepository->getNumberByUserId($userId),
            'cart' => '5'
        ]);
    }
}