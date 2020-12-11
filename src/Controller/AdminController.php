<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @Route("/admin/dashboard")
 * @package App\Controller
 */
class AdminController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * Compte le nombre d'utilisateurs inscrits
     * @Route("/", name="admin_dashboard")
     */
    public function index(): Response
    {
        $repoUser = $this->em->getRepository(User::class);

        $totalUser = $repoUser->countAllUsers();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $totalUser
        ]);
    }

    /**
     * @Route("/user", name="admin_users")
     */
    public function users()
    {
        $users = $this->em->getRepository(User::class)->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }
}
