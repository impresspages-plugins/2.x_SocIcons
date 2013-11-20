<?php
/**
 * @package   ImpressPages
 * @copyright Copyright (C) 2009 JSC Apro media.
 * @license   GNU/GPL, see ip_license.html
 */
namespace Modules\navigation\social_icons;

if (!defined('CMS')) exit;

class Uninstall{

    public function execute(){

        $repository = \Modules\administrator\repository\Model::instance();

        $module = 'navigation/social_icons';
        $dbh = \Ip\Db::getConnection();
        $sql = "SELECT id, filename FROM `".DB_PREF."m_navigation_social_icons` WHERE is_default=0";
        $sth = $dbh->query($sql);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);

        while($row = $sth->fetch()) {
            $repository->unbindFile($row['filename'], $module, $row['id']);
        }

        $sql = "DROP TABLE `".DB_PREF."m_navigation_social_icons`";

        $sth = $dbh->prepare($sql);
        $sth->execute();

        $sth = $dbh->prepare($sql);

    }
}

