<?php
namespace Application\Controller;

use Laminas\Box\API\AccessTokenAwareTrait;
use Laminas\Box\API\Resource\File;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class BoxController extends AbstractActionController
{
    use AccessTokenAwareTrait;
    
    public function viewAction()
    {
        $this->layout('files_layout');
        
        $file_id = $this->params()->fromRoute('id', 0);
        if (! $file_id) {
            $this->flashmessenger()->addErrorMessage('Did not pass identifier.');
            
            // -- Return to previous screen --//
            $url = $this->getRequest()->getHeader('Referer')->getUri();
            return $this->redirect()->toUrl($url);
        }
        
        $view = new ViewModel();
        $view->setTemplate('files_view');
        
        $file = new File($this->getAccessToken());
        $content = $file->download_file($file_id);
        $view->setVariable('data', $content->getContent());
        
        /**
         * 
         * @var File $info
         */
        $info = $file->get_file_information($file_id);
        $view->setVariable('TYPE', $info->type);
        $view->setVariable('NAME', $info->name);
        $view->setVariable('SIZE', $info->size);
        
        return $view;
    }
}