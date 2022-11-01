<?php

class TagController extends Controller
{
	public function actionCreate()
	{
		$model=new Tags;

		if(isset($_POST['Tags']))
		{
			$model->attributes=$_POST['Tags'];
			$board_id = Cards::model()->findByPk($_POST['Tags']['card_id'])->column->board_id;
			$model['board_id'] = $board_id;
			if($model->save())
				$card_tags = new CardTags;
				// print_r($model);die;

				$card_tags['card_id'] = $_POST['Tags']['card_id'];
				$card_tags['tag_id'] = $model->id;
				$card_tags->save();
				$this->redirect(Yii::app()->request->urlReferrer);
		}
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}