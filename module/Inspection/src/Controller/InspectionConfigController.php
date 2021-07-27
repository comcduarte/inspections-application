<?php
namespace Inspection\Controller;

use Components\Controller\AbstractConfigController;
use Components\Form\UploadFileForm;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Ddl\CreateTable;
use Laminas\Db\Sql\Ddl\DropTable;
use Laminas\Db\Sql\Ddl\Column\Datetime;
use Laminas\Db\Sql\Ddl\Column\Integer;
use Laminas\Db\Sql\Ddl\Column\Varchar;
use Laminas\Db\Sql\Ddl\Constraint\PrimaryKey;
use Laminas\View\Model\ViewModel;
use User\Model\UserModel;
use Inspection\Model\PurposeModel;
use Inspection\Model\ResponseModel;
use Inspection\Model\InspectionModel;
use Annotation\Model\AnnotationModel;

class InspectionConfigController extends AbstractConfigController
{
    public function clearDatabase()
    {
        $sql = new Sql($this->adapter);
        $ddl = [];
        
        $ddl[] = new DropTable('inspections');
        $ddl[] = new DropTable('inspections_purposes');
        $ddl[] = new DropTable('inspections_responses');
        $ddl[] = new DropTable(\Inspection\Model\ResponseModel::TABLENAME);
        $ddl[] = new DropTable(\Inspection\Model\PurposeModel::TABLENAME);
        
        foreach ($ddl as $obj) {
            $this->adapter->query($sql->buildSqlString($obj), $this->adapter::QUERY_MODE_EXECUTE);
        }
    }

    public function createDatabase()
    {
        $sql = new Sql($this->adapter);
        
        /******************************
         * INSPECTIONS
         ******************************/
        $ddl = new CreateTable('inspections');
        
        $ddl->addColumn(new Varchar('UUID', 36));
        $ddl->addColumn(new Integer('STATUS', TRUE));
        $ddl->addColumn(new Datetime('DATE_CREATED', TRUE));
        $ddl->addColumn(new Datetime('DATE_MODIFIED', TRUE));
        
        $ddl->addColumn(new Varchar('USER', 36, TRUE));
        $ddl->addColumn(new Varchar('ADDR', 255, TRUE));
        $ddl->addColumn(new Varchar('PURPOSE', 36, TRUE));
        $ddl->addColumn(new Varchar('RESPONSE', 36, TRUE));
        
        $ddl->addConstraint(new PrimaryKey('UUID'));
        
        $this->adapter->query($sql->buildSqlString($ddl), $this->adapter::QUERY_MODE_EXECUTE);
        unset($ddl);
        
        /******************************
         * PURPOSES
         ******************************/
        $ddl = new CreateTable(\Inspection\Model\PurposeModel::TABLENAME);
        
        $ddl->addColumn(new Varchar('UUID', 36));
        $ddl->addColumn(new Integer('STATUS', TRUE));
        $ddl->addColumn(new Datetime('DATE_CREATED', TRUE));
        $ddl->addColumn(new Datetime('DATE_MODIFIED', TRUE));
        
        $ddl->addColumn(new Varchar('NAME', 255, TRUE));
        
        $ddl->addConstraint(new PrimaryKey('UUID'));
        
        $this->adapter->query($sql->buildSqlString($ddl), $this->adapter::QUERY_MODE_EXECUTE);
        unset($ddl);
        
        /******************************
         * INSPECTION PURPOSES
         ******************************/
        $ddl = new CreateTable('inspections_purposes');
        
        $ddl->addColumn(new Varchar('UUID', 36));
        $ddl->addColumn(new Varchar('INSPECTION_UUID', 36));
        $ddl->addColumn(new Varchar('PURPOSE_UUID', 36));
        
        $ddl->addConstraint(new PrimaryKey('UUID'));
        
        $this->adapter->query($sql->buildSqlString($ddl), $this->adapter::QUERY_MODE_EXECUTE);
        unset($ddl);
        
        /******************************
         * RESPONSES
         ******************************/
        $ddl = new CreateTable(\Inspection\Model\ResponseModel::TABLENAME);
        
        $ddl->addColumn(new Varchar('UUID', 36));
        $ddl->addColumn(new Integer('STATUS', TRUE));
        $ddl->addColumn(new Datetime('DATE_CREATED', TRUE));
        $ddl->addColumn(new Datetime('DATE_MODIFIED', TRUE));
        
        $ddl->addColumn(new Varchar('NAME', 255, TRUE));
        
        $ddl->addConstraint(new PrimaryKey('UUID'));
        
        $this->adapter->query($sql->buildSqlString($ddl), $this->adapter::QUERY_MODE_EXECUTE);
        unset($ddl);
        
        /******************************
         * INSPECTION RESPONSES
         ******************************/
        $ddl = new CreateTable('inspections_responses');
        
        $ddl->addColumn(new Varchar('UUID', 36));
        $ddl->addColumn(new Varchar('INSPECTION_UUID', 36));
        $ddl->addColumn(new Varchar('RESPONSE_UUID', 36));
        
        $ddl->addConstraint(new PrimaryKey('UUID'));
        
        $this->adapter->query($sql->buildSqlString($ddl), $this->adapter::QUERY_MODE_EXECUTE);
        unset($ddl);
    }

    public function indexAction()
    {
        $view = new ViewModel();
        $view->setTemplate('config/index.phtml');
        $view->setVariables([
            'route' => $this->getRoute(),
        ]);
        
        $form = new UploadFileForm();
        $form->init();
        $form->addInputFilter();
        $view->setVariable('importForm', $form);
        
        return ($view);
    }
    
    public function importAction()
    {
        /****************************************
         * Column Descriptions
         ****************************************/
        $TIMESTAMP = 0;
        $DATE = 1;
        $STAFF = 2;
        $ADDRESS = 3;
        $PURPOSE = 4;
        $RESPONSE = 5;
        $NOTES = 6;
        
        /****************************************
         * Generate Form
         ****************************************/
        $request = $this->getRequest();
        
        $form = new UploadFileForm();
        $form->init();
        $form->addInputFilter();
        
        if ($request->isPost()) {
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
                );
            
            $form->setData($data);
            
            if ($form->isValid()) {
                $data = $form->getData();
                if (($handle = fopen($data['FILE']['tmp_name'],"r")) !== FALSE) {
                    while (($record = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                        /****************************************
                         * Skip First Line
                         ****************************************/
                        if ($record[$STAFF] == 'STAFF') {
                            continue;
                        }
                        
                        /****************************************
                         * Parse Object
                         ****************************************/
                        $name = explode(" ", $record[$STAFF]);
                        $user = new UserModel($this->adapter);
                        $result = $user->read(['FNAME' => $name[0], 'LNAME' => $name[1]]);
                        if ($result === FALSE) {
                            $this->flashmessenger()->addErrorMessage("Unable to read user $name[0] $name[1].");
                            continue;
                        }
                        
                        $p = new PurposeModel($this->adapter);
                        $result = $p->read(['NAME' => $record[$PURPOSE]]);
                        if ($result === FALSE) {
                            $this->flashmessenger()->addErrorMessage("Unable to read purpose $record[$PURPOSE].");
                            continue;
                        }
                        
                        
                        if ($record[$RESPONSE] == 'Resolved') {
                            ;
                        }
                        $r = new ResponseModel($this->adapter);
                        $result = $r->read(['NAME' => $record[$RESPONSE]]);
                        if ($result === FALSE) {
                            $this->flashmessenger()->addErrorMessage("Unable to read response $record[$RESPONSE].");
                            continue;
                        }
                        
                        $inspection = new InspectionModel($this->adapter);
                        $inspection->PURPOSE = $p->UUID;
                        $inspection->RESPONSE = $r->UUID;
                        $inspection->USER = $user->UUID;
                        
                        $inspection->ADDR = $record[$ADDRESS];
                        
                        if (!$inspection->create()) {
                            $this->flashmessenger()->addErrorMessage("Unable to create inspection.");
                            continue;
                        }
                        
                        $inspection->DATE_CREATED = date('Y-m-d H:i:s', strtotime($record[$DATE]));
                        $inspection->update();
                        
                        
                        /****************************************
                         * Annotations
                         ****************************************/
                        $note = new AnnotationModel($this->adapter);
                        $note->TABLENAME = $inspection->getTableName();
                        $note->PRIKEY = $inspection->UUID;
                        $note->ANNOTATION = $record[$NOTES];
                        
                        $note->USER = $inspection->USER;
                        if (!$note->create()) {
                            $this->flashmessenger()->addErrorMessage("Unable to create note for inspection $inspection->UUID.");
                            continue;
                        }
                        
                        $note->DATE_CREATED = $inspection->DATE_CREATED;
                        $note->update();
                    }
                    fclose($handle);
                    unlink($data['FILE']['tmp_name']);
                }
                $this->flashMessenger()->addSuccessMessage("Import Successful.");
            }  else {
                $this->flashmessenger()->addErrorMessage("Form is Invalid.");
            }
        }
        
        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }
}