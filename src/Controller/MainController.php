<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'main_home')]
    #[Route('/')]
    public function home(): Response
    {
        $username = "<h2>LiliKorNet</h2>";
        $serie = ["name" => "Emily in Paris", "year" => 2020];

        /**
         * @var User $user
         */
        $user = $this->getUser();

        return $this->render('main/home.html.twig', [
            'name' => $username,
            'serie' => $serie
        ]);
    }

    #[Route('/test', name: 'main_test')]
    public function test(): Response
    {
        $data = json_decode(file_get_contents('https://swapi.dev/api/people/1'));
        dump($data);

//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, "https://swapi.dev/api/people/1", CURLOPT_POSTFIELDS, ['key' => 'val']);
//        curl_exec($curl);
//        curl_close($curl);

        return $this->render('main/test.html.twig');
    }
}
