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
				'actions'=>array('index','view','create','update'),
				'users'=>array('@'),
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
		
		if(isset($_POST['Board'])) {
			$model = new Boards;
			$model->name = $_POST['Board']['name'];
			$model->user_id = $id;
			// print_r($model->user_id);die;
			if($model->save()){
				return $this->redirect('/board');
			}
		}
		$this->render('index', [
			'user_boards' => $user_boards,
			'member_boards' => $member_boards,
		]);
	}

	public function actionView($id)
	{
		
		$columns = Columns::model()->byid()->with('cards')->findAll('board_id = :board_id', [':board_id' => $id]);
		// print_r($columns[0]);die;
		
		if(isset($_POST['Board'])) {
			$model = new Columns;
			$model->title = $_POST['Board']['title'];
			$model->board_id = $id;
			if($model->save()){
				return $this->redirect('/board/view/id/'.$id);
			}
		}

		if(isset($_POST['Card'])) {
			print_r($_POST['Card']);die;
			$model = new Columns;
			$model->title = $_POST['Board']['title'];
			$model->board_id = $id;
			if($model->save()){
				return $this->redirect('/board/view/id/'.$id);
			}
		}

		$this->render('view', [
			'columns' => $columns,
			'id' => $id
		]);
	}
}
