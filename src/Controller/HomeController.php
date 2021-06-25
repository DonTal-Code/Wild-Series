<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        $apiAnswer = $this->callApi();
        return $this->json(
            $apiAnswer, 200);
    }

    public function callApi(): array
    {
        $response = $this->client->request(
            'GET',
            'http://api.pdflayer.com/api/convert?access_key=e0ab2026906726693798445c38175804'
        );
        return $response->toArray();
          }
}
