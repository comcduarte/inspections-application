<?php
namespace Inspection\Controller;

use Components\Controller\AbstractBaseController;
use Inspection\Form\ResponseForm;
use Inspection\Model\ResponseModel;

class ResponseController extends AbstractBaseController
{
    public function init()
    {
        $this->model = new ResponseModel($this->adapter);
        $this->form = new ResponseForm();
        $this->form->init();
        return $this;
    }
}