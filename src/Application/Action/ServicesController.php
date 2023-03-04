<?php

declare(strict_types=1);

namespace App\Application\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ServicesController extends AbstractController
{
    protected function action(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->createResponseHtml(
            'services.twig',
            [
                'services' => [
                    [
                        'name' => 'соснуть хуй 100 рублей',
                    ],
                    [
                        'name' => 'соснуть хуй 100 рублей',
                    ],
                    [
                        'name' => 'соснуть хуй 100 рублей',
                    ],
                    [
                        'name' => 'соснуть хуй 100 рублей',
                    ],
                ],
                'selectedService' => [
                    'name' => ''
                ],
            ]
        );
    }
}