<?php

use Boards;
use Exception;
use Controller;

class BoardController extends Controller
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
            array(
                'allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'create', 'update', 'UpdateCardColumn', 'DeleteBoard'),
                'users' => array('@'),
            ),
            array(
                'allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('UpdateCardColumn', 'GetBoardMembers'),
                'users' => array('*'),
            ),
            array(
                'deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $id = Yii::app()->user->id;
        $user = Users::model()->findByPk($id);

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
        try {
            $this->checkAjax('Board.Create');

            $model = new Boards;
            $model->name = @$_POST['name'];
            $model->user_id = @$_POST['id'];
            if (!$model->save()) {
                foreach ($model->getErrors() as $attribute => $error) {

                    foreach ($error as $message) {

                        throw new Exception($message);
                    }
                }
            }
            echo CJSON::encode([
                'ok' => true,
                "data" => $model,
            ]);
        } catch (Exception $error) {

            $httpVersion = Yii::app()->request->getHttpVersion();
            header("HTTP/$httpVersion 400");

            echo CJSON::encode([
                'ok' => false,
                "msg" => $error->getMessage(),
            ]);
        }
    }

    public function actionView($id)
    {

        $columns = Columns::model()->byid()->with([
            'cards' => [
                "order" => "cards.id ASC"
            ]
        ])->findAll('board_id = :board_id', [':board_id' => $id]);
        $board_members = BoardMembers::model()->with('user')->findAll('board_id = :board_id', [':board_id' => $id]);
        $BoardAdmin = Boards::model()->with('user')->findByPk($id)->user->username;

        $colors = Colors::model()->findAll();

        $this->render('view', [
            'columns' => $columns,
            'id' => $id,
            'board_members' => $board_members,
            'BoardAdmin' => $BoardAdmin,
            "colors" => $colors,
        ]);
    }

    public function actionUpdateCardColumn($column_id)
    {
        $data = $_POST;
        header('Content-type: application/json');

        if (isset($data["card_id"])) {
            $model = Cards::model()->findByPk($data["card_id"]);
            $model->column_id = $data["column_id"];
            $model->update();

            echo CJSON::encode(["ok" => true]);
        }
    }

    public function actionDeleteBoard($id)
    {
        $this->checkPermission('Board.DeleteBoard', Yii::app()->user->id);
        $model = Boards::model()->findByPk($id)->delete();

        $this->redirect(Yii::app()->request->urlReferrer);
    }

    public function actionGetBoardMembers()
    {
        try {
            $this->checkAjax('Board.GetBoardMembers');

            $member_boards = Yii::app()->db->createCommand('SELECT b.id,b.name,bm.user_id, u.username
			FROM board_members bm
			LEFT JOIN boards b
			ON b.id = bm.board_id
			LEFT JOIN users u 
			ON bm.user_id = u.id
			WHERE b.user_id =:user_id')->bindValue(':user_id', $_POST['id'])->queryAll();

            $arr = [];
            foreach ($member_boards as $board_member) {
                $card_member = CardMembers::model()->find('user_id = :user_id AND card_id = :card_id', ['user_id' => $board_member['user_id'], 'card_id' => $_POST['card_id']]);
                if ($card_member) {
                    $board_member['is_card_member'] = true;
                } else {
                    $board_member['is_card_member'] = false;
                }
                array_push($arr, $board_member);
            }
            echo CJSON::encode([
                'ok' => true,
                "data" => $arr,
            ]);
        } catch (Exception $error) {
            $httpVersion = Yii::app()->request->getHttpVersion();
            header("HTTP/$httpVersion 400");

            echo CJSON::encode([
                'ok' => false,
                "msg" => $error->getMessage(),
            ]);
        }
    }
}
