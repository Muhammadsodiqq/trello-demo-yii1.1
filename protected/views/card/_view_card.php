<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'board-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        // 'enableAjaxValidation' => true,
        'enableClientValidation' => true,
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="d-flex">
        <div style="min-width: 50%;">
            <label for="card_title" class="font-weight-bold d-block">Title:</label>
            <div class="d-inline">
                <?php if (Yii::app()->user->checkAccess("Card.Update")) { ?>
                    <input type="checkbox" class="ml-2 trigger">
                <?php } ?>
                <div class="row showthis show_text form-group">
                    <?php if (Yii::app()->user->checkAccess("Card.Update")) { ?>
                        <?php echo $form->labelEx($model, 'title'); ?>
                        <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 255, "class" => "form-control", "id" => 'input',)); ?>
                        <?php echo $form->error($model, 'title'); ?>
                    <?php } ?>
                </div><br>
                <h5 class="modal-title type_text" id="card_title"><?= $model->title ?></h5>
            </div>
            <label for="card_text" class="font-weight-bold">Izoh:</label>
            <?php if (Yii::app()->user->checkAccess("Card.Update")) { ?>
                <input type="checkbox" class="ml-2 trigger">
            <?php } ?>
            <div class="row showthis show_area form-group">
                <?php if (Yii::app()->user->checkAccess("Card.Update")) { ?>
                    <?php echo $form->labelEx($model, 'description'); ?>
                    <?php echo $form->textArea($model, 'description', array('size' => 60, 'maxlength' => 255, "class" => "form-control", 'id' => 'textarea', 'value' => $model->description)); ?>
                    <?php echo $form->error($model, 'description'); ?>
                <?php } ?>
            </div><br>
            <p class="font-weight-normal type_text" id="card_text"><?= $model->description ?></p>
            <div class="row buttons">
                <?php echo CHtml::button('Save', ['id' => "modal_saver", 'class' => 'd-none']); ?>
            </div>
            <label for="card_date" id="deadline_label" class="font-weight-bold">Muddat:</label>
            <p class="font-weight-normal" id="card_date"><?= $model->deadline ?></p>


            <label for="tags" id="tag_label" class="font-weight-bold">Teglar:</label>
            <div id="tags">
                <?php foreach ($tags as $tag) { ?>
                    <button type="button" tag_id="<?= $tag['tag_id'] ?>" style="background-color: <?= $tag['tag']['color']['name'] ?>;color:#17505e; " class="btn m-1"><?= $tag['tag']['name'] ?></button>
                <?php } ?>
            </div>

            <label for="tags" id="member_label" class="font-weight-bold">Userlar:</label>
            <div id="members">
                <?php foreach ($card_members as $model_member) { ?>
                    <a class="alert ml-2 alert-info"><?= ${$model_member['user']['username']} ?></a>
                <?php } ?>
            </div>
            <?php if (Yii::app()->user->checkAccess("Card.Delete")) { ?>

                <a href="/card/delete/id/<?= $model->id ?>" id="card_delete" class="btn btn-danger mt-4">Delete</a>
            <?php } ?>

        </div>
        <hr>
        <div class=" w-100  m-1">
            <?php if (Yii::app()->user->checkAccess("Card.UpdateDeadline")) { ?>
                <button type="button" data-toggle="modal" data-target="#mySubModal" class="btn btn-primary m-3 btn-sm">Deadline qo'shish</button>
            <?php } ?>
            <?php if (Yii::app()->user->checkAccess("Card.UpdateCardMember")) { ?>

                <button type="button" data-toggle="modal" id="adduserbtn" data-target="#addUser" class="btn btn-primary m-3 btn-sm">Foydalanuchi qo'shish</button>
            <?php } ?>

            <?php if (Yii::app()->user->checkAccess("Card.UpdateCardTag")) { ?>

                <button type="button" data-toggle="modal" data-target="#mySubModal" id="addtagbtn" class="btn btn-primary  m-3  btn-sm">Tag qo'shish</button>
            <?php } ?>

        </div>
    </div>

    <?php $this->endWidget(); ?>
    <!-- card view -->

    <script>
        document.querySelector('#modal_saver').classList.add('btn')
        document.querySelector('#modal_saver').classList.add('btn-primary')

        $(function() {
            let is = true;
            $('.trigger').change(function() {
                $(this).next('.showthis').toggle(this.checked);
                $(this).nextAll(".type_text").toggle(!this.checked);

                if ($('.show_text').css("display") == 'block' || $('.show_area').css("display") == 'block') {
                    $('#modal_saver').removeClass("d-none")
                } else {
                    $('#modal_saver').addClass("d-none")
                }
            })
        });


        function subSend(url, formdata = null, type = null, status = 0) {
            $.ajax({
                url: url,
                type: 'POST',
                data: formdata,
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if (data.ok == false) {
                        $("#sub-modal").html(data.model)
                        checkboxClick()
                        $("#sub_modal_saver").click(function(e) {
                            e.preventDefault()
                            subSend(url, $("#sub-board-form").serialize(), type, 1)
                            return;
                        });
                        return;
                    } else if (data.ok == true) {
                        if (status == 1) {
                            if (type == 'tag') {
                                $(".tags_check").append(`
                                <div class="form-check">
                                    <input class="tag_checkbox form-check-input" type="checkbox" value="${data.data.id}" id="${data.data.id}" checked>
                                    <label class="form-check-label" for="${data.data.id}">
                                        ${data.data.name}
                                    </label>
                                </div>
                                `)
                                checkboxClick()
                            }
                        }
                    } else if (data.ok == "error") {
                        $("#sub_error").html(`<strong>Error!</strong> ${request.responseJSON.msg}`)
                        $("#sub_error").removeClass("d-none")
                    }
                },
                error: function(request, error) {}

            })
        }
        console.log($('#addtagbtn'));

        $('#addtagbtn').click(function() {
            subSend(`/tag/create/board_id/<?= $model['column']['board_id'] ?>/card_id/<?= $model['id'] ?>`, null, 'tag')
        })



        function checkboxClick() {
            console.log('k');
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
                        card_id: '<?= $model->id ?>',
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
    </script>