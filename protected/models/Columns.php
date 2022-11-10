<?php

/**
 * This is the model class for table "columns".
 *
 * The followings are the available columns in table 'columns':
 * @property integer $id
 * @property string $title
 * @property integer $board_id
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Boards $board
 * @property Cards[] $cards
 */
class Columns extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'columns';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, board_id', 'required'),
			array('board_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('title', 'uniqueOnBoard', 'max'=>255),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, board_id, created_at', 'safe', 'on'=>'search'),
		);
	}

	public function uniqueOnBoard($attribute,$params){
		$isExsists = $this::model()->find('title = :title AND board_id = :board_id',["title" => $this->title,"board_id" => $this->board_id]);

		if($isExsists){
			$this->addError($attribute, 'this column already exsists on this board');
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'board' => array(self::BELONGS_TO, 'Boards', 'board_id'),
			'cards' => array(self::HAS_MANY, 'Cards', 'column_id'),
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
			'board_id' => 'Board',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('board_id',$this->board_id);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Columns the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function scopes() {
		return array(
			'byid' => array('order' => 't.id ASC'),
		);
	}
}
