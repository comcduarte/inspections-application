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
use Laminas\Db\Sql\Expression;
use User\Model\UserModel;

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
        
        /****************************************
         * INFO CARDS
         ****************************************/
        $date = new \DateTime('first day of this month');
        $THIS_MONTH = $date->format('Y-m-d H:i:s');
        
        $date = new \DateTime('first day of last month');
        $LAST_MONTH = $date->format('Y-m-d H:i:s');
        
        $where = new Where();
        $where->between('inspections.DATE_CREATED', $LAST_MONTH, $THIS_MONTH);
        
        $select = new Select();
        $select->from($inspection->getTableName());
        $select->columns(['COUNT' => new Expression('COUNT(*)')]);
        $select->where($where);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $resultSet = new ResultSet($results);
        $resultSet->initialize($results);
        $records = $resultSet->toArray();
        
        $inspections_last_month = $records[0]['COUNT'];
        
        $where = new Where();
        $where->between('inspections.DATE_CREATED', $THIS_MONTH, $NOW);
        $select->where($where);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $resultSet = new ResultSet($results);
        $resultSet->initialize($results);
        $records = $resultSet->toArray();
        
        $inspections_this_month = $records[0]['COUNT'];
        
        $view->setVariable('inspections_last_month', $inspections_last_month);
        $view->setVariable('inspections_this_month', $inspections_this_month);
        
        /****************************************
         * INFO CARDS - INSPECTOR OTM
         ****************************************/
        $where = new Where();
        $where->between('inspections.DATE_CREATED', $THIS_MONTH, $NOW);
        
        $select = new Select();
        $select->from($inspection->getTableName());
        $select->columns(['USER','COUNT' => new Expression('COUNT(inspections.UUID)')]);
        $select->where($where);
        $select->group(['inspections.USER']);
        $select->order(['COUNT DESC']);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $resultSet = new ResultSet($results);
        $resultSet->initialize($results);
        $records = $resultSet->toArray();
        
        $user = new UserModel($this->adapter);
        if ($records[0]['COUNT'] == $records[1]['COUNT']) {
            $user->FNAME = 'Tie';
        } else {
            $user->read(['UUID' => $records[0]['USER']]);
        }
        
        $view->setVariable('inspector_otm', $user);
        
        /****************************************
         * INFO CARDS - PRIOR WEEK
         ****************************************/
        $date = new \DateTime('monday this week');
        $THIS_WEEK = $date->format('Y-m-d H:i:s');
        
        $date = new \DateTime('monday last week');
        $LAST_WEEK = $date->format('Y-m-d H:i:s');
        
        $where = new Where();
        $where->between('inspections.DATE_CREATED', $LAST_WEEK, $THIS_WEEK);
        
        $select = new Select();
        $select->from($inspection->getTableName());
        $select->columns(['COUNT' => new Expression('COUNT(*)')]);
        $select->where($where);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $resultSet = new ResultSet($results);
        $resultSet->initialize($results);
        $records = $resultSet->toArray();
        
        $inspections_last_week = $records[0]['COUNT'];
        
        $where = new Where();
        $where->between('inspections.DATE_CREATED', $THIS_WEEK, $NOW);
        $select->where($where);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $resultSet = new ResultSet($results);
        $resultSet->initialize($results);
        $records = $resultSet->toArray();
        
        $inspections_this_week = $records[0]['COUNT'];
        
        $view->setVariable('inspections_last_week', $inspections_last_week);
        $view->setVariable('inspections_this_week', $inspections_this_week);
        
        return $view;
    }
}