<?php

class InviteLinkController extends Controller
{
	public function actionInvite($token)
	{

		$cookie = new CHttpCookie('tokenLink', $token);
		$cookie->expire = time() + 1800;
		Yii::app()->request->cookies['tokenLink'] = $cookie;

		if (Yii::app()->user->isGuest) {

			$this->redirect('/user/login');
		}
		$invite = InviteLinks::model()->find('token = :token', ['token' => $token]);

		if (!$invite || ($invite->expired_ad < date('Y-m-d H:i:s'))) {
			echo 'this invite link is invalid';
			die;
		}

		unset(Yii::app()->request->cookies['tokenLink']);


		$model = new BoardMembers;
		$id = Yii::app()->user->id;
		$isExists = BoardMembers::model()->find('user_id = :user_id AND board_id = :board_id ', ['user_id' => $id,'board_id' => $invite->board_id]);
		if ($isExists) {
			$this->redirect('/board/view/id/' . $invite->board_id);
		}
		$model->board_id = $invite->board_id;
		$model->user_id = $id;

		if ($model->save()) {
			$this->redirect('/board/view/id/' . $invite->board_id);
		}

		// echo $_COOKIE["tokenLink"];



		// $this->render('index');
	}


	public function actionGenerate($board_id)
	{
		$model = new InviteLinks;


		$oldLink = InviteLinks::model()->find('board_id = :board_id', ['board_id' => $board_id]);

		if ($oldLink && ($oldLink->expired_ad > date('Y-m-d H:i:s'))) {
			Yii::app()->user->setFlash('notice', Yii::app()->getBaseUrl(true) . '/inviteLink/invite/token/' . $oldLink->token);
			$this->redirect(Yii::app()->request->urlReferrer);
		}
		InviteLinks::model()->deleteAll('board_id = :board_id', ['board_id' => $board_id]);
		$token = md5(uniqid(rand(), true));

		$time = strtotime("24 hours");
		$expired_ad = date('Y-m-d H:i:s', $time);

		$model->token = $token;
		$model->board_id = $board_id;
		$model->expired_ad = $expired_ad;
		if ($model->save()) {
			Yii::app()->user->setFlash('notice', Yii::app()->getBaseUrl(true) . '/inviteLink/invite/token/' . $model->token);
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
