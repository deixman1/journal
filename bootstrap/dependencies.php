<?php

declare(strict_types=1);

use App\Application\Response\ResponseFactory;
use App\Domain\Infrastructure\UsdaApi\UsdaApi;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;

return [
    'config' => require_once __DIR__ . '/config.php',
    LoggerInterface::class => function (ContainerInterface $container) {
        $config = $container->get('config')['logger'];
        $logger = new Logger($config['name']);
        $handler = new StreamHandler($config['path'], $config['level']);
        $logger->pushHandler($handler);
        return $logger;
    },
    UsdaApi::class => function (ContainerInterface $container) {
        $key = $container->get('config')['usdaApiKey'];
        return new UsdaApi(new Client(), $key);
    },
    Environment::class => static function (ContainerInterface $container): Environment {
        $config = $container->get('config')['twig'];

        $loader = new FilesystemLoader();

        foreach ($config['template_dirs'] as $alias => $dir) {
            $loader->addPath($dir, $alias);
        }

        $environment = new Environment($loader, [
            'cache' => $config['debug'] ? false : $config['cache_dir'],
            'debug' => $config['debug'],
            'strict_variables' => $config['debug'],
            'auto_reload' => $config['debug'],
        ]);

        if ($config['debug']) {
            $environment->addExtension(new DebugExtension());
        }

        foreach ($config['extensions'] as $class) {
            /** @var ExtensionInterface $extension */
            $extension = $container->get($class);
            $environment->addExtension($extension);
        }

        return $environment;
    },
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return new ResponseFactory($container->get(Environment::class));
    },
];

