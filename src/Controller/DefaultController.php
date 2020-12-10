<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * Page d'accueil
     * @Route("/", name="default_index", methods={"GET"})
     */
    public function index(): Response
    {
        #Récupérer les articles
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();

        // Grâce à render, je vais pouvoir effectuer le rendu d'une vue.
        //return new Response("<h1>Page d'accueil</h1>");
        return $this->render("default/index.html.twig", [
            'posts' => $posts
        ]);
    }

    /**
     * Page / Action Catégorie
     * Afficher les articles d'une catégorie
     * @Route("/{alias}", name="default_category", methods={"GET"})
     * @param Category $category
     * @return Response
     */
    public function category(Category $category): Response
    {
        return $this->render("default/category.html.twig", [
            'category' => $category
        ]);
    }

    /**
     * Page / Action Atricle
     * Afficher le contenu d'un article
     * https://127.0.0.1:8000/politique/covid-19-une-troisieme-vague_1.html
     * @Route("/{category}/{alias}_{id}.html", name="default_post", methods={"GET"})
     * @param Post $post
     * @return Response
     */
    public function post(Post $post): Response
    {
        return $this->render("default/post.html.twig", [
            'post' => $post
        ]);
    }
}
