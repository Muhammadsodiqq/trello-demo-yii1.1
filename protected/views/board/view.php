<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/board/main.css');

$is_own = Yii::app()->user->checkAccess("");

?>
<input type="hidden" id="b_id" value="<?= $id ?>">
<input type="hidden" id="u_id" value="<?= Yii::app()->user->id; ?>">
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
				<button type="button" class="w-100 mb-1  btn btn-success ml-auto columnbtn" id="columnbtn" column_id="<?= $column->id ?>" data-toggle="modal" data-target="#myModal">
					card qo'shish
				</button>
			<?php } ?>
			<div class="colHdr"><strong><?= $column->title ?></strong></div>

			<?php foreach ($column->cards as $card) {	?>
				<?php if (!Yii::app()->user->checkAccess("admin")) { ?>
					<?php if (CardMembers::model()->find('card_id = :card_id AND user_id = :user_id', ['card_id' => $card->id, 'user_id' => Yii::app()->user->id])) { ?>
						<div class="taskDiv" data-toggle="modal" data-target="#myModal" draggable="true" id="<?= $card->id ?>"><span id="<?= $card->id ?>"><strong id="<?= $card->id ?>"><?= $card->title ?></strong></span></div>

					<?php }	?>
				<?php } else {	?>
					<div role="taskDiv" class="taskDiv d-flex" data-toggle="modal" data-target="#myModal" draggable="true" id="<?= $card->id ?>">
						<span id="<?= $card->id ?>">
							<strong id="<?= $card->id ?>"><?= $card->title ?>
							</strong>
						</span>

						<div id="<?= $card->id ?>" role="members" class="members ml-auto">
							<?php foreach ($card->cardMembers as $member) {	?>
								<a id="<?= $card->id ?>" class="btn ml-2 btn-info btn-sm"><?= $member['user']['username'] ?></a>
							<?php }	?>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	<?php } ?>
	<?php if (Yii::app()->user->checkAccess("Column.Create")) { ?>

		<button type="button" id="taskColumnAdd" class="taskColumnAdd btn btn-info ml-auto" data-toggle="modal" data-target="#myModal">
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
				<div class="modal-body" id="main-modal">

				</div>
			</div>
		</div>
	</div>

<?php } ?>

<?php if (Yii::app()->user->checkAccess("Card.Create")) {

	$this->renderPartial('card_tag');
	$this->renderPartial('card_user',);
} ?>

<script>
	$('#myModal').on('hidden.bs.modal', function() {
		$('body').removeClass('modal-open');
		$('.modal-backdrop').remove();
		$("#error").addClass("d-none")

	})

	function send(url, formdata = null, type = null, status = 0) {
		$.ajax({
			url: url,
			type: 'POST',
			data: formdata,
			dataType: 'json',
			success: function(data) {

				
				if (data.ok == false) {
					$("#main-modal").html(data.model)

					$("#modal_saver").click(function(e) {
						e.preventDefault()
						send(url, $("#board-form").serialize(), type, 1)
						return;
					});
					return;

				} else if (data.ok == true) {

					if (status == 1) {

						if (type == 'column') {
							$("#board").append(`
							<div class="taskColumn" id="${data.data.title}" column_id="${data.data.id}">
								<button type="button" class="w-100 mb-1  btn btn-success ml-auto columnbtn" id="columnbtn" column_id="${data.data.id}" data-toggle="modal" data-target="#myModal1">
									card qo'shish
								</button>
								<div class="colHdr"><strong>${data.data.title}</strong></div>
							</div>`)

							$("#myModal").toggle('hide');

						}
						if (type == 'card') {
							$(`.taskColumn[column_id = ${data.data.column_id}]`).append(`
						<div class="taskDiv" data-toggle="modal" data-target="#myModal" draggable="true" id="${data.data.id}"><span id="${data.data.id}"><strong id="${data.data.id}">${data.data.title}</strong></span></div>
							`);
							$("#myModal").toggle('hide');

						}
						if (type == 'card_view') {
							$("#card_text").text(data.data.description)
							$("#card_title").text(data.data.title)
							$(".type_text").css('display', 'block')
							$(".showthis").css('display', 'none')
							$(".trigger").prop('checked', false)
							$('#modal_saver').addClass("d-none")
							
						}
					}

				} else if (data.ok == "error") {
					$("#error").html(`<strong>Error!</strong> ${request.responseJSON.msg}`)
					$("#error").removeClass("d-none")
				}

				
			},
			error: function(request, error) {
				console.log($("#error"));
			}
		});
	}
	$("#taskColumnAdd").click(function() {
		send(`<?php echo Yii::app()->createUrl('Column/Create', ['board_id' => $id]); ?>`, null, 'column')
	})

	$(".columnbtn").click(function(e) {
		let column_id = $(this).attr("column_id")
		send(`/Card/Create/column_id/${column_id}`, null, 'card')
	})
	$(".taskDiv").click(function(e) {
		// console.log($(this));

		let id = $(this).attr("id");
		send(`/card/view/id/${id}`, null, 'card_view')

	})
</script>
<?php
// if (Yii::app()->user->checkAccess("Card.View")) {

// }

// if (Yii::app()->user->checkAccess("Card.UpdateDeadline")) {
// 	$this->renderPartial('card_deadline');
// }

// if (Yii::app()->user->checkAccess("Card.UpdateCardMember")) {
// 	$this->renderPartial('card_user',);
// }

// if (Yii::app()->user->checkAccess("Card.UpdateCardTag")) {
// 	$this->renderPartial('card_tag', ["colors" => $colors, "id" => $id]);
// }
// 
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