<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Gestion des articles du site
 * @Route("/dashboard/post")
 */
class PostController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Permet de créer un article
     * @Route("/create", name="post_create", methods={"GET|POST"})
     * ex. http://localhost:8000/dashboard/post/create
     * @param Request $request : Contient la requête de l'utilisateur et ses données
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function create(Request $request, SluggerInterface $slugger, FileUploader $fileUploader): Response
    {
        # Création d'un nouvel article
        $post = new Post();
        $post->setCreatedAt(new \DateTime());

        # FIXME : Remplacer par l'utilisateur connecté
        $post->setUser(
            $this->getDoctrine()->getRepository(User::class)->find(2)
        );
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

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            # Upload de l'image
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            # Générer le nom de l'image | sécurisation du nom de l'image
            if ($imageFile) {
                $newFilename = $fileUploader->upload($imageFile);
                # /!\ Permet d'insérer le nouveau nom de l'image dans la BDD /!\
                $post->setImage($newFilename);
            }

            # Génération de l'alias
            $post->setAlias($slugger->slug(
                $post->getTitle()
            ));
            # Sauvegarde dans la bdd
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            # TODO : Notification Flash / Confirmation

            # Rediredction vers l'article
            return $this->redirectToRoute('default_post', [
               'category' => $post->getCategory()->getAlias(),
               'alias' => $post->getAlias(),
               'id' => $post->getId(),
            ]);
        }

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
