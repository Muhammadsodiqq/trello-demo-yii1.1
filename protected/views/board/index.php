<?php
/* @var $this UserController */

$this->breadcrumbs = array(
	'Board',
);
?>

<div class="list-group">
	<h1>Sizning doskalaringiz</h1>
	<?php foreach ($user_boards as $user_board) { ?>
		<a href="/board/view/id/<?= $user_board->id ?>" class="list-group-item list-group-item-action">
			<?= $user_board->name ?>
		</a>
	<?php } ?>
	<button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#myModal">
		doska qo'shish
	</button>
	<div class="list-group">
		<h3><?= !$member_boards ? "" : "Siz ulangan do'skalar" ?></h3>
		<?php foreach ($member_boards as $member_board) { ?>
			<a href="/board/view/id/<?= $member_board->id ?>" class="list-group-item list-group-item-action">
				<?= $member_board->name ?>
			</a>
		<?php } ?>
	</div>
</div>


<!-- Modal -->
<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
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
					<button type="submit" class="btn btn-primary mt-4">Save changes</button>
					</div>
				</form>
			</div>
			
		</div>
	</div>
</div>


