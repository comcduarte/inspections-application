<?php
namespace Inspection\Controller;

use Annotation\Traits\AnnotationAwareTrait;
use Application\ActionMenu\ActionMenu;
use Components\Controller\AbstractBaseController;
use Files\Traits\FilesAwareTrait;
use Inspection\Model\PurposeModel;
use Inspection\Model\ResponseModel;
use Laminas\Box\API\AccessTokenAwareTrait;
use Laminas\Box\API\Resource\Folder;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Join;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Html\Hyperlink;
use Laminas\View\Model\ViewModel;

class InspectionController extends AbstractBaseController
{
    use FilesAwareTrait;
    use AnnotationAwareTrait;
    use AccessTokenAwareTrait;
    
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
            'Date' => 'DATE',
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
         * BOX
         ****************************************/
        $view->setVariable('access_token', $this->getAccessToken()->getResponse());
        
        $folder = new Folder($this->getAccessToken());
        $folder->get_folder_information('0');
        $folder->create_folder('0', $this->model->UUID);
        
        
        /****************************************
         * ANNOTATIONS
         ****************************************/
        $this->annotations_tablename = $this->model->getTableName();
        $this->annotations_prikey = $this->model->UUID;
        $this->annotations_user = $this->currentUser()->UUID;
        $view->setVariables($this->getAnnotations());
        
        /****************************************
         * FILES
         ****************************************
        $select = new Select();
        $select->columns(['UUID','Name' => 'NAME','Size' => 'SIZE']);
        $this->files->setSelect($select);
        $images = $this->files->findFiles($this->model->UUID);
        $title = 'Images';
        $view->setVariables([
            'files' => $images,
            'files_reference' => $this->model->UUID,
            'files_title' => $title,
        ]);
        */
        
        $folder_id = '0';
        $folder = new Folder($this->getAccessToken());
        $items = $folder->list_items_in_folder('0');
        foreach ($items->entries as $item ) {
            if ($item['name'] == $this->model->UUID) {
                $folder_id = $item['id'];
            }
        }
        
        $parameters = [
            'fields' => 'name',
        ];
        
        $items = $folder->list_items_in_folder($folder_id, $parameters);
        $view->setVariables([
            'files' => $items->entries,
            'files_title' => 'Related Files',
            'files_reference' => $this->model->UUID,
        ]);
        
        $actionMenu = new ActionMenu();
        
        $update = new Hyperlink();
        $update->class = 'dropdown-item';
        $update->data_url_route = 'box';
        $update->data_url_params = ['action' => 'view'];
        $update->data_href_param = 'id';
        $update->setLabel('View');
        
        $delete = new Hyperlink();
        $delete->class = 'dropdown-item bg-danger text-white';
        $delete->data_url_route = 'home';
        $delete->data_url_params = ['action' => 'delete'];
        $delete->data_href_param = 'id';
        $delete->setLabel('Delete');
        
        $actionMenu->add_menu_item($update);
//         $actionMenu->add_menu_item($delete);
        
        $view->setVariable('actionMenu', $actionMenu);
        
        return $view;
    }
}