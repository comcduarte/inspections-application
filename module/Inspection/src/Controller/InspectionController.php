<?php
namespace Inspection\Controller;

use Annotation\Traits\AnnotationAwareTrait;
use Components\Controller\AbstractBaseController;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Sql\Join;
use Files\Traits\FilesAwareTrait;
use Inspection\Model\PurposeModel;
use Inspection\Model\ResponseModel;

class InspectionController extends AbstractBaseController
{
    use FilesAwareTrait;
    use AnnotationAwareTrait;
    
    public function indexAction()
    {
        $view = new ViewModel();
        $view = parent::indexAction();
        
        $sql = new Sql($this->adapter);
        $select = new Select();
        $select->from('inspections');
        $select->columns([
            'UUID' => 'UUID',
            'Address' => 'ADDR',
            'Date' => 'DATE_CREATED',
        ]);
        $select->join('users', 'inspections.USER = users.UUID', ['FNAME', 'LNAME', 'USERNAME'], Join::JOIN_INNER);
        $select->join(PurposeModel::TABLENAME, "inspections.PURPOSE = ".PurposeModel::TABLENAME.".UUID", ['Purpose'=>'NAME'], Join::JOIN_INNER);
        $select->join(ResponseModel::TABLENAME, "inspections.RESPONSE = ".ResponseModel::TABLENAME.".UUID", ['Response'=>'NAME'], Join::JOIN_INNER);
        $select->where(['inspections.STATUS' => $this->model::ACTIVE_STATUS]);
        $select->order('inspections.DATE_CREATED DESC');
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $resultSet = new ResultSet($results);
        $resultSet->initialize($results);
        $data = $resultSet->toArray();
        
        $header = [];
        if (!empty($data)) {
            $header = array_keys($data[0]);
        }
        
        $view->setVariable('header', $header);
        $view->setVariable('data', $data);
        
        return $view;
    }
    
    public function updateAction()
    {
        $view = new ViewModel();
        $view = parent::updateAction();
        
        $view->setTemplate('inspections/update');
        
        /****************************************
         * ANNOTATIONS
         ****************************************/
        $this->annotations_tablename = $this->model->getTableName();
        $this->annotations_prikey = $this->model->UUID;
        $this->annotations_user = $this->currentUser()->UUID;
        $view->setVariables($this->getAnnotations());
        
        /****************************************
         * FILES
         ****************************************/
        $select = new Select();
        $select->columns(['UUID','Name' => 'NAME','Date Created' => 'DATE_CREATED']);
        $this->files->setSelect($select);
        $images = $this->files->findFiles($this->model->UUID);
        $title = 'Images';
        $view->setVariables([
            'files' => $images,
            'files_reference' => $this->model->UUID,
            'files_title' => $title,
        ]);
        
        return $view;
    }
}