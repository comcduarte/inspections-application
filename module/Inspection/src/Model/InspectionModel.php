<?php
namespace Inspection\Model;

use Components\Model\AbstractBaseModel;

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
}