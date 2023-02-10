<?php
namespace Application\Controller\Factory;

use Application\Controller\BoxController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class BoxControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $controller = new BoxController();
        $access_token = $container->get('access-token');
        $controller->setAccessToken($access_token);
        return $controller;
    }
}