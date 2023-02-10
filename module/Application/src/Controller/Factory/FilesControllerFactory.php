<?php
namespace Application\Controller\Factory;

use Application\Controller\FilesController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class FilesControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $controller = new FilesController();
        $access_token = $container->get('access-token');
        $controller->setAccessToken($access_token);
        return $controller;
    }
}