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
        // 1. Yazı Başlığı
        yield TextField::new('title', 'Başlık');

        // 2. Kategori Seçimi (Senin "Yes" dediğin ilişki burada devreye giriyor)
        yield AssociationField::new('category', 'Kategori');

        // 3. İçerik (Zengin Metin Editörü)
        yield TextEditorField::new('content', 'İçerik')->hideOnIndex();

        // 4. Resim Yükleme Ayarları
        yield ImageField::new('coverImage', 'Kapak Resmi')
            ->setBasePath('uploads/images')      // Resimlerin tarayıcıda görüneceği yol prefix'i
            ->setUploadDir('public/uploads/images') // Resimlerin sunucuda yükleneceği fiziksel klasör
            ->setUploadedFileNamePattern('[randomhash].[extension]') // Dosya adını şifrele (aynı isimli dosyalar çakışmasın)
            ->setRequired(false); // Resim yüklemek zorunlu olmasın

        // 5. Tarih ve Durum
        yield DateTimeField::new('publishedAt', 'Yayın Tarihi');
        yield BooleanField::new('isPublished', 'Yayında');
    }
}
