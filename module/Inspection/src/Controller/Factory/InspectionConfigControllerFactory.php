<?php
namespace Inspection\Controller\Factory;

use Inspection\Controller\InspectionConfigController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class InspectionConfigControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new InspectionConfigController();
        $adapter = $container->get('inspection-model-adapter');
        $controller->setDbAdapter($adapter);
        return $controller;
    }
}