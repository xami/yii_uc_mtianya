<?php

/**
 * This is the model class for table "p".
 *
 * The followings are the available columns in table 'p':
 * @property integer $id
 * @property string $link
 * @property integer $count
 * @property string $info
 * @property integer $status
 * @property string $mktime
 * @property string $uptime
 */
class P extends OzSqliteActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return P the static model class
	 */
	public static function model($className=__CLASS__)
	{
//		if(!empty($config) && is_array($config))
//			$this->config=$config;

		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'p';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('link', 'required'),
			array('count, status, mktime, uptime', 'numerical', 'integerOnly'=>true),
			array('info, mktime, uptime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, link, count, info, status, mktime, uptime', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'link' => 'Link',
			'count' => 'Count',
			'info' => 'Info',
			'status' => 'Status',
			'mktime' => 'Mktime',
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
		$criteria->compare('link',$this->link,true);
		$criteria->compare('count',$this->count);
		$criteria->compare('info',$this->info,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('mktime',$this->mktime);
		$criteria->compare('uptime',$this->uptime);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}