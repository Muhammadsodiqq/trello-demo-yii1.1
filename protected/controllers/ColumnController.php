<?php

class ColumnController extends Controller
{
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
				"model" => $this->renderPartial("_form_board",["model"=>$model],true,true),
			]);

		}catch(Exception $error){
			echo CJSON::encode([
				'ok' => false,
				"msg" => $error->getMessage(),
			]);
		}

	}
}
