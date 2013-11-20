<?php
/**
 * @package ImpressPages
 * @copyright   Copyright (C) 2011 ImpressPages LTD.
 * @license GNU/GPL, see ip_license.html
 */
namespace Modules\navigation\social_icons;

if (!defined('CMS')) exit; //restrict direct access to file


class Model{

    const TABLE_PREF = "m_navigation_social_icons";

    public static function getFileName($id) {

        $dbh = \Ip\Db::getConnection();

        $sql = "
            SELECT
               filename
            FROM
                `".DB_PREF.self::TABLE_PREF."`
            WHERE
                id=:id";

        $sth = $dbh->prepare($sql);
        $icon_data = array( 'id' => $id);

        $sth->execute($icon_data);
        $row = $sth->fetch();

        return $row['filename'];

    }

    public static function getAllIcons() {

        $dbh = \Ip\Db::getConnection();

        $sql = "
            SELECT
                id, order_id, url, filename, resized_filename, enabled, is_default
            FROM
                `".DB_PREF.self::TABLE_PREF."`
            WHERE
                1
            ORDER BY
                order_id
        ";
        $sth = $dbh->query($sql);

# setting the fetch mode
        $sth->setFetchMode(\PDO::FETCH_ASSOC);

        while($row = $sth->fetch()) {
            $allIcons[] = $row;
        }

        return $allIcons;
    }

    public static function getVisitorIcons() {

        $dbh = \Ip\Db::getConnection();

        $sql = "
            SELECT
                id, order_id, url, filename, resized_filename, enabled, is_default
            FROM
                `".DB_PREF.self::TABLE_PREF."`
            WHERE
                enabled = 1
            ORDER BY
                order_id
        ";
        $sth = $dbh->query($sql);
        $sth->setFetchMode(\PDO::FETCH_ASSOC);
        $defaultIconsPath = IP_SOCICONS_IMG_URL;

        while ($row = $sth->fetch()) {
            if ($row['is_default']){
                $row['filename'] = $defaultIconsPath.$row['filename'];
                $row['resized_filename'] = $row['filename'];
            }else{
                $row['filename']= BASE_URL.$row['filename'] ;
                $row['resized_filename'] = BASE_URL.$row['resized_filename'];
            }

            $allIcons[] = $row;
        }

        return $allIcons;
    }



    public static function updateIconOptions($url, $filename, $resizedFileName, $orderId, $enabled){

        $dbh = \Ip\Db::getConnection();

        $sql = "UPDATE
            `".DB_PREF.self::TABLE_PREF."`
            SET order_id=:order_id,
                resized_filename=:resized_filename,
                enabled=:enabled,
                url=:url
            WHERE
                filename=:filename";
        $sth = $dbh->prepare($sql);
        $icon_data = array( 'filename' => $filename, 'resized_filename' => $resizedFileName, 'url' => $url,'order_id' => $orderId, 'enabled' => $enabled);
        $sth->execute($icon_data);

    }

    public static function addNewIcon($filename, $resizedFileName){


        $dbh = \Ip\Db::getConnection();

        $sql = "INSERT INTO
            `".DB_PREF.self::TABLE_PREF."`
                    (filename, resized_filename, enabled, is_default)
                VALUES
                    (:filename, :resized_filename,  1, 0)";

        $sth = $dbh->prepare($sql);
        $icon_data = array( 'filename' => $filename, 'resized_filename' => $resizedFileName);

        $sth->execute($icon_data);

        return $dbh->lastInsertId();

    }

    public static function getIconSize(){

        $parametersMod = \Ip\ServiceLocator::getParametersMod();
        return $parametersMod->getValue('navigation', 'social_icons', 'view', 'size');;

    }

    public static function setIconSize($size){

        $parametersMod = \Ip\ServiceLocator::getParametersMod();
        $parametersMod->setValue('navigation', 'social_icons', 'view', 'size', $size);

    }

    public static function removeIcon($id) {

        $dbh = \Ip\Db::getConnection();
        $sql = "DELETE FROM
                    `".DB_PREF.self::TABLE_PREF."`
               WHERE
                    id=:id
               AND
                    is_default<>1";

        $sth = $dbh->prepare($sql);
        $icon_data = array( 'id' => $id);
        $sth->execute($icon_data);

        return $sth->rowCount();
    }


}