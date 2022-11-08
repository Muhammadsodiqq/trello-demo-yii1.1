<div class="modal fade shadow-lg p-3 mb-5 bg-secondary rounded" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="alert alert-danger d-none" id="CardMemberUpdateerror"></div>

			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- Foydalanuvchi qo'shish -->
				<button id="submit_user_add" class="btn btn-primary m-3">save</button>
				<select id="generals" name="generals" class="form-control kt-selectpicker" multiple title="Choose one of the following...">

				</select>
			</div>
		</div>
	</div>
</div>

<!-- get board members -->
<script>
	adduserbtn.addEventListener('click', function(e) {
		e.preventDefault()

		$.ajax({
			url: `<?php echo Yii::app()->createUrl('Board/GetBoardMembers'); ?>`,
			type: 'POST',
			data: {
				id: "<?php echo Yii::app()->user->id; ?>",
				card_id: card_id,
			},
			dataType: 'json',
			success: function(data) {
				$("#generals").html('')
				data.data.forEach(function(e) {
					$("#generals").append(`<option value='${e.user_id}' ${e.is_card_member ? 'selected' : ''}>${e.username}</option>`)
				})
				$('option').mousedown(function(e) {
					e.preventDefault();
					$(this).prop('selected', !$(this).prop('selected'));
					return false;
				});
			},
			error: function(request, error) {
				console.log("Request: " + JSON.stringify(request));

			}
		});
	})

	submit_user_add.addEventListener('click', function(e) {
		e.preventDefault()
		$.ajax({
			url: `<?php echo Yii::app()->createUrl('Card/UpdateCardMember'); ?>`,
			type: 'POST',
			data: {
				id: "<?php echo Yii::app()->user->id; ?>",
				card_id: card_id,
				card_member_id: $("#generals").val()
			},
			dataType: 'json',
			success: function(data) {
				console.log(data);
				if (data.data) {
					members.innerHTML = ''

					for (const key in data.data) {
						console.log(data.data[key]);
						member_label.innerText = 'Userlar:'
						members.innerHTML = members.innerHTML + `<a class="alert ml-2 alert-info">${data.data[key].user.username}</a>`
					}
				}
				$("#addUser").modal("toggle")
				$("#CardMemberUpdateerror").addClass("d-none")

			},
			error: function(request, error) {
				console.log("Request: " + JSON.stringify(request));
				$("#CardMemberUpdateerror").html(`<strong>Error!</strong> ${request.responseJSON.msg}`)
				$("#CardMemberUpdateerror").removeClass("d-none")
			}
		});
	})
</script>
