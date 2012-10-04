<?php

/**
 * This is the model class for table "channel".
 *
 * The followings are the available columns in table 'channel':
 * @property integer $id
 * @property integer $pid
 * @property integer $pos
 * @property string $text
 * @property string $info
 * @property string $status
 * @property string $mktime
 * @property string $uptime
 */
class C extends OzSqliteActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return C the static model class
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
		return 'c';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pos', 'required'),
			array('pid, pos, mktime, uptime', 'numerical', 'integerOnly'=>true),
			array('text, info, status, mktime, uptime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, pid, pos, text, info, status, mktime, uptime', 'safe', 'on'=>'search'),
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
			'pid' => 'Pid',
			'pos' => 'Pos',
			'text' => 'Text',
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
		$criteria->compare('pid',$this->pid);
		$criteria->compare('pos',$this->pos);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('info',$this->info,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('mktime',$this->mktime);
		$criteria->compare('uptime',$this->uptime);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}