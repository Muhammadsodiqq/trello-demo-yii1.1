<?php
/* @var $this UserController */

$this->breadcrumbs = array(
	'Board',
);
?>

<div class="list-group">
	<h1>Sizning doskalaringiz</h1>
	<?php foreach ($user_boards as $user_board) { ?>
		<a href="/board/view/id/<?= $user_board->id ?>" class="list-group-item list-group-item-action d-flex justify-content-between">
			<?= $user_board->name ?>
			<a href="/board/DeleteBoard/id/<?= $user_board->id ?>" class="d-inline-block btn btn-danger btn-sm">Delete</a>
		</a>
	<?php } ?>
</div>
<button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#myModal">
	doska qo'shish
</button>


<!-- Modal -->
<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
				<form action="/board" method="POST" class="row g-3">
					<div class="col-md-6">
						<label for="inputEmail4" class="form-label">Name</label>
						<input type="text" required class="form-control" id="inputEmail4" name="Board[name]" placeholder="name" />
						<button id="submit" type="submit" class="btn btn-primary mt-4">Save changes</button>
					</div>
				</form>
			</div>

		</div>
	</div>
</div>

<script>
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
				console.log($(".list-group").html());
				$(".list-group").html( $(".list-group").html() + `
				<a href="/board/view/id/${data.data.id}" class="list-group-item list-group-item-action d-flex justify-content-between">
				${data.data.name}
					<a href="/board/DeleteBoard/id/${data.data.id}" class="d-inline-block btn btn-danger btn-sm">Delete</a>
				</a>
				`)
			},
			error: function(request, error) {
				// console.log(request.responseJSON.msg);
				$("#error").removeClass("d-none")
				$("#error").html(`<strong>Error!</strong> .${request.responseJSON.msg}`)
			}
		});
	});
</script>