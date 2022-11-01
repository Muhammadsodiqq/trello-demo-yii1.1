<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/css/board/main.css');

$is_own = Boards::model()->findByPk($id)->user_id == Yii::app()->user->id;


?>

<!-- </style> -->
<?php if (Yii::app()->user->hasFlash('notice')) { ?>
	<div class="alert alert-info" role="alert">
		invite link: <strong><?php echo Yii::app()->user->getFlash('notice'); ?></strong>
	</div>
<?php } ?>

<?php if ($is_own) { ?>
	<a href="/inviteLink/generate/board_id/<?= $id ?>" type="button" class="btn btn-info ml-auto">
		invite link
	</a>
<?php } ?>

<div class="board">
	<?php foreach ($columns as $column) { ?>
		<div class="taskColumn" id="<?= $column->title ?>" column_id="<?= $column->id ?>">
			<?php if ($is_own) { ?>
				<button type="button" class="w-100 mb-1  btn btn-success ml-auto" id="columnbtn" column_id="<?= $column->id ?>" data-toggle="modal" data-target="#myModal1">
					card qo'shish
				</button>
			<?php } ?>
			<div class="colHdr"><strong><?= $column->title ?></strong></div>

			<?php foreach ($column->cards as $card) { ?>
				<div class="taskDiv" data-toggle="modal" data-target="#myModal2" draggable="true" id="<?= $card->id ?>"><span><strong><?= $card->title ?></strong></span></div>
			<?php } ?>
		</div>
	<?php } ?>
	<?php if ($is_own) { ?>

		<button type="button" class="taskColumnAdd btn btn-info ml-auto" data-toggle="modal" data-target="#myModal">
			list qo'shish
		</button>

	<?php } ?>

	<ul style="position:absolute; margin-left: -260px;" class="list-group list-group-flush member_list">

		<li class="list-group-item list-group-item-action">Foydalanuvchilar</li>
		<li class="list-group-item">Admin: <?= $BoardAdmin?> </li>

		<?php foreach ($board_members as $board_member) { ?>
			<li class="list-group-item"><?= $board_member->user->username?></li>
		<?php } ?>

	</ul>
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
				<h5 class="modal-title" id="exampleModalLabel">Card qo'shish</h5>
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
						<a href="#" id="column_delete" class="btn btn-danger mt-4">Column Delete</a>

					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade " id="myModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="card_title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<label for="card_text" class="font-weight-bold">Izoh:</label>
				<p class="font-weight-normal" id="card_text"> </p>
				<label for="card_date" id="deadline_label" class="font-weight-bold"></label>
				<p class="font-weight-normal" id="card_date"> </p>

				<label for="tags" id="tag_label" class="font-weight-bold"></label>
				<div id="tags">
				</div>

				<label for="tags" id="member_label" class="font-weight-bold"></label>
				<div id="members">

				</div>
				<?php if ($is_own) { ?>
					<a href="#" id="card_edit" type="submit" class="btn btn-primary mt-4">Edit</a>
					<a href="#" id="card_delete" class="btn btn-danger mt-4">Delete</a>
				<?php } ?>

			</div>
		</div>
	</div>
</div>


<script>
	let btn = document.querySelectorAll("#columnbtn")

	btn.forEach((e) => {
		e.addEventListener('click', function(e) {
			let btn = document.querySelector("#inp-hidden")
			btn.value = e.target.getAttribute('column_id')
			column_delete.href = '/column/delete/id/' + e.target.getAttribute('column_id')
		})
	})

	let btn1 = document.querySelectorAll(".taskDiv")

	btn1.forEach((e) => {
		e.addEventListener('click', function(e) {
			getCard(e.target.id);
		})
	})

	function getCard(id) {
		$.ajax({
			url: '/card/view/id/' + id,
			type: 'GET',
			data: arr,
			dataType: 'json',
			success: function(data) {
				card_text.innerText = data.card.description
				card_title.innerText = data.card.title
				card_date.innerText = data.card.deadline
				tags.innerHTML = ''
				members.innerHTML = ''
				deadline_label.innerText = data.card.deadline ? "Muddat" : ""
				if (data.tags) {
					for (const key in data.tags) {
						tag_label.innerText = 'Teglar:'
						tags.innerHTML = tags.innerHTML + `<button type="button" style="background-color: ${data.tags[key].tag.color.name};color:#17505e; " class="btn m-1">${data.tags[key].tag.name}</button>`
					}
				}
				if (data.card_members) {
					for (const key in data.card_members) {
						member_label.innerText = 'Userlar:'
						members.innerHTML = members.innerHTML + `<a class="alert ml-2 alert-info">${data.card_members[key].user.username}</a>`
					}
				}
				if(card_delete){
					card_delete.href = `/card/delete/id/${id}`
					card_edit.href = `/card/update/id/${id}`

				}
			},
			error: function(request, error) {
				console.log("Request: " + JSON.stringify(request));
			}
		});
	}
</script>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/board.js"></script>
<?php if (Yii::app()->user->hasFlash('success')) { ?>

	<script>
		$(document).ready(function() {
			$("#myModal2").modal('show');
			getCard(<?php echo Yii::app()->user->getFlash('success'); ?>)
		});
	</script>

<?php } ?>