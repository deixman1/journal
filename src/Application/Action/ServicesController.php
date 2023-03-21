<?php

declare(strict_types=1);

namespace App\Application\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ServicesController extends AbstractController
{
    protected function action(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->createResponseWithJsonData(
            [
                [
                  'name'=> 'Маникюр 1',
                  'price'=> 100,
                  'time_executing'=> 3
                ],
                [
                    'name'=> 'Маникюр 2',
                  'price'=> 100,
                  'time_executing'=> 3
                ],
                [
                    'name'=> 'Маникюр 3',
                  'price'=> 100,
                  'time_executing'=> 3
                ]
            ]
        );
    }
}