<?php

namespace App\Controller;

use Elastic\Elasticsearch\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ElasticSearchController extends AbstractController
{
    public function __construct(private readonly Client $client)
    {
    }

    #[Route('/elastic', methods: ['POST'])]
    public function createDocument(): Response
    {
        $params = [
            'index' => 'test_index',
            'body' => [
                'test_string_field' => 'abc',
                'test_int_field' => 42,
                'test_float_field' => 42.42,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        $response = $this->client->index($params);

        return new Response($response);
    }
}
