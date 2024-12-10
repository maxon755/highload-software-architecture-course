<?php

declare(strict_types=1);

namespace App\Controller;

use Redis;
use App\CachedService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{

    public function __construct(private readonly CachedService $cachedService)
    {
    }

    #[Route('/test', name: 'test')]
    public function test(Request $request): Response
    {
        $value = $this->cachedService->getClassicallyCachedValue('classically_cached_value');

        return new Response(
            "Classically cached value: {$value}"
        );
    }

    #[Route('/test-prob', name: 'test-prob')]
    public function testProb(Request $request): Response
    {
        $value = $this->cachedService->getProbabilisticCachedValue('probabilistic_cached_value');

        return new Response(
            "Classically cached value: {$value}"
        );
    }
}
