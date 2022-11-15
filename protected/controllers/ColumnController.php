<?php

class ColumnController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}


	public function actionDelete($id)
	{
		$model = Columns::model()->findByPk($id);
		if (@$model['column']['board']['user_id'] != Yii::app()->user->id) {
			throw new Exception('access denied');
		}
		$model->delete();

		$this->redirect(Yii::app()->request->urlReferrer);
	}

	public function actionCreate($board_id)
	{

		try {
			$this->checkAjax();
			$isOwn = Boards::model()->findByPk($board_id);

			if (@$isOwn->user_id != Yii::app()->user->id) {
				throw new Exception('access denied');
			}
			$model = new Columns;
			$this->performAjaxValidation($model);
			if (isset($_POST['Columns'])) {
				$model->title = @$_POST['Columns']['title'];
				$model->board_id = $board_id;
				if ($model->save()) {
					echo CJSON::encode([
						'ok' => true,
						"data" => $model
					]);
					exit;
				}
			}
			echo CJSON::encode([
				'ok' => false,
				"model" => $this->renderPartial("_form_board", ["model" => $model], true, true),
			]);
		} catch (Exception $error) {
			echo CJSON::encode([
				'ok' => 'error',
				"msg" => $error->getMessage(),
			]);
		}
	}
}
