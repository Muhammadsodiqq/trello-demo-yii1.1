<?php

/**
 * This is the model class for table "boards".
 *
 * The followings are the available columns in table 'boards':
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property InviteLinks[] $inviteLinks
 * @property Users $user
 * @property BoardMembers[] $boardMembers
 * @property Columns[] $columns
 * @property Tags[] $tags
 */
class Boards extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'boards';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, user_id', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, user_id, created_at', 'safe', 'on'=>'search'),
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
			'inviteLinks' => array(self::HAS_MANY, 'InviteLinks', 'board_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'boardMembers' => array(self::HAS_MANY, 'BoardMembers', 'board_id'),
			'columns' => array(self::HAS_MANY, 'Columns', 'board_id'),
			'tags' => array(self::HAS_MANY, 'Tags', 'board_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'user_id' => 'User',
			'created_at' => 'Created At',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Boards the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
