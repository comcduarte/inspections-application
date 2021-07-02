<?php
namespace Inspection\Form;

use Components\Form\AbstractBaseForm;
use Laminas\Form\Element\Text;

class ResponseForm extends AbstractBaseForm
{
    public function init()
    {
        parent::init();
        
        $this->add([
            'name' => 'NAME',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'NAME',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Response Name',
            ],
        ],['priority' => 100]);
    }
}