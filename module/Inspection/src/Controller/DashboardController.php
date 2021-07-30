<?php
namespace Inspection\Controller;

use Inspection\Model\InspectionModel;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Where;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Sql\Join;

class DashboardController extends AbstractActionController
{
    use AdapterAwareTrait;
    
    
    public function indexAction()
    {
        $view = new ViewModel();
        $inspection = new InspectionModel($this->adapter);
        
        $date = new \DateTime('now',new \DateTimeZone('EDT'));
        $NOW = $date->format('Y-m-d H:i:s');
        
        $date = new \DateTime('now -1 year',new \DateTimeZone('EDT'));
        $YEAR_AGO = $date->format('Y-m-d H:i:s');
        
        /****************************************
         * NUMBER OF OPEN CASES OVER PAST YEAR
         ****************************************/
        $sql = new Sql($this->adapter);
        
        $where = new Where();
        $where->between('inspections.DATE_CREATED', $YEAR_AGO, $NOW);
        
        $select = new Select();
        $select->from($inspection->getTableName());
        $select->join('purposes', 'inspections.PURPOSE = purposes.UUID', ['NAME'], Join::JOIN_LEFT);
        $select->columns(['DATE_CREATED', 'STATUS']);
        $select->where($where);
        $select->order('DATE_CREATED ASC');
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $resultSet = new ResultSet($results);
        $resultSet->initialize($results);
        $records = $resultSet->toArray();
        
        $statistics = [];
        $statistics[1] = 0;
        $purposes = [];
        
        foreach ($records as $record) {
            $date = new \DateTime($record['DATE_CREATED'], new \DateTimeZone('EDT'));
            $MONTH = $date->format('m');
            
            if (!array_key_exists($MONTH, $statistics)) {
                $statistics[$MONTH] = 0;
            }
            $statistics[$MONTH]++;
            
            if (!array_key_exists($record['NAME'], $purposes)) {
                $purposes[$record['NAME']] = 0;
            }
            $purposes[$record['NAME']]++;
        }
        
        $view->setVariable('statistics', $statistics);
        $view->setVariable('purposes', $purposes);
        
        return $view;
    }
}