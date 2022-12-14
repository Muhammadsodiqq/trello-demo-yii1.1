<?php

/**
 * This is the model class for table "tags".
 *
 * The followings are the available columns in table 'tags':
 * @property integer $id
 * @property string $name
 * @property integer $color_id
 * @property integer $board_id
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property CardTags[] $cardTags
 * @property Colors $color
 * @property Boards $board
 */
class Tags extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tags';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, color_id, board_id', 'required'),
			array('color_id, board_id', 'numerical', 'integerOnly'=>true),
			array('name','uniqueOnColumn', 'length', 'max'=>255),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, color_id, board_id, created_at', 'safe', 'on'=>'search'),
		);
	}

	public function uniqueOnColumn($attribute,$params)
	{
		if($this->board_id && $this->name && $this->color_id){

			$isExists = Tags::model()->find('board_id = :board_id AND name = :name AND color_id = :color_id',["board_id" => $this->board_id, "name" => $this->name,"color_id" => $this->color_id]);
			if($isExists){
				$this->addError($attribute, 'this teg already exsists on this board');
			}
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
			'cardTags' => array(self::HAS_MANY, 'CardTags', 'tag_id'),
			'color' => array(self::BELONGS_TO, 'Colors', 'color_id'),
			'board' => array(self::BELONGS_TO, 'Boards', 'board_id'),
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
			'color_id' => 'Color',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('color_id',$this->color_id);
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
	 * @return Tags the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
