<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Urllog;

class UrlLogController extends AbstractController
{
    #[Route('/main/getlog', name: 'url_log')]
    public function index(): Response
    {
        $user     = $this->get('security.token_storage')->getToken()->getUser();
        $logrep   = $this->getDoctrine()->getRepository(Urllog::class);
        $logList  = $logrep->findByUser($user->getId());

        $response = array();
        foreach($logList as $log){
            $response[] = array (
                'log_id'       => $log->getId(),
                'responseCode' => $log->getResponsecode(),
                'url_link'     => $log->geturl()->getUrllink(),
            );

        }
        return new JsonResponse($response);
    }

    #[Route('/main/getlog/{logid}', name: 'url_log_body')]
    public function bodyResponse(string $logid): Response
    {
        $user     = $this->get('security.token_storage')->getToken()->getUser();
        $logrep   = $this->getDoctrine()->getRepository(Urllog::class);
        $logList  = $logrep->findByUserAndLog($user->getId(), $logid);
        $result = base64_decode($logList->getBody());
        return $this->render('url/showbody.html.twig', ['result' => $result]);
    }

}
