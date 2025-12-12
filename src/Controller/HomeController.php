<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route; // <-- Bak burası Attribute oldu
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')] // Sitenin ana girişi (/) olsun
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        // Veritabanından 'isPublished' alanı true olanları tarihe göre getir
        $yazilar = $blogPostRepository->findBy(
            ['isPublished' => true],
            ['publishedAt' => 'DESC']
        );

        return $this->render('home/index.html.twig', [
            'posts' => $yazilar,
        ]);
    }

    #[Route('/blog/{slug}', name: 'blog_detail')]
    public function show(
        #[MapEntity(mapping: ['slug' => 'slug'])] BlogPost $post,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        // 1. Yorum Nesnesi ve Formu Oluştur
        $comment = new \App\Entity\Comment();
        $form = $this->createForm(\App\Form\CommentType::class, $comment);

        // 2. Form isteğini yakala
        $form->handleRequest($request);

        // 3. Eğer form gönderildiyse ve geçerliyse
        if ($form->isSubmitted() && $form->isValid()) {

            // Eğer kullanıcı giriş yapmamışsa yorum yapamasın (Güvenlik)
            if (!$this->getUser()) {
                $this->addFlash('error', 'Yorum yapmak için giriş yapmalısınız.');
                return $this->redirectToRoute('app_login');
            }

            $comment->setAuthor($this->getUser()); // Yazan kişi: Şu anki kullanıcı
            $comment->setBlogPost($post);          // Hangi yazıya yazıldı?
            $comment->setCreatedAt(new \DateTimeImmutable()); // Ne zaman?
            $comment->setIsPublished(true); // Direkt yayınlansın mı? (Admin onayı istersen false yap)

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Yorumunuz başarıyla gönderildi!');

            // Sayfayı yenile (Formun tekrar gönderilmesini önler)
            return $this->redirectToRoute('blog_detail', ['slug' => $post->getSlug()]);
        }

        return $this->render('home/show.html.twig', [
            'post' => $post,
            'commentForm' => $form->createView(), // Formu görünüme gönderiyoruz
        ]);
    }
}
