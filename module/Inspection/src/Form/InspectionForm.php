<?php
namespace Inspection\Form;

use Components\Form\AbstractBaseForm;
use Components\Form\Element\DatabaseSelect;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Form\Element\Text;

class InspectionForm extends AbstractBaseForm
{
    use AdapterAwareTrait;
    
    public function init()
    {
        parent::init();
        
        $this->add([
            'name' => 'USER',
            'type' => DatabaseSelect::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'USERNAME',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'User',
                'database_adapter' => $this->adapter,
                'database_table' => 'users',
                'database_id_column' => 'UUID',
                'database_value_columns' => [
                    'FNAME',
                    'LNAME',
                ],
            ],
        ],['priority' => 100]);
        
        $this->add([
            'name' => 'ADDR',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'ADDR',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Address',
            ],
        ],['priority' => 100]);
        
        $this->add([
            'name' => 'PURPOSE',
            'type' => DatabaseSelect::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'PURPOSE',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'PURPOSE',
                'database_adapter' => $this->adapter,
                'database_table' => 'inspections_purposes',
                'database_id_column' => 'UUID',
                'database_value_columns' => [
                    'NAME',
                ],
            ],
        ],['priority' => 100]);
        
        $this->add([
            'name' => 'RESPONSE',
            'type' => DatabaseSelect::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'RESPONSE',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'RESPONSE',
                'database_adapter' => $this->adapter,
                'database_table' => 'inspections_responses',
                'database_id_column' => 'UUID',
                'database_value_columns' => [
                    'NAME',
                ],
            ],
        ],['priority' => 100]);
    }
}