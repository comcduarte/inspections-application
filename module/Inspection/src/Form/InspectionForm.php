<?php
namespace Inspection\Form;

use Components\Form\AbstractBaseForm;
use Components\Form\Element\DatabaseSelect;
use Inspection\Model\PurposeModel;
use Inspection\Model\ResponseModel;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Form\Element\Date;
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
            'name' => 'DATE',
            'type' => Date::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'DATE',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Date of Inspection',
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
    }
}