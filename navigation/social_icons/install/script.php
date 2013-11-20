<?php
/**
 * @package   ImpressPages
 * @copyright Copyright (C) 2009 JSC Apro media.
 * @license   GNU/GPL, see ip_license.html
 */
namespace Modules\navigation\social_icons;

if (!defined('CMS')) exit;

class Install{

    public function execute(){

        $dbh = \Ip\Db::getConnection();
        $sql="
    CREATE TABLE IF NOT EXISTS `".DB_PREF."m_navigation_social_icons` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `order_id` int(11) NOT NULL,
      `filename` VARCHAR( 255 ),
      `resized_filename` VARCHAR( 255 ),
      `url` VARCHAR( 255 ),
      `enabled` BOOLEAN,
      `is_default` BOOLEAN,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;
    ";

        $sth = $dbh->prepare($sql);
        $sth->execute();

        /* Add icon data to database */

        $sth = $dbh->prepare($sql);

        $sql = "INSERT INTO  `".DB_PREF."m_navigation_social_icons` (order_id, enabled, filename, url, is_default) VALUES (:order_id, 1, :filename, :url, 1)";
        $sth = $dbh->prepare($sql);

        $data[] = array( 'filename' => 'fb.svg', 'url' => 'http://www.facebook.com' );
        $data[] = array( 'filename' => 'youtube.svg', 'url' => 'http://www.youtube.com' );
        $data[] = array( 'filename' => 'linkedin.svg', 'url' => 'https://www.linkedin.com' );
        $data[] = array( 'filename' => 'twitter.svg', 'url' => 'https://twitter.com' );
        $data[] = array( 'filename' => 'googleplus.svg', 'url' => 'https://plus.google.com' );
        $data[] = array( 'filename' => 'instagram.svg', 'url' => 'http://instagram.com' );
        $data[] = array( 'filename' => 'picasa.svg', 'url' => 'http://picasa.google.com' );
        $data[] = array( 'filename' => 'rss.svg', 'url' => '' );

        $order_id = 0;

        foreach ($data as $icon_data){
            try{
                $order_id++;
                $icon_data['order_id'] = $order_id;

                $sth->execute($icon_data);
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        }

    }
}

