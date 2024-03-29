<div class="page" id="notes">

<?php
$notes = getNotes(NOTES_FILE);
if (!empty($notes)) {
    foreach ($notes as $key => $value) {
        $text               = $value["content"];
        $md                 = md($text);

        $created_datetime   = (!empty($value["created_at"]) ? $value["created_at"] : Null);
        $created            = "<br><span class='text-muted'>Created ".convertToRelativeTime($created_datetime)."</span>";

        $modified_datetime  = (!empty($value["last_modified_at"]) ? $value["last_modified_at"] : Null);
        $modified           = ($modified_datetime != $created_datetime) ? "<br><span class='text-muted'>Updated ".convertToRelativeTime($modified_datetime)."</span>" : Null;

        $title              = (!empty($value["title"])) ? $value["title"] : $value["id"];

        /* ───────────────────────────────────────────────────────────────────── */
        /*                              EDIT CONTENT                             */
        /* ───────────────────────────────────────────────────────────────────── */
            echo "
                <div class='card editContent ".$editCardClass."' data-key='$key'>
                <form action='' method='POST'>
                    <h4 class='card-header ".$editCardTitleClass."'><input type='text' class='form-control form-control-lg' value='$title' name='title'></h4>
                    <div class='card-body'>
                            <input type='hidden' name='id' value='$key'>
                            <div class='form-group'>

                                ".textTools($key)."

                                <textarea class='form-control textarea' name='text' cols='30' rows='10'>$text</textarea>
                                $md_preview

                                <div class='form-group mt-2'>
                                    <div class='btn-group'>
                                        <button type='submit' name='update' class='btn btn-success'>".icon('floppy')." Save</button>
                                        <button type='button' name='del' value='$key' class='btn btn-danger deleteNote'>".icon('trash')." Delete</button>
                                        <button type='button' class='btn btn-secondary cancelEdit' data-key='$key'>".icon('x-circle')." Cancel</button>
                                    </div>
                                        $created
                                        $modified
                                </div>
                            </div>
                        </form>
                        </div>
                </div>
            ";

            /* ───────────────────────────────────────────────────────────────────── */
            /*                            DISPLAY CONTENT                            */
            /* ───────────────────────────────────────────────────────────────────── */
            echo "
                <div class='card noteCard ".$notesCardClass."' data-key='$key'>
                <h4 class='card-header ".$notesCardTitleClass."'>
                    <div class='d-flex justify-content-between align-items-center'>
                        $title
                        <div class='btn-group'>
                            <button class='btn btn-orange editNote'>".icon("pen")." Edit</button>
                            <button class='btn btn-danger' type='submit' name='del' value='$key'>".icon('trash')." Delete</button>
                        </div>
                    </div>
                </h4>
                <div class='card-body'>
                    <form action='' method='POST'>
                        <div class='d-flex justify-content-between align-items-center'>
                            <span class='note'>$md</span>

                            <div>
                                $created
                                $modified
                                <br>

                            </div>
                        </div>
                    </form>
                </div>
                </div>
            <br>
        ";
    }
} else {
    alert("Nothing added yet.", "warning");
}
?>
</div>