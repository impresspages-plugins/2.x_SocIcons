<?php
namespace Modules\navigation\social_icons;  //Replace "YourPluginName" with actual plugin name

if (!defined('CMS')) exit; //restrict direct access to file

class AdminController extends \Ip\Controller{

    const ICON_MODULE = 'navigation/social_icons';

    public function showDialog()
    {

        $site = \Ip\ServiceLocator::getSite();
        $data['icons'] = Model::getAllIcons();
        $data['size'] = Model::getIconSize();
        $renderedHtml = \Ip\View::create('view/view_dialog.php', $data)->render();
        $popUpData = array ($data,
            'popUpHtml' => $renderedHtml,
            'size' => $data['size']
        );
        $this->returnJson($popUpData);
    }

    public function getIconData()
    {
        $data['status'] = "success";
        $this->returnJson($data);
    }

    public function saveIcons(){

        $icons = $_POST['icons'];
        if (isset($_POST['size'])){
            Model::setIconSize($_POST['size']);
        }
        $data['size'] = Model::getIconSize();
        $order_id = 0;

        foreach ($icons as $icon){
            $order_id++;
            $reflection = $this->reflectIcons($icon['filename'], $data['size']);
            $result = Model::updateIconOptions($icon['url'], $icon['filename'], $reflection, $order_id, $icon['enabled']);

        }

        $data['icons'] = Model::getVisitorIcons();
        $renderedHtml = \Ip\View::create('view/view_preview.php', $data)->render();

        $data['stringHtml'] = $renderedHtml;
        $data['status'] = "success";
        $this->returnJson($data);
    }




    public function uploadIcons(){

        $repository = \Modules\administrator\repository\Model::instance();
        $size = $_POST['size'];
        foreach ( $_POST['newIcons'] as $newIcon){

            $file = $newIcon;
            $module = self::ICON_MODULE;
            $resizedIcon = $this->reflectIcons($file, $size);
            $instanceId = Model::addNewIcon($newIcon, $resizedIcon);
            $repository->bindFile($file, $module, $instanceId);
        }

        $data['uploadIcons'] = 'Uploading icons';
        $this->returnJson($data);

    }

    public function reflectIcons($filename, $size) {

        if (strtolower(pathinfo($filename, PATHINFO_EXTENSION))!= 'svg'){
            $reflectionService = \Modules\administrator\repository\ReflectionService::instance();
            $newName =  null; // The same as $file
            $maxWidth = $size;
            $maxHeight = $size;
            $quality = null; //number 0 - 100 or null to use default quality level
            $forced = TRUE; //if true and image is smaller it will be scaled up
            $transform = new \Modules\administrator\repository\Transform\ImageFit($maxWidth, $maxHeight, $quality, $forced);
            $reflection = $reflectionService->getReflection($filename, $newName, $transform);
        } else {
            $reflection = $filename; // SVG files should be the same
        }

        return $reflection;

    }

    public function removeIcon() {

        $file = Model::getFileName($_POST['id']);
        if (Model::removeIcon($_POST['id'])) {
            $repository = \Modules\administrator\repository\Model::instance();
            $module = self::ICON_MODULE;
            $instanceId = $_POST['id'];
            $repository->unbindFile($file, $module, $instanceId);
            $data['status'] = "success";
            $data['id'] = $_POST['id'];
        } else {
            print_r($data);
            $data['status'] = "error";
        }
        $this->returnJson($data);
    }

}