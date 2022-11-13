<?php

// use \Boards;
// use \Exception;
// use \Controller;

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
            'rights', // perform access control for CRUD operations
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
            if (isset($_POST['Boards'])) {
                $model->name = @$_POST['Boards']['name'];
                $model->user_id = Yii::app()->user->id;
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
                'ok' => "error",
                "msg" => $error->getMessage(),
            ]);
        }
    }

    public function actionView($id)
    {

        $columns = Columns::model()->byid()->with([
            'cards' => [
                "order" => "cards.id ASC",
                "with" => ['cardMembers' => [
                    "with" => 'user'
                ]]
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
        try {
            $this->checkAjax('Board.UpdateCardColumn');
            $data = $_POST;

            $model = Cards::model()->findByPk($data["card_id"]);

            if (!@$model) {
                throw new Exception('invalid card');
            }

            $model->column_id = $data["column_id"];
            $model->update();

            echo CJSON::encode([
                'ok' => true,
            ]);
        } catch (Exception $error) {
            $this->errorCatch($error);
        }
    }

    public function actionDeleteBoard($id)
    {
        // $this->checkPermission('Board.DeleteBoard', Yii::app()->user->id);
        $model = Boards::model()->findByPk($id);
        if (!@$model->user_id == Yii::app()->user->id) {
            Yii::app()->user->setFlash('error', "you don't have acces for delete this");
            $this->redirect(Yii::app()->request->urlReferrer);
        }
        $model->delete();

        $this->redirect(Yii::app()->request->urlReferrer);
    }

    public function actionGetBoardMembers($card_id)
    {
        try {
            $this->checkAjax('Board.GetBoardMembers');
            $model = new CardMembers;
            $member_boards = Yii::app()->db->createCommand('SELECT b.id,b.name,bm.user_id, u.username
			FROM board_members bm
			LEFT JOIN boards b
			ON b.id = bm.board_id
			LEFT JOIN users u 
			ON bm.user_id = u.id
			WHERE b.user_id =:user_id')->bindValue(':user_id', Yii::app()->user->id)->queryAll();

            $arr = [];
            foreach ($member_boards as $board_member) {
                $card_member = CardMembers::model()->find('user_id = :user_id AND card_id = :card_id', ['user_id' => $board_member['user_id'], 'card_id' => $card_id]);
                if ($card_member) {
                    $board_member['is_card_member'] = true;
                } else {
                    $board_member['is_card_member'] = false;
                }
                array_push($arr, $board_member);
            }
            echo CJSON::encode([
                'ok' => false,
                "model" => $this->renderPartial("_view_card_member", ["model" => $model, 'data' => $arr, 'card_id' => $card_id], true, true),
            ]);
        } catch (Exception $error) {

            echo CJSON::encode([
                'ok' => 'error',
                "msg" => $error->getMessage(),
            ]);
        }
    }

    public function actionCardUserControl($card_id)
    {
        try {
            $this->checkAjax('Board.GetBoardMembers');
            $model = new CardMembers;

            if (isset($_POST['BoardMember'])) {

                $user = BoardMembers::model()->find('user_id = :user_id', ['user_id' => $_POST['BoardMember']['user_id']]);
                if ($user['board']['user_id'] != Yii::app()->user->id) {
                    throw new Exception("invalid member");
                }

                if (@$_POST['BoardMember']['is_delete'] == true) {
                    $delete = CardMembers::model()->find('card_id = :card_id AND user_id = :user_id', ['card_id' => $card_id, 'user_id' => $_POST['BoardMember']['user_id']])->delete();

                    echo CJSON::encode([
                        'ok' => true,
                        "data" => null
                    ]);
                    exit;
                } else {
                    $model['card_id'] = $card_id;
                    $model['user_id'] = $_POST['BoardMember']['user_id'];

                    if (!$model->save()) {
                        $this->getError($model);
                    }
                    echo CJSON::encode([
                        'ok' => true,
                        "data" => $model
                    ]);
                    exit;
                }
            } else {
                throw new Exception('invalid request');
            }
        } catch (Exception $error) {
            echo CJSON::encode([
                'ok' => 'error',
                "msg" => $error->getMessage(),
            ]);
        }
    }
}
