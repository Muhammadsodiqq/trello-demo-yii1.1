<div class="modal fade " id="myModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="alert alert-danger d-none" id="error"></div>
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex ">
                <div style="min-width: 50%;">
                    <label for="card_title" class="font-weight-bold d-block">Title:</label>
                    <div class="d-inline">
                        <?php if (Yii::app()->user->checkAccess("Card.Update")) { ?>
                            <input type="checkbox" class="ml-2 trigger" name="showhidecheckbox">
                            <?php } ?>
                            <div class="showthis show_text form-group">
                                <?php if (Yii::app()->user->checkAccess("Card.Update")) { ?>
                                    <input required id="input" type="text" class="form-control" name="showhideinput">
                                    <?php } ?>
                                </div><br>
                                <h5 class="modal-title type_text" id="card_title"></h5>
                    </div>
                    <label for="card_text" class="font-weight-bold">Izoh:</label>
                    <?php if (Yii::app()->user->checkAccess("Card.Update")) { ?>
                        <input type="checkbox" class="ml-2 trigger" name="showhidecheckbox">
                    <?php } ?>
                    <div class="showthis show_area form-group">
                        <?php if (Yii::app()->user->checkAccess("Card.Update")) { ?>
                            <textarea required id="textarea" class="form-control" id="exampleFormControlTextarea3" rows="10" cols="90"></textarea>
                        <?php } ?>
                    </div><br>
                    <p class="font-weight-normal type_text" id="card_text"> </p>
                    <button class="btn btn-primary d-none" id="btnarea_save">save</button>

                    <label for="card_date" id="deadline_label" class="font-weight-bold"></label>
                    <p class="font-weight-normal" id="card_date"> </p>


                    <label for="tags" id="tag_label" class="font-weight-bold"></label>
                    <div id="tags">
                    </div>

                    <label for="tags" id="member_label" class="font-weight-bold"></label>
                    <div id="members">

                    </div>
                    <?php if (Yii::app()->user->checkAccess("Card.Delete")) { ?>

                        <a href="#" id="card_delete" class="btn btn-danger mt-4">Delete</a>
                    <?php } ?>

                </div>
                <hr>
                <div class=" w-100  m-1">
                    <?php if (Yii::app()->user->checkAccess("Card.UpdateDeadline")) { ?>
                        <button data-toggle="modal" data-target="#addDeadline" class="btn btn-primary m-3 btn-sm">Deadline qo'shish</button>
                    <?php } ?>
                    <?php if (Yii::app()->user->checkAccess("Card.UpdateCardMember")) { ?>

                        <button data-toggle="modal" id="adduserbtn" data-target="#addUser" class="btn btn-primary m-3 btn-sm">Foydalanuchi qo'shish</button>
                    <?php } ?>

                    <?php if (Yii::app()->user->checkAccess("Card.UpdateCardTag")) { ?>

                        <button data-toggle="modal" data-target="#addDTag" id="addtagbtn" class="btn btn-primary  m-3  btn-sm">Tag qo'shish</button>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- card view -->
<script>
    $('#myModal2').on('hidden.bs.modal', function() {
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    })
    let btn = document.querySelectorAll("#columnbtn")
    let card_id
    // btn.forEach((e) => {
    //     e.addEventListener('click', function(e) {
    //         let btn = document.querySelector("#inp-hidden")
    //         btn.value = e.target.getAttribute('column_id')
    //         column_delete.href = '/column/delete/id/' + e.target.getAttribute('column_id')
    //     })
    // })

    let btn1 = document.querySelectorAll(".taskDiv")

    btn1.forEach((e) => {
        e.addEventListener('click', function(e) {
            // if (e.target !== this) {
            //     return
            // };
            getCard(e.target.id);
            card_id = e.target.id
        })
    })

    function getCard(id) {
        $.ajax({
            url: '/card/view/id/' + id,
            type: 'POST',
            data: {
                id: "<?php echo Yii::app()->user->id; ?>",
            },
            dataType: 'json',
            success: function(data) {

                card_text.innerText = data.card.description
                card_title.innerText = data.card.title
                card_date.innerText = data.card.deadline
                if (typeof(input) !== 'undefined') {
                    input.value = data.card.title;
                    textarea.value = data.card.description;
                }

                $("#Card_deadline").val(data.card.deadline)
                tags.innerHTML = ''
                members.innerHTML = ''
                deadline_label.innerText = data.card.deadline ? "Muddat" : ""
                if (data.tags) {
                    for (const key in data.tags) {
                        tag_label.innerText = 'Teglar:'
                        tags.innerHTML = tags.innerHTML + `<button type="button" tag_id="${data.tags[key].tag.id}" style="background-color: ${data.tags[key].tag.color.name};color:#17505e; " class="btn m-1">${data.tags[key].tag.name}</button>`
                    }
                }
                if (data.card_members) {
                    for (const key in data.card_members) {
                        member_label.innerText = 'Userlar:'
                        members.innerHTML = members.innerHTML + `<a class="alert ml-2 alert-info">${data.card_members[key].user.username}</a>`
                    }
                }
                if (typeof(card_delete) !== 'undefined') {
                    card_delete.href = `/card/delete/id/${id}`

                }
                $("#CardUpdateerror").addClass("d-none")
            },
            error: function(request, error) {
                $("#CardUpdateerror").html(`<strong>Error!</strong> ${request.responseJSON.msg}`)
                $("#CardUpdateerror").removeClass("d-none")
            }
        });
    }
</script>

<!-- card view checkbox input -->

<script>
    $(function() {
        let is = true;
        $('.trigger').change(function() {
            $(this).next('.showthis').toggle(this.checked);
            $(this).nextAll(".type_text").toggle(!this.checked);

            if ($('.show_text').css("display") == 'block' || $('.show_area').css("display") == 'block') {
                $('#btnarea_save').removeClass("d-none")
            } else {
                $('#btnarea_save').addClass("d-none")
            }
        })
    });
</script>

<!-- card update -->
<script>
    $('#btnarea_save').click(function() {

        $.ajax({
            url: `<?php echo Yii::app()->createUrl('Card/Update'); ?>`,
            type: 'POST',
            data: {
                id: "<?php echo Yii::app()->user->id; ?>",
                title: $("#input").val(),
                description: $("#textarea").val(),
                card_id: card_id
            },
            dataType: 'json',
            success: function(data) {
                $("#card_text").text(data.data.description)
                $("#card_title").text(data.data.title)
                $("#textarea").val(data.data.description)
                $("#input").val(data.data.title)
                $(".type_text").css('display', 'block')
                $(".showthis").css('display', 'none')
                $(".trigger").prop('checked', false)
                $('#btnarea_save').addClass("d-none")
            },
            error: function(request, error) {
                console.log($("#error"));
                $("#CardUpdateerror").html(`<strong>Error!</strong> ${request.responseJSON.msg}`)
                $("#CardUpdateerror").removeClass("d-none")
                console.log($("#CardUpdateerror"));

            }
        });
    })
</script>