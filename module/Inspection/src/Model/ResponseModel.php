<?php
namespace Inspection\Model;

use Components\Model\AbstractBaseModel;

class ResponseModel extends AbstractBaseModel
{
    const TABLENAME = 'responses';
    
    public $NAME;
    
    public function __construct($adapter)
    {
        parent::__construct($adapter);
        $this->setTableName($this::TABLENAME);
    }
}