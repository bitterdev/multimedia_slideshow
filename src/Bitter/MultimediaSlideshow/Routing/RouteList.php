<?php

namespace Bitter\MultimediaSlideshow\Routing;

use Bitter\MultimediaSlideshow\API\V1\Middleware\FractalNegotiatorMiddleware;
use Bitter\MultimediaSlideshow\API\V1\Configurator;
use Concrete\Core\Routing\RouteListInterface;
use Concrete\Core\Routing\Router;

class RouteList implements RouteListInterface
{
    public function loadRoutes(Router $router)
    {
        $router
            ->buildGroup()
            ->setNamespace('Concrete\Package\MultimediaSlideshow\Controller\Dialog\Support')
            ->setPrefix('/ccm/system/dialogs/multimedia_slideshow')
            ->routes('dialogs/support.php', 'multimedia_slideshow');
    }
}