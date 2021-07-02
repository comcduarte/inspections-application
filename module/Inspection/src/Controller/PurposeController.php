<?php
namespace Inspection\Controller;

use Components\Controller\AbstractBaseController;
use Inspection\Form\PurposeForm;
use Inspection\Model\PurposeModel;

class PurposeController extends AbstractBaseController
{
    public function init()
    {
        $this->model = new PurposeModel($this->adapter);
        $this->form = new PurposeForm();
        $this->form->init();
        return $this;
    }
}