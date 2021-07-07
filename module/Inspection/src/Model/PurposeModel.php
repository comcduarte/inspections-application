<?php
namespace Inspection\Model;

use Components\Model\AbstractBaseModel;

class PurposeModel extends AbstractBaseModel
{
    const TABLENAME = 'purposes';
    
    public $NAME;
    
    public function __construct($adapter)
    {
        parent::__construct($adapter);
        $this->setTableName($this::TABLENAME);
    }
}