<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    // Yorumlar genelde silinir veya okunur, admin panelinden pek "Yeni Yorum" eklenmez.
    // Ama şimdilik standart kalsın.

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('author', 'Yazan Kişi');
        yield TextareaField::new('content', 'Yorum İçeriği');

        // Hangi yazıya yapılmış? (BlogPost'taki __toString sayesinde başlık görünecek)
        yield AssociationField::new('post', 'İlgili Yazı');

        yield DateTimeField::new('createdAt', 'Tarih')
            ->setFormat('dd.MM.yyyy HH:mm'); // Tarih formatını güzelleştirelim
    }
}
