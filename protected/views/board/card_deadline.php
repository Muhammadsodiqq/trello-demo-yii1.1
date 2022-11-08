<div class="modal fade  bg-white " id="addDeadline" style="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="alert alert-danger d-none" id="CardDeadlineUpdateerror"></div>

			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="/board/view/id/" method="POST" class="row g-3">
					<div class="col-md-6">
						<label for="inputEmail4" class="form-label">Name</label>
						<input type="date" required class="form-control" id="Card_deadline" name="Card[deadline]" placeholder="name" />
						<button type="submit" id="deadline_submit" class="btn btn-primary mt-4">Save changes</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- update  card deadline -->
<script>
	deadline_submit.addEventListener('click', function(e) {
		e.preventDefault()

		$.ajax({
			url: `<?php echo Yii::app()->createUrl('Card/UpdateDeadline'); ?>`,
			type: 'POST',
			data: {
				id: "<?php echo Yii::app()->user->id; ?>",
				deadline: $("#Card_deadline").val(),
				card_id: card_id
			},
			dataType: 'json',
			success: function(data) {
				console.log(data.data.deadline);
				card_date.innerText = data.data.deadline
				deadline_label.innerText = data.data.deadline ? "Muddat" : ""
				$("#addDeadline").modal("toggle")
			},
			error: function(request, error) {
				console.log("Request: " + JSON.stringify(request));
				$("#CardDeadlineUpdateerror").html(`<strong>Error!</strong> ${request.responseJSON.msg}`)
				$("#CardDeadlineUpdateerror").removeClass("d-none")
			}
		});
	})
</script>