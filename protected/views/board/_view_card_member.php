<div class="m-2 member_check" style="min-width: 30%;">
    <h4>Userlar</h4>
    <?php foreach ($data as $member) { ?>
        <div class="form-check">
            <input  class="member_checkbox form-check-input" type="checkbox" value="<?= $member['user_id'] ?>" id="<?= $member['user_id'] ?>" <?= $member['is_card_member'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="<?= $member['user_id'] ?>">
                <?= $member['username'] ?>
            </label>
        </div>
    <?php } ?>
</div>