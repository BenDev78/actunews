<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CreatePostType;
use App\Form\UpdatePostType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
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
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param FileUploader $fileUploader
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

        #Création du formulaire
        $form = $this->createForm(CreatePostType::class, $post);

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
     * @Route("/{id}/update", name="post_update", methods={"GET|POST"})
     * ex. http://localhost:8000/dashboard/post/1/update
     * @param Request $request
     * @param Post $post
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function update(Request $request, Post $post, FileUploader $fileUploader): Response
    {
        # Récupération du l'image existante
        $oldFile = new File($this->getParameter('images_directory').'/'.$post->getImage());
        $oldFileName = $oldFile->getFilename();

        $form = $this->createForm(UpdatePostType::class, $post);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $post->setImage($oldFileName);

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            # Générer le nom de l'image | sécurisation du nom de l'image
            if ($imageFile) {

                # Supprime l'ancienne image si elle doit être modifiée
                $filesystem = new Filesystem();
                $filesystem->remove($this->getParameter('images_directory').'/'.$oldFileName);

                $newFilename = $fileUploader->upload($imageFile);

                # /!\ Permet d'insérer le nouveau nom de l'image dans la BDD /!\
                $post->setImage($newFilename);
            }

            $this->entityManager->flush();

            return $this->redirectToRoute('default_post', [
                'category' => $post->getCategory()->getAlias(),
                'alias' => $post->getAlias(),
                'id' => $post->getId()
            ]);
        }

        return $this->render('post/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un article
     * @Route("/{id}/delete", name="post_delete", methods={"GET"})
     * ex. http://localhost:8000/dashboard/post/1/delete
     * @param Post $post
     * @return Response
     */
    public function delete(Post $post): Response
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->getParameter('images_directory').'/'.$post->getImage());

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return $this->redirectToRoute('default_index');
    }
}
