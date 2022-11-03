<?php

class BoardController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{

		// var_dump(Yii::app()->user->isGuest);die;
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','update','UpdateCardColumn','delete'),
				'users'=>array('@'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('UpdateCardColumn'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$id = Yii::app()->user->id;
		$user_boards = Boards::model()->findAll('user_id = :user_id', [':user_id' => $id]);

		$member_boards = Yii::app()->db->createCommand('SELECT b.id,b.name,b.user_id
												FROM board_members bm 
												LEFT JOIN boards b
												ON b.id = bm.board_id
												WHERE bm.user_id =:user_id')->bindValue(':user_id', $id)->queryAll();

		$this->render('index', [
			'user_boards' => $user_boards,
			'member_boards' => $member_boards,
		]);
	}

	public function actionCreate()
	{
		try
		{
			$this->checkAjax();
			if(!$this->checkAjax()) {
				// throw new Exception('Invalid request');
				return;
			}
	
			// var_dump($_POST);die;
	
			$model = new Boards;
			$model->name = @$_POST['name'];
			$model->user_id = @$_POST['id'];
			if(!$model->save()){
				throw new Exception	(CActiveForm::validate($model)) ;
			}
			echo CJSON::encode([
				'ok' => true,
				"data" => $model
			]);

		}catch(Exception $error)
		{

			$httpVersion = Yii::app()->request->getHttpVersion();
			header("HTTP/$httpVersion 400");

			echo CJSON::encode([
				'ok' => false,
				"msg" => $error->getMessage() 
			]);

		}
	}

	public function actionView($id)
	{

		$columns = Columns::model()->byid()->with('cards')->findAll('board_id = :board_id', [':board_id' => $id]);
		$board_members = BoardMembers::model()->with('user')->findAll('board_id = :board_id', [':board_id' => $id]);
		$BoardAdmin = Boards::model()->with('user')->findByPk($id)->user->username;
		$colors = Colors::model()->findAll();

		if(isset($_POST['Board'])) {
			$model = new Columns;
			$model->title = $_POST['Board']['title'];
			$model->board_id = $id;
			if($model->save()){
				return $this->redirect('/board/view/id/'.$id);
			}
		}

		if(isset($_POST['Card'])) {
			// print_r($_POST['Card']);die;
			$model = new Cards;
			$model->title = $_POST['Card']['title'];
			$model->description = $_POST['Card']['description'];
			$model->column_id = $_POST['Card']['column_id'];
			if($model->save()){
				return $this->redirect('/board/view/id/'.$id);
			}
		}

		$this->render('view', [
			'columns' => $columns,
			'id' => $id,
			'board_members' => $board_members,
			'BoardAdmin' => $BoardAdmin,
			"colors" => $colors
		]);
	}

	public function actionUpdateCardColumn($column_id)
	{
		$data =  $_POST;
		header('Content-type: application/json');
		
		
		
		if(isset($data["card_id"])) {
			$model = Cards::model()->findByPk($data["card_id"]);
			$model->column_id = $data["column_id"];
			$model->update();
			
			echo CJSON::encode(["ok" => true]);
		}

	}

	public function actionDeleteBoard($id)
	{
		$model=Boards::model()->findByPk($id)->delete();

		$this->redirect(Yii::app()->request->urlReferrer);

	}

	public function checkAjax(){
		if(!Yii::app()->request->isAjaxRequest || !isset($_POST['id'])) {
			throw new Exception('Invalid request');
		}
		if(!Users::model()->findByPk($_POST['id'])){
			throw new Exception('Invalid user');
		}

		return true;
	}
}