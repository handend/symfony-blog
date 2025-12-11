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

    #[Route('/yazi/{id}', name: 'blog_detail')]
    public function show(BlogPost $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        // 1. Yazı yayında değilse gösterme (Güvenlik)
        if (!$post->isPublished()) {
            throw $this->createNotFoundException('Yazı bulunamadı.');
        }

        // 2. Yorum Formunu Hazırla
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        // 3. Form gönderildi mi diye kontrol et
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post); // Yorumu bu yazıya bağla
            $comment->setCreatedAt(new \DateTimeImmutable()); // Şu anki saati ekle

            // Veritabanına kaydet
            $entityManager->persist($comment);
            $entityManager->flush();

            // Başarı mesajı ver ve sayfayı yenile
            $this->addFlash('success', 'Yorumunuz başarıyla gönderildi!');
            return $this->redirectToRoute('blog_detail', ['id' => $post->getId()]);
        }

        // 4. Sayfayı göster (Hem yazıyı hem formu gönderiyoruz)
        return $this->render('home/show.html.twig', [
            'post' => $post,
            'commentForm' => $form->createView(),
        ]);
    }
}
