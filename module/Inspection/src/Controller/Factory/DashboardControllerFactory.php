<?php
namespace Inspection\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Inspection\Controller\DashboardController;

class DashboardControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new DashboardController();
        $adapter = $container->get('inspection-model-adapter');
        $controller->setDbAdapter($adapter);
        return $controller;
    }
}