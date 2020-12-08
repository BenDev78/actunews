<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController
{
    /**
     * Page contact
     * @Route("/page/contact", name="contact_contact", methods={"GET"})
     * @return Response
     */
    public function contact(): Response
    {
        return new Response("<h1>Page Contact</h1>");
    }
}
