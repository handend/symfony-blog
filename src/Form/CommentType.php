<?php

namespace App\Form;

use App\Entity\BlogPost;
use App\Entity\Comment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', null, [
                'label' => 'Adınız Soyadınız',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('content', null, [
                'label' => 'Yorumunuz',
                'attr' => ['class' => 'form-control mb-3', 'rows' => 5]
            ])
            // createdAt ve post alanlarını buradan SİLDİK.
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
