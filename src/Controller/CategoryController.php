<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\BlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

class CategoryController extends AbstractController
{
    #[Route('/kategori/{slug}', name: 'app_category_show')]
    public function show(
        #[MapEntity(mapping: ['slug' => 'slug'])] Category $category,
        BlogPostRepository $postRepository,
        Request $request
    ): Response
    {
        // 1. URL'den ?sirala=... kısmını al (Yoksa 'yeni' kabul et)
        $sirala = $request->query->get('sirala', 'yeni');

        // 2. Veritabanı sorgusunu hazırla
        $orderBy = match ($sirala) {
            'eski' => ['publishedAt' => 'ASC'],
            'az'   => ['title' => 'ASC'],
            'za'   => ['title' => 'DESC'],
            default => ['publishedAt' => 'DESC']
        };

        // 3. Yazıları çek
        $posts = $postRepository->findBy(
            ['category' => $category],
            $orderBy
        );

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'posts' => $posts,
            'currentSort' => $sirala
        ]);
    }
}
