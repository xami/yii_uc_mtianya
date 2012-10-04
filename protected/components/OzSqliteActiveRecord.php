<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xami
 * Date: 12-9-22
 * Time: 下午6:29
 * To change this template use File | Settings | File Templates.
 */
class OzSqliteActiveRecord extends CActiveRecord {
    public static $oz_sqlite;
    public static $_oz_sqlite_config;

    public function getDbConnection()
    {
        if(self::$oz_sqlite!==null)
            return self::$oz_sqlite;
        else
        {
            self::$oz_sqlite=Yii::createComponent(self::$_oz_sqlite_config);
            if(self::$oz_sqlite instanceof CDbConnection){
                self::$oz_sqlite->setActive(true);
                return self::$oz_sqlite;
            }else{
                throw new CDbException(Yii::t('yii','Active Record requires a "db" CDbConnection application component.'));
            }
        }
    }
}