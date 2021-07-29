<?php
namespace Inspection\Model;

use Components\Model\AbstractBaseModel;
use Inspection\Filter\ValidAddress;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\InputFilter\InputFilter;

class InspectionModel extends AbstractBaseModel
{
    public $USER;
    public $ADDR;
    public $PURPOSE;
    public $RESPONSE;
    
    public function __construct($adapter)
    {
        parent::__construct($adapter);
        $this->setTableName('inspections');
    }
    
    public static function retrieveStatus($status)
    {
        $statuses = [
            NULL => 'Inactive',
            self::INACTIVE_STATUS => 'Resolved',
            self::ACTIVE_STATUS => 'Open',
        ];
        
        return $statuses[$status];
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            
            foreach ($this->public_attributes as $var) {
                $inputFilter->add([
                    'name' => $var,
                    'required' => $this->required,
                    'filters' => [
                        ['name' => StripTags::class],
                        ['name' => StringTrim::class],
                    ],
                ]);
            }
            $inputFilter->remove('ADDR');
            $inputFilter->add([
                'name' => 'ADDR',
                'required' => $this->required,
                'filters' => [
                    ['name' => ValidAddress::class],
                ],
            ]);
            
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}