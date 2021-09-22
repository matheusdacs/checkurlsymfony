<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="app_main")
     */
    public function Index(): Response
    {

        $urlgetData = $this->generateUrl('url_log', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $user = $this->get('security.token_storage')->getToken()->getUser();


        return $this->render('main.html.twig', ['user' => $user->getUsername(), 'url_getdata' => $urlgetData]);
    }
}
