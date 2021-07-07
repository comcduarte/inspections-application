<?php
namespace Inspection\Form;

use Components\Form\AbstractBaseForm;
use Components\Form\Element\DatabaseSelect;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Form\Element\Text;
use Laminas\Form\Fieldset;
use Laminas\Form\Element\Checkbox;
use Inspection\Model\PurposeModel;
use Inspection\Model\ResponseModel;

class InspectionForm extends AbstractBaseForm
{
    use AdapterAwareTrait;
    
    public function init()
    {
        parent::init();
        
        $purposes = new Fieldset('Purposes');
        $purposes->setLabel('My Purposes');
        $purposes->add([
            'name' => 'Hello',
            'type' => Checkbox::class,
            'options' => [
                'label' => 'Hello',
            ],
        ]);
        
        $purposes->add([
            'name' => 'World',
            'type' => Checkbox::class,
            'options' => [
                'label' => 'World',
            ],
        ]);
        
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
                'empty_option' => '--- Unassigned ---',
                'database_adapter' => $this->adapter,
                'database_table' => 'users',
                'database_id_column' => 'UUID',
                'database_value_columns' => [
                    'LNAME',
                    'FNAME',
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
                'empty_option' => '--- Unassigned ---',
                'database_adapter' => $this->adapter,
                'database_table' => PurposeModel::TABLENAME,
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
                'empty_option' => '--- Unassigned ---',
                'database_adapter' => $this->adapter,
                'database_table' => ResponseModel::TABLENAME,
                'database_id_column' => 'UUID',
                'database_value_columns' => [
                    'NAME',
                ],
            ],
        ],['priority' => 100]);
        
        $this->add($purposes);
    }
}