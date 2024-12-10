<?php

namespace App\Controller;

use MongoDB\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MongoDBController extends AbstractController
{
    public function __construct(private readonly Client $client)
    {
    }

    #[Route('/mongo', methods: ['POST'])]
    public function createDocument(): Response
    {
        $collection = $this->client->test->users;

        $insertOneResult = $collection->insertOne([
            'test_string_field' => 'abc',
            'test_int_field' => 42,
            'test_float_field' => 42.42,
            'created_at' => date('Y-m-d H:i:s')
        ]);


        return new Response(
            sprintf(
                "Inserted %d document with id %s",
                $insertOneResult->getInsertedCount(),
                $insertOneResult->getInsertedId()
            )
        );
    }
}
