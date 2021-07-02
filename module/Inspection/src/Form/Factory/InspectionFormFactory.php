<?php
namespace Inspection\Form\Factory;

use Inspection\Form\InspectionForm;
use Psr\Container\ContainerInterface;

class InspectionFormFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $adapter = $container->get('inspection-model-adapter');
        
        $form = new InspectionForm();
        $form->setDbAdapter($adapter);
        return $form;
    }
}