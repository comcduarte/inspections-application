<?php
namespace Application\Controller;

use Annotation\Traits\AnnotationAwareTrait;
use Inspection\Entity\InspectionEntity;
use Laminas\View\Model\ViewModel;
use Report\Controller\ReportController;
use Report\Model\ReportModel;

class CustomReportController extends ReportController
{
    use AnnotationAwareTrait;
    
    public function viewAction()
    {
        $view = new ViewModel();
        $view = parent::viewAction();
        
        /**
         * @var ReportModel $report
         */
        if ($report = $view->getVariable('report')) {
            if (method_exists($this, $report->FUNC)) {
                $function = $report->FUNC;
                $data = $this->$function($view->getVariable('data'));
                $view->setVariable('data', $data);
            }
        }
        $view->setTemplate('report/report/view');
        return $view;
    }
    
    private function withAnnotations($data)
    {
        global $report_data;
        
        foreach ($data as $inspection) {
            $entity = new InspectionEntity();
            $entity->setDbAdapter($this->adapter);
            $report_data[] = $entity->getInspection($inspection['UUID']);
        }
        
        return $report_data;
    }
}