<div class="modal fade shadow-inner bg-white " id="mySubModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="alert alert-danger d-none" id="sub_error">

			</div>
			<div class="alert alert-success d-none" id="teg_alert" role="alert">
				changed succesfuly
			</div>
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>

			</div>
			<div class="modal-body" id="sub-modal">

			</div>
		</div>
	</div>
</div>

<script>
	$('#mySubModal').on('hidden.bs.modal', function() {
		$('body').removeClass('modal-open');
		$('.modal-backdrop').remove();
		$("#sub_error").addClass("d-none")

	})
</script>