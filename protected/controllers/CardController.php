<?php

class CardController extends Controller
{



	public function actionView($id)
	{
		header('Content-type: application/json');
		// $isAjax = Yii::app()->request->isAjaxRequest;
		// var_dump($isAjax);die;

		$card = $this->loadModel($id);	
		$cardTags = CardTags::model()->with('tag')->findAll("card_id = :card_id", ["card_id" =>$id]);
		$card_members = CardMembers::model()->with('user')->findAll("card_id = :card_id", ["card_id" =>$id]);
		echo CJSON::encode(["card" => $card,"tags" => $this->convertModelToArray($cardTags),"card_members" => $this->convertModelToArray($card_members)]);
	}

	public function actionDelete($id){
		$this->loadModel($id)->delete();

		$this->redirect(Yii::app()->request->urlReferrer);
	}


	// public function actionUpdate($id){

	// 	$model=$this->loadModel($id);
	// 	$tags = Tags::model()->with('color')->findAll('board_id = :board_id', [':board_id' => $model->column->board_id]);
	// 	$board_member =  BoardMembers::model()->with('user')->findAll('board_id = :board_id', [':board_id' => $model->column->board_id]);
	// 	$colors = Colors::model()->findAll();
	// 	if(isset($_POST['Cards']))
	// 	{

			
	// 		if(isset($_POST['Cards']['tag_id'])){
	// 		CardTags::model()->deleteAll('card_id = :card_id', ['card_id' => $id]);
	// 		foreach($_POST['Cards']['tag_id'] as $tag){
	// 			$card_tags = new CardTags;
	
	// 			$card_tags['card_id'] = $id;
	// 			$card_tags['tag_id'] = $tag;
	// 			$card_tags->save();
	// 		}
	// 	}

		
	// 	if(isset($_POST['Cards']['card_member_id'])){
	// 		CardMembers::model()->deleteAll('card_id = :card_id', ['card_id' => $id]);
	// 		foreach($_POST['Cards']['card_member_id'] as $card_member){
	// 			$card_tags = new CardMembers;
	
	// 			$card_tags['card_id'] = $id;
	// 			$card_tags['user_id'] = $card_member;
	// 			$card_tags->save();
	// 		}
	// 	}

	// 		// print_r($_POST['Cards']['deadline']);die;
	// 		$model->attributes=$_POST['Cards'];
	// 		if(!$_POST['Cards']['deadline']){
	// 			$model->deadline = null;
	// 		}
	// 		if($model->save()){
				
	// 			Yii::app()->user->setFlash('success', $id);

	// 			$this->redirect("/board/view/id/".$model->column->board_id);
	// 		}
	// 	}

	// 	$this->render('update',array(
	// 		'model' => $model,
	// 		"tags" => $tags,
	// 		"colors" => $colors,
	// 		'board_members' => $board_member
	// 	));
	// }

	public function actionUpdate(){

		try{
			$this->checkAjax();

			$model = $this->loadModel($_POST['card_id']);

			$model->title = @$_POST['title'];
			$model->description = @$_POST['description'];

			if(!$model->save()){
				$this->getError($model);
			}

			echo CJSON::encode([
				'ok' => true,
				"data" => $model
			]);
		}catch(Exception $error) {
			$httpVersion = Yii::app()->request->getHttpVersion();
			header("HTTP/$httpVersion 400");

			echo CJSON::encode([
				'ok' => false,
				"msg" => $error->getMessage()
			]);
		}
	}


	public function loadModel($id)
	{
		$model=Cards::model()->with('cardMembers','cardTags')->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
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