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
<!-- <script>
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
</script> -->



<!-- update card tag -->
<!-- <script>
	tag_submit.addEventListener('click', function(e) {
		e.preventDefault()

		$.ajax({
			url: ``,
			type: 'POST',
			data: {
				id: "",
				name: $("#tag_value").val(),
				card_id: card_id,
				board_id: "",
				color_id: color_id.value
			},
			dataType: 'json',
			success: function(data) {
				console.log(data);
				tag_label.innerText = 'Teglar:'
				tags.innerHTML = tags.innerHTML + `<button type="button" tag_id="${data.data.id}" style="background-color: ${data.data.color.name};color:#17505e; " class="btn m-1">${data.data.name}</button>`
				$(".tags_check").html($(".tags_check").html() + `<div class="form-check">
							<input class="tag_checkbox form-check-input" type="checkbox" value="${data.data.id}" id="${data.data.id}" checked >
							<label class="form-check-label" for="${data.data.id}">	
								${data.data.name}
							</label>
						</div>`)

				checkboxClick()
				$("#CardTagUpdateerror").addClass("d-none")

			},
			error: function(request, error) {
				console.log("Request: " + JSON.stringify(request));
				$("#CardTagUpdateerror").html(`<strong>Error!</strong> ${request.responseJSON.msg}`)
				$("#CardTagUpdateerror").removeClass("d-none")
			}
		});
	})
</script> -->

<!-- get tag -->
<!-- <script>
	addtagbtn.addEventListener("click", function(e) {
		e.preventDefault()

		$.ajax({
			url: `<?php echo Yii::app()->createUrl('Tag/GetTags'); ?>`,
			type: 'POST',
			data: {
				id: "<?php echo Yii::app()->user->id; ?>",
				card_id: card_id,
				board_id: "",
			},
			dataType: 'json',
			success: function(data) {
				$(".tags_check").html("")
				data.data.forEach(function(e) {
					$(".tags_check").html($(".tags_check").html() + `<div class="form-check">
							<input class="tag_checkbox form-check-input" type="checkbox" value="${e.id}" id="${e.id}" ${e.is_card_tag ? 'checked' : ""}>
							<label class="form-check-label" for="${e.id}">	
								${e.name}
							</label>
						</div>`)
				})

				checkboxClick()
			},
			error: function(request, error) {
				console.log("Request: " + JSON.stringify(request));
			}
		});
	})

	function checkboxClick() {
		$(".tag_checkbox").change(function() {
			let is_delete;
			if ($(this).is(":checked")) {
				is_delete = null;
			} else {
				is_delete = true;
			}
			// console.log(is_delete);
			// console.log(this.value);

			let teg_id = this.value
			$.ajax({
				url: `<?php echo Yii::app()->createUrl('Tag/TegControl'); ?>`,
				type: 'POST',
				data: {
					id: "<?php echo Yii::app()->user->id; ?>",
					card_id: card_id,
					tag_id: teg_id,
					is_delete
				},
				dataType: 'json',
				beforeSend: function() {
					$(".tag_checkbox").prop("disabled", true);
				},
				success: function(data) {

					$("#CardTagUpdateerror").addClass("d-none")
					$("#teg_alert").removeClass('d-none');
					if (data.data) {
						console.log(teg_id);
						console.log(data.data.id);
						tags.innerHTML = tags.innerHTML + `<button type="button" tag_id="${data.data.id}" style="background-color: ${data.data.color.name};color:#17505e; " class="btn m-1">${data.data.name}</button>`
					} else {
						document.querySelector("[tag_id ='" + teg_id + "']").remove();
					}

					setTimeout(function() {
						// $("#addDTag").modal("toggle")
						$("#teg_alert").addClass('d-none');
						$(".tag_checkbox").prop("disabled", false);

					}, 1000);

				},
				error: function(request, error) {
					console.log(request.responseJSON);
					$("#CardTagUpdateerror").html(`<strong>Error!</strong> ${request.responseJSON.msg}`)
					$("#CardTagUpdateerror").removeClass("d-none")
				}
			});

		})
	}
</script> -->