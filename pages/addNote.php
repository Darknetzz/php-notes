<!-- NOTE: addNoteCard -->
<div class="page" id="addNote">
    <div id="addNoteCard" class="card <?= $addCardClass ?>">
        <h4 class="card-header <?= $addCardTitleClass ?>">Add note</h4>
        <div class="card-body mb-3">
    
    
            <form action="index.php" id="addNoteForm" method="POST">
    
                <div class="editor">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-lg" name="title" placeholder="Title (optional)">
                    </div>
                    <br>
                    <?= textTools() ?>
                    <input type="hidden" name="id" value="<?= uniqid("NOTE_") ?>">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <textarea class="form-control textarea" name="text" id="newNote" cols="30" rows="10"><?= $edit ?></textarea>
                    </div>
                </div>
    
                <br>
                <?= $md_preview ?>
                    <button type="button" class="btn btn-success addNote btn-block"><?= icon('floppy') ?> Save note</button>
                    <button type="button" class="btn btn-secondary toggleEditor btn-block"><?= icon('eye-slash') ?> Cancel</button>
            </form>
        </div>
    </div>
</div>