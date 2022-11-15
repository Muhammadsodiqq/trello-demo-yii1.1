<?php

/**
 * This is the model class for table "cards".
 *
 * The followings are the available columns in table 'cards':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $deadline
 * @property integer $column_id
 * @property integer $user_id
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property CardMembers[] $cardMembers
 * @property Users $user
 * @property Columns $column
 * @property CardTags[] $cardTags
 */
class Cards extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cards';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, description, column_id', 'required'),
			array('column_id', 'numerical', 'integerOnly' => true),
			array('title', 'length', 'max' => 255),
			array('deadline', 'date', 'format' => 'd.m.Y'),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, description, deadline, column_id, created_at', 'safe', 'on' => 'search'),
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
			'cardMembers' => array(self::HAS_MANY, 'CardMembers', 'card_id'),
			'column' => array(self::BELONGS_TO, 'Columns', 'column_id'),
			'cardTags' => array(self::HAS_MANY, 'CardTags', 'card_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'deadline' => 'Deadline',
			'column_id' => 'Column',
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

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('deadline', $this->deadline, true);
		$criteria->compare('column_id', $this->column_id);
		$criteria->compare('created_at', $this->created_at, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cards the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
