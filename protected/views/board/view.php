<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/board/main.css');

$is_own = Yii::app()->user->checkAccess("");

?>

<?php if (Yii::app()->user->checkAccess("InviteLink.Generate")) { ?>
	<?php if (Yii::app()->user->hasFlash('notice')) { ?>
		<div class="alert alert-info" role="alert">
			invite link: <strong><?php echo Yii::app()->user->getFlash('notice'); ?></strong>
		</div>
	<?php } ?>

	<a href="/inviteLink/generate/board_id/<?= $id ?>" type="button" class="btn btn-info ml-auto">
		invite link
	</a>
<?php } ?>

<div class="board" id="board">
	<?php foreach ($columns as $column) { ?>
		<div class="taskColumn" id="<?= $column->title ?>" column_id="<?= $column->id ?>">
			<?php if (Yii::app()->user->checkAccess("Card.Create")) { ?>
				<button type="button" class="w-100 mb-1  btn btn-success ml-auto" id="columnbtn" column_id="<?= $column->id ?>" data-toggle="modal" data-target="#myModal1">
					card qo'shish
				</button>
			<?php } ?>
			<div class="colHdr"><strong><?= $column->title ?></strong></div>

			<?php foreach (($column->cards) as $card) {	?>
				<?php if (!Yii::app()->user->checkAccess("admin")) { ?>
					<?php if (CardMembers::model()->find('card_id = :card_id AND user_id = :user_id',['card_id' => $card->id, 'user_id' => Yii::app()->user->id])) { ?>
						<div class="taskDiv" data-toggle="modal" data-target="#myModal2" draggable="true" id="<?= $card->id ?>"><span id="<?= $card->id ?>"><strong id="<?= $card->id ?>"><?= $card->title ?></strong></span></div>

					<?php }	?>
				<?php } else {	?>
					<div class="taskDiv" data-toggle="modal" data-target="#myModal2" draggable="true" id="<?= $card->id ?>"><span id="<?= $card->id ?>"><strong id="<?= $card->id ?>"><?= $card->title ?></strong></span></div>
				<?php } ?>
			<?php } ?>
		</div>
	<?php } ?>

	<?php if (Yii::app()->user->checkAccess("Column.Create")) { ?>

		<button type="button" class="taskColumnAdd btn btn-info ml-auto" data-toggle="modal" data-target="#myModal">
			list qo'shish
		</button>

	<?php } ?>

	<ul style="position:absolute; margin-left: -260px;" class="list-group list-group-flush member_list">

		<li class="list-group-item list-group-item-action">Foydalanuvchilar</li>
		<?php if (Yii::app()->user->checkAccess("admin")) { ?>
			<li class="list-group-item">Admin: <?= $BoardAdmin ?> </li>

			<?php foreach ($board_members as $board_member) { ?>
				<li class="list-group-item"><?= $board_member->user->username ?></li>
			<?php } ?>
		<?php } else { ?>
			<li class="list-group-item">Siz: <?= Users::model()->findByPk(Yii::app()->user->id)->username ?> </li>

		<?php } ?>

	</ul>
</div>



<?php if (Yii::app()->user->checkAccess("Column.Create")) { ?>

	<!-- Modal -->

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="alert alert-danger d-none" id="error">

			</div>
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="/board/view/id/<?= $id ?>" method="POST" class="row g-3">
						<div class="col-md-6">
							<label for="inputEmail4" class="form-label">Name</label>
							<input type="text" required class="form-control" id="list_title" name="Board[title]" placeholder="name" />
							<button type="submit" id="list_submit" class="btn btn-primary mt-4">Save changes</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

<?php } ?>

<?php if (Yii::app()->user->checkAccess("Card.Create")) { ?>

	<div class="modal fade " id="myModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Card qo'shish</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="/card/create" method="POST" class="row g-3">
						<div class="col-md-6">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="form-label">Title</label>
								<input type="text" class="form-control" id="exampleFormControlInput1" name="Card[title]" placeholder="name@example.com">
							</div>
							<div class="mb-3">
								<label for="exampleFormControlTextarea1" class="form-label">Description</label>
								<textarea class="form-control" id="exampleFormControlTextarea1" name="Card[description]" rows="3"></textarea>
								<input type="hidden" name="Card[column_id]" id="inp-hidden">
							</div>
							<button type="submit" class="btn btn-primary mt-4">Save changes</button>
							<?php if (Yii::app()->user->checkAccess("Column.Delete")) { ?>

								<a href="#" id="column_delete" class="btn btn-danger mt-4">Column Delete</a>
							<?php } ?>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<?php
if (Yii::app()->user->checkAccess("Card.View")) {
	$this->renderPartial('card_view');
}

if (Yii::app()->user->checkAccess("Card.UpdateDeadline")) {
	$this->renderPartial('card_deadline');
}

if (Yii::app()->user->checkAccess("Card.UpdateCardMember")) {
	$this->renderPartial('card_user',);
}

if (Yii::app()->user->checkAccess("Card.UpdateCardTag")) {
	$this->renderPartial('card_tag', ["colors" => $colors, "id" => $id]);
}
?>





<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/board.js"></script>
<?php if (Yii::app()->user->hasFlash('success')) { ?>

	<script>
		$(document).ready(function() {
			$("#myModal2").modal('show');
			getCard(<?php echo Yii::app()->user->getFlash('success'); ?>)
		});
	</script>

<?php } ?>