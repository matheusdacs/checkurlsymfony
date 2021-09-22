<?php

namespace App\Controller;

use App\Entity\Url;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UrlController extends AbstractController
{
    #[Route('/main/urladd', name: 'url', methods:["GET", "POST"])]
    public function index(Request $request, ValidatorInterface $validator): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $return = array();
        if($request->isMethod('POST')){

            $url = new Url();
            $url->setUrllink($request->get('urllink'));
            $url->setUserId($user);


            $violations = $validator->validate($url);
            if(count($violations) > 0){
                foreach($violations as $valid){
                    $return[] = $valid->getMessage();
                }
            }else{
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($url);
                $manager->flush();
                $return[] = "Thank you for register your url!";
            }


        }

        return $this->render('url/url.html.twig', ['returnList' => $return]);
    }

    #[Route('/main/urlget', name: 'url_get', methods:["GET"])]
    public function urlget(): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository(Url::class);
        $urlList = $repository->findByUser($user->getId());

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = json_decode($serializer->serialize($urlList, 'json'));

        return $this->render('url/showurl.html.twig', ['urllist' => $jsonContent]);
    }
}
