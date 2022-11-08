
<div class="modal fade shadow-inner bg-white " id="addDTag" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="alert alert-danger d-none" id="CardTagUpdateerror"></div>

			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="alert alert-success d-none" id="teg_alert" role="alert">
						changed succesfuly
				</div>
				<div class="d-flex">
					<form action="/tag/create/" method="POST" class="row g-3">
						<div class="col-md-6">
							<label for="inputEmail4" class="form-label">Name</label>
							<input type="text" required class="form-control" id="tag_value" name="Tags[name]" placeholder="name" />
							<select class="form-select" name="Tags[color_id]" id="color_id" style="margin-top: 10px; display: block;">
								<?php foreach ($colors as $color) { ?>
									<option value="<?= $color->id ?>"><?= $color->name ?></option>
								<?php } ?>

							</select>
							<button type="submit" id="tag_submit" class="btn btn-primary mt-4">Save changes</button>
						</div>
					</form>
					<hr>
					<div class="m-2 tags_check">

					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- update card tag -->
<script>
	tag_submit.addEventListener('click', function(e) {
		e.preventDefault()

		$.ajax({
			url: `<?php echo Yii::app()->createUrl('Tag/Create'); ?>`,
			type: 'POST',
			data: {
				id: "<?php echo Yii::app()->user->id; ?>",
				name: $("#tag_value").val(),
				card_id: card_id,
				board_id: "<?= $id ?>",
				color_id: color_id.value
			},
			dataType: 'json',
			success: function(data) {
				console.log(data);
				tag_label.innerText = 'Teglar:'
				tags.innerHTML = tags.innerHTML + `<button type="button" tag_id="${data.data.id}" style="background-color: ${data.data.color.name};color:#17505e; " class="btn m-1">${data.data.name}</button>`
				$(".tags_check").html($(".tags_check").html() + `<div class="form-check">
							<input class="tag_checkbox form-check-input" type="checkbox" value="${data.data.id}" id="${data.data.id}" 'checked' >
							<label class="form-check-label" for="${data.data.id}">	
								${data.data.name}
							</label>
						</div>`)
				$("#addDTag").modal("toggle")
				$("#CardTagUpdateerror").addClass("d-none")

			},
			error: function(request, error) {
				console.log("Request: " + JSON.stringify(request));
				$("#CardTagUpdateerror").html(`<strong>Error!</strong> ${request.responseJSON.msg}`)
				$("#CardTagUpdateerror").removeClass("d-none")
			}
		});
	})
</script>

<!-- get tag -->
<script>
	addtagbtn.addEventListener("click", function(e) {
		e.preventDefault()

		$.ajax({
			url: `<?php echo Yii::app()->createUrl('Tag/GetTags'); ?>`,
			type: 'POST',
			data: {
				id: "<?php echo Yii::app()->user->id; ?>",
				card_id: card_id,
				board_id: "<?= $id ?>",
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
							if(data.data){
								console.log(teg_id);
								console.log(data.data.id);
								tags.innerHTML = tags.innerHTML + `<button type="button" tag_id="${data.data.id}" style="background-color: ${data.data.color.name};color:#17505e; " class="btn m-1">${data.data.name}</button>`
							}else{
								document.querySelector("[tag_id ='" +  teg_id + "']").remove();
							}
							
							setTimeout(function() {
								$("#addDTag").modal("toggle")
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
			},
			error: function(request, error) {
				console.log("Request: " + JSON.stringify(request));
			}
		});
	})
</script>