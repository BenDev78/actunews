<?php


namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Class UserController
 * @Route("/user")
 * @package App\Controller
 */
class UserController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * Permet d'inscrire un user
     * @Route("/register", name="user_register", methods={"GET|POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        # Création du user
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $user->setCreatedAt(new \DateTime());

        # Création du formulaire
        $form = $this->createFormBuilder($user)
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'block_prefix' => ' col-md-6',
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 80
                ])
            ])
            ->add('lastname', TextType::class, [
                'label' => 'votre nom',
                'constraints' => new Length([
                    'min' => 3,
                    'max' => 80
                ])
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'constraints' => new Length([
                    'max' => 180
                ])
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Vos mots de passe ne correspondent pas',
                'required' => true,
                'first_options' => ['label' => 'Votre mot de passe'],
                'second_options' => ['label' => 'Confirmez votre mot de passe']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Inscrivez-vous'
            ])
            ->getForm()
        ;

        # Insertion du user en base de données
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            # Encodage du mot de passe
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            # Sauvegarde dans la bdd
            $this->em->persist($user);
            $this->em->flush();

            # Redirection vers l'accueil
            return $this->redirectToRoute('default_index');
        }

        # Affichage du formulaire
        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
