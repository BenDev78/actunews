<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Gestion des articles du site
 * @Route("/dashboard/post")
 */
class PostController extends AbstractController
{
    /**
     * Permet de créer un article
     * @Route("/create", name="post_create", methods={"GET|POST"})
     * ex. http://localhost:8000/dashboard/post/create
     */
    public function create()
    {
        # Création d'un nouvel article
        $post = new Post();
        $post->setCreatedAt(new \DateTime());
        # TODO User

        #Création du formulaire
        $form = $this->createFormBuilder($post)
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
                'label' => "Illustration",
                'attr' => [
                    'class' => 'dropify'
                ]
            ] )
            ->add('submit', SubmitType::class, [
                'label' => "Publier cet article"
            ])
            ->getForm()
        ;

        # Afficher le formulaire
        return $this->render("post/create.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de mettre à jour article
     * @Route("/{id}/create", name="post_update", methods={"GET|POST"})
     * ex. http://localhost:8000/dashboard/post/1/update
     */
    public function update()
    {
        # TODO
    }

    /**
     * Permet de supprimer un article
     * @Route("/{id}/delete", name="post_delete", methods={"GET"})
     * ex. http://localhost:8000/dashboard/post/1/delete
     */
    public function delete()
    {
        # TODO
    }
}
