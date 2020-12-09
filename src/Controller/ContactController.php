<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * Page contact
     * @Route("/page/contact", name="contact_contact", methods={"GET|POST"})
     * @return Response
     */
    public function contact(): Response
    {
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class, [
                'label' => 'Votre Nom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre Email'
            ])
            ->add('objet', TextType::class, [
                'label' => 'Objet de votre message'
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre Message'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ->getForm()
        ;

        return $this->render('page/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
