<?php

/**
 * This is the model class for table "{{article}}".
 *
 * The followings are the available columns in table '{{article}}':
 * @property integer $id
 * @property integer $cid
 * @property integer $tid
 * @property integer $aid
 * @property string $title
 * @property string $tag
 * @property string $key
 * @property integer $page
 * @property string $un
 * @property integer $cto
 * @property integer $pcount
 * @property integer $mktime
 * @property integer $uptime
 * @property string $src
 * @property integer $status
 * @property integer $reach
 * @property integer $reply
 * @property integer $hot
 */
class Article extends OzActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Article the static model class
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
		return '{{article}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cid, tid, aid, title, page, un, cto, pcount, mktime, uptime, src, status, reach, reply, hot', 'required'),
			array('cid, tid, aid, page, cto, pcount, mktime, uptime, status, reach, reply, hot', 'numerical', 'integerOnly'=>true),
			array('title, key, un, src', 'length', 'max'=>255),
			array('tag', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, cid, tid, aid, title, tag, key, page, un, cto, pcount, mktime, uptime, src, status, reach, reply, hot', 'safe', 'on'=>'search'),
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
			'item'=>array(self::BELONGS_TO, 'Item', 'tid'),
			'channel'=>array(self::BELONGS_TO, 'Channel', 'cid'),
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
			'tid' => 'Tid',
			'aid' => 'Aid',
			'title' => 'Title',
			'tag' => 'Tag',
			'key' => 'Key',
			'page' => 'Page',
			'un' => Tianya::t('Author'),
			'cto' => 'Cto',
			'pcount' => 'Pcount',
			'mktime' => 'Mktime',
			'uptime' => 'Uptime',
			'src' => 'Src',
			'status' => 'Status',
			'reach' => 'Reach',
			'reply' => 'Reply',
			'hot' => 'Hot',
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
		$criteria->compare('tid',$this->tid);
		$criteria->compare('aid',$this->aid);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('tag',$this->tag,true);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('page',$this->page);
		$criteria->compare('un',$this->un,true);
		$criteria->compare('cto',$this->cto);
		$criteria->compare('pcount',$this->pcount);
		$criteria->compare('mktime',$this->mktime);
		$criteria->compare('uptime',$this->uptime);
		$criteria->compare('src',$this->src,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('reach',$this->reach);
		$criteria->compare('reply',$this->reply);
		$criteria->compare('hot',$this->hot);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}