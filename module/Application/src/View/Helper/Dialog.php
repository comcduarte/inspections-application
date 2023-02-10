<?php
namespace Application\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class Dialog extends AbstractHelper
{
    public $dialog;
    
    public function __invoke($dialog = null)
    {
        if (! $dialog) {
            return $this;
        }
        
        $this->setDialog($dialog);
        return $this->render();
    }
    
    public function render()
    {
        $html = $this->getView()->render('dialog', ['dialog' => $this->getDialog()]);
        return $html;
    }
    
    /**
     * @return mixed
     */
    public function getDialog()
    {
        return $this->dialog;
    }

    /**
     * @param mixed $dialog
     */
    public function setDialog($dialog)
    {
        $this->dialog = $dialog;
        return $this;
    }
}