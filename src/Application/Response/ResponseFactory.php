<?php

declare(strict_types=1);

namespace App\Application\Response;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Twig\Environment;

class ResponseFactory implements ResponseFactoryInterface
{
    public function __construct(
        private readonly Environment $template
    )
    {
    }

    public function createResponse(int $code = StatusCodeInterface::STATUS_OK, string $reasonPhrase = ''): ResponseInterface
    {
        $response = new Response($code);
        if ($reasonPhrase !== '') {
            $response = $response->withStatus($code, $reasonPhrase);
        }

        return $response;
    }

    public function createResponseWithJson(int $code = StatusCodeInterface::STATUS_OK, string $reasonPhrase = ''): ResponseInterface
    {
        $response = $this->createResponse($code, $reasonPhrase);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createResponseWithBodyString(
        string $bodyString,
        int $code = StatusCodeInterface::STATUS_OK,
        string $reasonPhrase = ''
    ): ResponseInterface {
        $response = $this->createResponse($code, $reasonPhrase);
        $responseBody = $response->getBody();
        $responseBody->rewind();
        $responseBody->write($bodyString);

        return $response;
    }

    public function createResponseWithJsonString(
        string $jsonString,
        int $code = StatusCodeInterface::STATUS_OK,
        string $reasonPhrase = ''
    ): ResponseInterface {
        $response = $this->createResponseWithJson($code, $reasonPhrase);
        $responseBody = $response->getBody();
        $responseBody->rewind();
        $responseBody->write($jsonString);

        return $response;
    }

    public function createResponseWithJsonData(
        array $jsonData,
        int $code = StatusCodeInterface::STATUS_OK,
        string $reasonPhrase = ''
    ): ResponseInterface {
        $json = json_encode($jsonData, JSON_UNESCAPED_UNICODE);

        return $this->createResponseWithJsonString($json, $code, $reasonPhrase);
    }

    public function createResponseHtml(string $template, array $data = [], $status = StatusCodeInterface::STATUS_OK): ResponseInterface
    {
        $response = $this->createResponseWithJsonString($this->template->render($template, $data));
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'text/html');
    }
}
