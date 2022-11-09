<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends RController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();

	public function convertModelToArray($models)
	{

		if (is_array($models))

			$arrayMode = TRUE;

		else {

			$models = array($models);

			$arrayMode = FALSE;
		}


		$result = array();

		foreach ($models as $model) {

			$attributes = $model->getAttributes();

			$relations = array();

			foreach ($model->relations() as $key => $related) {

				if ($model->hasRelated($key)) {

					$relations[$key] = $this->convertModelToArray($model->$key);
				}
			}

			$all = array_merge($attributes, $relations);


			if ($arrayMode)

				array_push($result, $all);

			else

				$result = $all;
		}

		return $result;
	}

	public function checkAjax($access_name)
	{
		if (!Yii::app()->request->isAjaxRequest) {
			throw new Exception('Invalid request');
		}

	}

	public function checkPermission($access_name, $user_id)
	{
		$permission = Yii::app()->getAuthManager()->checkAccess($access_name, $user_id, []);
		if (!$permission) {
			throw new Exception('this user has not permission!');
		}

		return true;
	}

	public function getError($model)
	{
		foreach ($model->getErrors() as $attribute => $error) {

			foreach ($error as $message) {

				throw new Exception($message);
			}
		}
	}

	public function errorCatch($error)
	{
		$httpVersion = Yii::app()->request->getHttpVersion();
		header("HTTP/$httpVersion 400");

		echo CJSON::encode([
			'ok' => false,
			"msg" => $error->getMessage(),
		]);
	}

	protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
