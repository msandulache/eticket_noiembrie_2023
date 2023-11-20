<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Movie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Genre;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();
        return $this->render('admin/index.html.twig');
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Eticket');
    }

//    public function configureMenuItems(): iterable
//    {
//        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
//        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
//    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Blog'),
            MenuItem::linkToCrud('Categories', 'fa fa-file-text', Category::class),
            MenuItem::linkToCrud('Genres', 'fa fa-tags', Genre::class),
            MenuItem::linkToCrud('Movies', 'fa fa-tags', Movie::class),

//
//            MenuItem::section('Users'),
//            MenuItem::linkToCrud('Comments', 'fa fa-comment', Comment::class),
//            MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
        ];
    }


}
