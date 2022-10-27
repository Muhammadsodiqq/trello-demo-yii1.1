<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/board/main.css');
?>

<!-- </style> -->

<div class="board">


	<div class="taskColumn" id="todo">
		<div class="colHdr" dragabble="true"><strong>TO DO</strong></div>
		<div class="taskDiv" draggable="true" id="dragtarget1"><span><strong>Task 1</strong></span></div>
		<div class="taskDiv" draggable="true" id="dragtarget2"><span><strong>Task 2</strong></span></div>
		<div class="taskDiv" draggable="true" id="dragtarget3"><span><strong>Task 3</strong></span></div>
		<div class="taskDiv" draggable="true" id="dragtarget4"><span><strong>Task 4</strong></span></div>
		<div class="taskDiv" draggable="true" id="dragtarget5"><span><strong>Task 5</strong></span></div>
		<div class="taskDiv" draggable="true" id="dragtarget6"><span><strong>Task 6</strong></span></div>
		<div class="taskDiv" draggable="true" id="dragtarget7"><span><strong>Task 7</strong></span></div>
		<div class="taskDiv" draggable="true" id="dragtarget8"><span><strong>Task 8</strong></span></div>
		<div class="taskDiv" draggable="true" id="dragtarget9"><span><strong>Task 9</strong></span></div>
		<div class="taskDiv" draggable="true" id="dragtarget10"><span><strong>Task 10</strong></span></div>
	</div>
	<?php foreach ($columns as $column) { ?>
		<div class="taskColumn" id="<?= $column->title ?>">
			<button type="button" class="w-100 mb-1  btn btn-success ml-auto" id="columnbtn" column_id="<?= $column->id ?>" data-toggle="modal" data-target="#myModal1">
				card qo'shish
			</button>
			<div class="colHdr"><strong><?= $column->title ?></strong></div>
		</div>
	<?php } ?>
	<button type="button" class="taskColumnAdd btn btn-info ml-auto" data-toggle="modal" data-target="#myModal">
		list qo'shish
	</button>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
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
						<input type="text" required class="form-control" id="inputEmail4" name="Board[title]" placeholder="name" />
						<button type="submit" class="btn btn-primary mt-4">Save changes</button>
					</div>
				</form>
			</div>

		</div>
	</div>
</div>

<div class="modal fade " id="myModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
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

					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/board.js"></script>

<script>
	let btn = document.querySelectorAll("#columnbtn")

	btn.forEach((e) => {
		e.addEventListener('click', function(e) {
			let btn = document.querySelector("#inp-hidden")
			btn.value = e.target.getAttribute('column_id')
		})
	})
</script>