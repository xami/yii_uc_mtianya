<?php

/**
 * This is the model class for table "{{item}}".
 *
 * The followings are the available columns in table '{{item}}':
 * @property integer $id
 * @property integer $cid
 * @property string $key
 * @property string $name
 * @property integer $count
 * @property integer $status
 * @property integer $uptime
 */
class Item extends OzActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Item the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{item}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cid, key, name, count, status', 'required'),
			array('cid, count, status, uptime', 'numerical', 'integerOnly'=>true),
			array('key', 'length', 'max'=>20),
			array('name', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, cid, key, name, count, status, uptime', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
//			'channel'=>array(self::BELONGS_TO, 'Channel', '', 'on'=>'t.cid=channel.id'),
            'channel'=>array(self::BELONGS_TO, 'Channel', 'cid'),
            'count_article'=>array(self::STAT, 'Article', 'tid', 'condition'=>'`t`.`status`=1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cid' => 'Cid',
			'key' => 'Key',
			'name' => 'Name',
			'count' => 'Count',
			'status' => 'Status',
			'uptime' => 'Uptime',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('cid',$this->cid);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('count',$this->count);
		$criteria->compare('status',$this->status);
		$criteria->compare('uptime',$this->uptime);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}