<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdatePostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'article"
            ])
            ->add('category', EntityType::class, [
                'label' => 'Choisissez une catégorie',
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('content', TextareaType::class, [
                'label' => "Contenu de l'article"
            ])
            ->add('image', FileType::class, [
                'label' => "Illustration (vide si vous avez pas besoin de modifier)",
                'data_class' => null,
                'required' => true,
                'attr' => [
                    'class' => 'dropify'
                ]
            ] )
            ->add('submit', SubmitType::class, [
                'label' => "Publier cet article"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
