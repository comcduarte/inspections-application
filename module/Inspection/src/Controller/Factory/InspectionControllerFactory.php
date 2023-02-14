<?php
namespace Inspection\Controller\Factory;

use Files\Model\FilesModel;
use Inspection\Controller\InspectionController;
use Inspection\Form\InspectionForm;
use Inspection\Model\InspectionModel;
use Laminas\Box\API\AccessToken;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class InspectionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new InspectionController();
        $adapter = $container->get('inspection-model-adapter');
        $form = $container->get('FormElementManager')->get(InspectionForm::class);
        $model = new InspectionModel($adapter);
        
        $access_token = new AccessToken($container->get('access-token-config'));
        $controller->setAccessToken($access_token);
        
        $controller->setDbAdapter($adapter);  
        $controller->setFiles(new FilesModel());
        $controller->getFiles()->setDbAdapter($adapter);
        $controller->setModel($model);
        $controller->setForm($form);
        return $controller;
    }
}