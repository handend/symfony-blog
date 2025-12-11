<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\Category;
use App\Entity\Comment;
use App\Controller\Admin\BlogPostCrudController; // Bunu eklediğimizden emin olalım
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator; // Yönlendirme servisi
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        // Panel açıldığında direkt Blog Yazıları (BlogPost) sayfasına git:
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(BlogPostCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Benim Blog Panelim');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Panel Ana Sayfa', 'fa fa-home');

        yield MenuItem::section('Blog Yönetimi');
        yield MenuItem::linkToCrud('Kategoriler', 'fas fa-tags', Category::class);
        yield MenuItem::linkToCrud('Yazılar', 'fas fa-pen-nib', BlogPost::class);

        // --- YENİ EKLENEN KISIM ---
        yield MenuItem::section('Etkileşim');
        yield MenuItem::linkToCrud('Yorumlar', 'fas fa-comments', Comment::class);

        yield MenuItem::section('Siteye Git');
        yield MenuItem::linkToUrl('Ana Sayfayı Gör', 'fas fa-eye', '/');
    }


}
