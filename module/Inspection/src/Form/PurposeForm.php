<?php
namespace Inspection\Form;

use Components\Form\AbstractBaseForm;
use Laminas\Form\Element\Text;

class PurposeForm extends AbstractBaseForm
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
                'label' => 'Purpose Name',
            ],
        ],['priority' => 100]);
    }
}