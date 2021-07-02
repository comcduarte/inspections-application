<?php
namespace Inspection\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ListControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new $requestedName();
        $adapter = $container->get('list-model-adapter');
        $controller->setDbAdapter($adapter);
        $controller->init();
        return $controller;
    }
}