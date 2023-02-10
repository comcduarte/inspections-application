<?php
namespace Inspection\Controller;

use Components\Controller\AbstractConfigController;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Ddl\CreateTable;
use Laminas\Db\Sql\Ddl\DropTable;
use Laminas\Db\Sql\Ddl\Column\Date;
use Laminas\Db\Sql\Ddl\Column\Datetime;
use Laminas\Db\Sql\Ddl\Column\Integer;
use Laminas\Db\Sql\Ddl\Column\Varchar;
use Laminas\Db\Sql\Ddl\Constraint\PrimaryKey;

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
        $ddl->addColumn(new Date('DATE', TRUE));
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
}