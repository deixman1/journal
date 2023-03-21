<?php

declare(strict_types=1);

namespace App\Application\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SetEnrollController extends AbstractController
{
    protected function action(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->createResponseWithJsonData(
            []
        );
    }
}