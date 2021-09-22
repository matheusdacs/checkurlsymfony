<?php

namespace App\Controller\Cron;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Url;
use App\Entity\Urllog;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UrlLogjobController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/cron/url/logjob', name: 'cron_url_logjob')]
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Url::class);
        $urlList = $repository->findAll();
        $responsejson  =array();
        foreach($urlList as $url){

            try{
                $response = $this->client->request(
                    'GET',
                    $url->getUrllink(),
                );
            }catch(\Exception $e){
                $responsejson[] = $e->getMessage();
                continue;
            }
            $statusCode = $response->getStatusCode();
            $responseContent= $response->getContent();

            $urlLog = new Urllog();
            $urlLog->setUrl($url)
                   ->setBody(base64_encode($responseContent))
                   ->setResponsecode($statusCode);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($urlLog);
            $manager->flush();
            $responsejson[] = "URL " . $url->getUrllink() . " processada com sucesso!";
        }


        return new JsonResponse($responsejson);
    }
}
