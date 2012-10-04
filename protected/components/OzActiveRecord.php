<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xami
 * Date: 12-9-22
 * Time: 下午6:29
 * To change this template use File | Settings | File Templates.
 */
class OzActiveRecord extends CActiveRecord {
    public static $oz_db;

    public function getDbConnection()
    {
        if(self::$oz_db!==null)
            return self::$oz_db;
        else
        {
            self::$oz_db=Yii::app()->getComponent('oz_db');
            if(self::$oz_db instanceof CDbConnection){
                self::$oz_db->setActive(true);
                return self::$oz_db;
            }else{
                throw new CDbException(Yii::t('yii','Active Record requires a "db" CDbConnection application component.'));
            }
        }
    }
}