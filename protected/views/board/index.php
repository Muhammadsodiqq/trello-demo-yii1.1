<?php
/* @var $this UserController */

$this->breadcrumbs = array(
	'Board',
);
?>
<?php if (Yii::app()->user->hasFlash('error')) { ?>
		<div class="alert alert-danger" role="alert">
			 <strong><?php echo Yii::app()->user->getFlash('error'); ?></strong>
		</div>
	<?php } ?>
<h1>Sizning doskalaringiz</h1>
<div class="list-group" id="list_board">
	<?php foreach ($user_boards as $user_board) { ?>
		<div></div>
		<a href="/board/view/id/<?= $user_board->id ?>" class="list-group-item list-group-item-action d-flex justify-content-between">
			<?= $user_board->name ?>
			<a href="<?php echo Yii::app()->createUrl('Board/DeleteBoard', ["id" => $user_board->id]); ?>" class="d-inline-block btn btn-danger btn-sm">Delete</a>
		</a>
	<?php } ?>
</div>
<?php if (Yii::app()->user->checkAccess("Board.Create")) { ?>

	<button type="button" id="addColumn" class="btn btn-primary mt-2" data-toggle="modal" data-target="#myModal">
		doska qo'shish
	</button>
<?php } ?>
<div class="list-group">
	<h3><?= !$member_boards ? "" : "Siz ulangan do'skalar" ?></h3>
	<?php foreach ($member_boards as $member_board) { ?>
		<a href="/board/view/id/<?= $member_board['id'] ?>" class="list-group-item list-group-item-action">
			<?= $member_board['name'] ?>
		</a>
	<?php } ?>
</div>


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

<script>
	if (window.history.replaceState) {
		window.history.replaceState(null, null, window.location.href);
	}

	function send(url, formdata = null, type = null) {
		$.ajax({
			url: url,
			type: 'POST',
			data: formdata,
			dataType: 'json',
			success: function(data) {
				if (data.ok == false) {
					$("#main-modal").html(data.model)

					$("#modal_saver").click(function(e) {
						console.log(
							'bos'
						);
						e.preventDefault()
						send(url, $("#board-form").serialize(), 'column')
						return;
					});
					return;

				} else {
					$("#myModal").toggle('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();

					// if (type == 'column') {
					$("#list_board").html($("#list_board").html() + `<a href="/board/view/id/${data.data.id}" class="list-group-item list-group-item-action d-flex justify-content-between">
				${data.data.name}
					<a href="/board/DeleteBoard/id/${data.data.id}" class="d-inline-block btn btn-danger btn-sm">Delete</a>`)

					$('.modal').removeClass('in');
					$('.modal').attr("aria-hidden", "true");
					$('.modal').css("display", "none");
					$('.modal-backdrop').remove();
					$('body').removeClass('modal-open');
					$("#inputEmail4").val('')
					$("#error").addClass("d-none")
					// }
				}
			},
			error: function(request, error) {
				console.log($("#error"));
			}
		});
	}
	$("#addColumn").click(function() {
		send(`<?php echo Yii::app()->createUrl('Board/Create'); ?>`)
	})
</script>
<!-- <script>
	$("#submit").click(function(e) {
		e.preventDefault();
		// console.log($("#inputEmail4").val());
		$.ajax({
			url: "<?php echo Yii::app()->createUrl('Board/Create'); ?>",
			type: 'POST',
			data: {
				id: "<?php echo Yii::app()->user->id; ?>",
				name: $("#inputEmail4").val()
			},
			dataType: 'json',
			success: function(data) {

				$("#list_board").html($("#list_board").html() + `<a href="/board/view/id/${data.data.id}" class="list-group-item list-group-item-action d-flex justify-content-between">
				${data.data.name}
					<a href="/board/DeleteBoard/id/${data.data.id}" class="d-inline-block btn btn-danger btn-sm">Delete</a>`)

				$('.modal').removeClass('in');
				$('.modal').attr("aria-hidden", "true");
				$('.modal').css("display", "none");
				$('.modal-backdrop').remove();
				$('body').removeClass('modal-open');
				$("#inputEmail4").val('')
				$("#error").addClass("d-none")
				$("#error").html('')
			},
			error: function(request, error) {

				$("#error").removeClass("d-none")
				$("#error").html(`<strong>Error!</strong> .${request.responseJSON.msg}`)
			}
		});
	});
</script> -->