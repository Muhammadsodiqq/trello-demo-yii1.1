<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs = array(
    'Users' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Update',
);

?>

<h1>Update Card <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model' => $model, 'tags' => $tags, 'board_members' => $board_members)); ?>
<button type="button" class="taskColumnAdd btn btn-info ml-auto" data-toggle="modal" data-target="#myModal">
    Teg qo'shish
</button>

<button type="button" class="taskColumnAdd btn btn-info ml-auto" data-toggle="modal" data-target="#myModal2">
    Rang qo'shish
</button>


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
                <form action="/tag/create/" method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Name</label>
                        <input type="text" required class="form-control" id="inputEmail4" name="Tags[name]" placeholder="name" />
                        <input type="hidden" name="Tags[card_id]" value="<?= $model->id ?>">
                        <select class="form-select" name="Tags[color_id]" style="margin-top: 10px; display: block;">
                            <?php foreach ($colors as $color) { ?>
                                <option value="<?= $color->id ?>"><?= $color->name ?></option>
                            <?php } ?>

                        </select>
                        <button type="submit" class="btn btn-primary mt-4">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/color/create/" method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Name</label>
                        <input type="text" required class="form-control" id="inputEmail4" name="Colors[name]" placeholder="name" />
                        <button type="submit" class="btn btn-primary mt-4">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>