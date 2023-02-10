<?php
namespace Application\Controller;

use Files\Form\FilesUploadForm;
use Laminas\Box\API\AccessTokenAwareTrait;
use Laminas\Box\API\Resource\Folder;
use Laminas\Box\API\Resource\Upload;
use Laminas\Mvc\Controller\AbstractActionController;

class FilesController extends AbstractActionController
{
    use AccessTokenAwareTrait;
    
    public function uploadAction() {
        $form = new FilesUploadForm();
        $form->init();
        $form->addInputFilter();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());;
            $form->setData($post);
            
            if ($form->isValid()) {
                $folder_id = '0';
                $folder = new Folder($this->getAccessToken());
                $items = $folder->list_items_in_folder('0');
                foreach ($items->entries as $item ) {
                    if ($item['name'] == $post['REFERENCE']) {
                        $folder_id = $item['id'];
                    }
                }
                
                $filename = $post['FILE']['tmp_name'];
                
                $attributes = [
                    'name' => $post['FILE']['name'],
                    'parent' => [
                        'id' => $folder_id,
                    ],
                ];
                
                $upload = new Upload($this->getAccessToken());
                $upload->upload_file($attributes, $filename);
            }
            
        }
        /** Return to previous screen **/
        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }
}