<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BlogPostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BlogPost::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // 1. Başlık
        yield TextField::new('title', 'Başlık');

        // 2. Slug (URL) - İşte burası görünümü yönetiyor
        yield TextField::new('slug', 'URL Uzantısı')
            ->setRequired(false)     // Zorunlu değil (Boş bırakılabilir)
            ->hideWhenCreating();    // YENİ EKLEME sayfasında GİZLE

        // 3. Kategori
        yield AssociationField::new('category', 'Kategori');

        // 4. İçerik
        yield TextEditorField::new('content', 'İçerik')->hideOnIndex();

        // 5. Kapak Resmi
        yield ImageField::new('coverImage', 'Kapak Resmi')
            ->setBasePath('uploads/images')
            ->setUploadDir('public/uploads/images')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false);

        // 6. Tarih
        yield DateTimeField::new('publishedAt', 'Yayın Tarihi');
        yield BooleanField::new('isPublished', 'Yayında');
    }


}
