<?php
namespace Inspection\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Inspection\Controller\InspectionController;
use Inspection\Model\InspectionModel;
use Inspection\Form\InspectionForm;

class InspectionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new InspectionController();
        $adapter = $container->get('inspection-model-adapter');
        $form = $container->get('FormElementManager')->get(InspectionForm::class);
        $model = new InspectionModel($adapter);
        
        $controller->setDbAdapter($adapter);  
        $controller->setModel($model);
        $controller->setForm($form);
        return $controller;
    }
}