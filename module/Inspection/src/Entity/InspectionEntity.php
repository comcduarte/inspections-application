<?php
namespace Inspection\Entity;

use Annotation\Traits\AnnotationAwareTrait;
use Files\Model\FilesModel;
use Inspection\Model\InspectionModel;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\Sql\Where;
use User\Model\UserModel;
use Inspection\Model\ResponseModel;
use Inspection\Model\PurposeModel;

class InspectionEntity
{
    use AdapterAwareTrait;
    use AnnotationAwareTrait;
    
    public $INSPECTION_UUID;
    public $INSPECTION;
    public $NOTES;
    public $IMAGES;
    public $PURPOSE;
    public $RESPONSE;
    public $USER;
    
    public function getInspection($UUID)
    {
        if ($UUID) {
            $this->clear();
            $this->INSPECTION_UUID = $UUID;
        }
        
        if (is_null($this->INSPECTION_UUID)) {
            return false;
        }
        
        /******************************
         * INSPECTION
         ******************************/
        $this->INSPECTION = new InspectionModel($this->adapter);
        $result = $this->INSPECTION->read(['UUID' => $this->INSPECTION_UUID]);
        
        if (!$result) {
            return false;
        }
        
        /******************************
         * PURPOSE
         ******************************/
        $this->PURPOSE = new PurposeModel($this->adapter);
        $this->PURPOSE->read(['UUID' => $this->INSPECTION->PURPOSE]);
        
        /******************************
         * RESPONSE
         ******************************/
        $this->RESPONSE = new ResponseModel($this->adapter);
        $this->RESPONSE->read(['UUID' => $this->INSPECTION->RESPONSE]);
        
        /******************************
         * USER
         ******************************/
        $this->USER = new UserModel($this->adapter);
        $this->USER->read(['UUID' => $this->INSPECTION->USER]);
        
        /******************************
         * ANNOTATIONS
         ******************************/
        $notes = $this->getAnnotations($this->INSPECTION->getTableName(), $this->INSPECTION_UUID);
        $this->NOTES = $notes['annotations'];
        
        /******************************
         * IMAGES
         ******************************/
        $file = new FilesModel();
        $file->setDbAdapter($this->adapter);
        $where = new Where();
        $where->equalTo('REFERENCE', $this->INSPECTION_UUID);
        $this->IMAGES = $file->fetchAll($where);
        
        return $this;
    }
    
    public function clear()
    {
        $this->INSPECTION_UUID = null;
        $this->INSPECTION = null;
        $this->NOTES = null;
        
        return $this;
    }
}