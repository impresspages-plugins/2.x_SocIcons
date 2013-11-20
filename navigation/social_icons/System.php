<?php
namespace Modules\navigation\social_icons; //namespace should be changed accordingly to your plugin module name and group

if (!defined('CMS')) exit; //restrict direct access to file

define( 'IP_SOCICONS_IMG_URL', BASE_URL.PLUGIN_DIR.'navigation/social_icons/public/img/'); // global

class System{

    function init(){

        global $dispatcher;
        $dispatcher->bind('site.generateSlot', array($this, 'catchSlot'));

        $site = \Ip\ServiceLocator::getSite();

        if ($site->managementState()) {
            $site->addJavascript(BASE_URL.'ip_cms/modules/developer/inline_management/public/inlineManagementControls.js');
            $site->addJavascript(BASE_URL.PLUGIN_DIR.'navigation/social_icons/public/management.js');
        }

        $site->addCss(BASE_URL.PLUGIN_DIR.'navigation/social_icons/public/socicons.css');
    }

    public static function catchSlot (\Ip\Event $event) {
        $name = $event->getValue('slotName');
        if ( $name == 'socialIcons') {

            $data['icons'] = Model::getVisitorIcons();
            $data['size'] = Model::getIconSize();

            $renderedHtml = \Ip\View::create('view/view_preview.php', $data)->render();
            $event->setValue('content', $renderedHtml);
            $event->addProcessed();
        }
    }
}