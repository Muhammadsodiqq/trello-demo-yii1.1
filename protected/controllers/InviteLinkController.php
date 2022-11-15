<?php

class InviteLinkController extends Controller
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
		$isExists = BoardMembers::model()->find('user_id = :user_id AND board_id = :board_id ', ['user_id' => $id, 'board_id' => $invite->board_id]);
		if ($isExists) {
			$this->redirect('/board/view/id/' . $invite->board_id);
		}
		$model->board_id = $invite->board_id;
		$model->user_id = $id;

		if ($model->save()) {
			$this->redirect('/board/view/id/' . $invite->board_id);
		}
	}


	public function actionGenerate($board_id)
	{
		$model = new InviteLinks;

		$isOwn = Boards::model()->findByPk($board_id);

		if ($isOwn->user_id != Yii::app()->user->id) {
			throw new Exception('access denied');
		}

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
}
