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
		$model = Columns::model()->findByPk($id)->delete();

		$this->redirect(Yii::app()->request->urlReferrer);
	}

	public function actionCreate($board_id)
	{
		// header('Content-type: application/json');

		try {
			$this->checkAjax('Column.Create');

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
