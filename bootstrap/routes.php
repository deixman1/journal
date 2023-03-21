<?php

declare(strict_types=1);

use App\Application\Action\MainPageController;
use App\Application\Action\ServicesController;
use App\Application\Action\SetEnrollController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return static function (App $app) {
    $app->get('/', MainPageController::class);
    $app->get('/services', ServicesController::class);
    $app->group('/enroll', function (Group $group) {
        $group->post('/set', SetEnrollController::class);
    });
};
