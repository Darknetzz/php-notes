<!DOCTYPE html>
<!-- Import latest bootstrap and jquery -->
<html>
<head>
    <meta charset="utf-8"/>
    <title>NOTES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<?php
require_once("functions.php");
$notesFile  = "notes.json";
$notes      = getNotes($notesFile);
$edit       = "";
$md_preview = "<div class='md-preview'><span class='text-muted'>When you start typing, you can see the preview here.</span></div>";
?>

<body data-bs-theme="dark">

<?php include_once("nav.php"); ?>

<!-- NOTE: CONTAINER -->
<div class="container" style="margin-top:15px">

<?php
require_once("formhandler.php");

# Classes
$addCardClass       = "border-success";
$addCardTitleClass  = "bg-success bg-opacity-50 text-white";
$editCardClass      = "border-warning";
$editCardTitleClass = "bg-warning bg-opacity-50 text-white";
$notesCardClass     = "border-info";
$notesCardTitleClass= "bg-info bg-opacity-50 text-white";
?>

<div id="response"></div>

<br>

<!-- NOTE: addNoteCard -->
<div id="addNoteCard" class="card <?= $addCardClass ?>" style="display: none;">
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

<br>

<div id="allNotes">

<?php
$notes = getNotes("notes.json");
if (!empty($notes)) {
    foreach ($notes as $key => $value) {
        $text               = $value["content"];

        $created_datetime   = $value["created_at"];
        $created            = "<br><span class='text-muted'>Created ".convertToRelativeTime($created_datetime)."</span>";

        $modified_datetime  = $value["last_modified_at"];
        $modified           = ($modified_datetime != $created_datetime) ? "<br><span class='text-muted'>Updated ".convertToRelativeTime($modified_datetime)."</span>" : Null;

        $title              = (!empty($value["title"])) ? $value["title"] : $value["id"];

        /* ───────────────────────────────────────────────────────────────────── */
        /*                              EDIT CONTENT                             */
        /* ───────────────────────────────────────────────────────────────────── */
            echo "
                <div class='card editContent ".$editCardClass."' data-key='$key' style='display:none;'>
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
                                        <button type='submit' name='del' value='$key' class='btn btn-danger deleteNote'>".icon('trash')." Delete</button>
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
                <div class='card displayContent ".$notesCardClass."' data-key='$key'>
                <h4 class='card-header ".$notesCardTitleClass."'>
                    <div class='d-flex justify-content-between align-items-center'>
                        $title
                        <div class='btn-group'>
                            <button class='btn btn-primary editNote' style='font-weight: bold; color: white; background-color: #ff6600;'>".icon("pen")." Edit</button>
                            <button class='btn btn-danger' type='submit' name='del' value='$key'>".icon('trash')." Delete</button>
                        </div>
                    </div>
                </h4>
                <div class='card-body'>
                    <form action='' method='POST'>
                        <div class='d-flex justify-content-between align-items-center'>
                            <span class='note'>$text</span>

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
</div>



</body>

<?php require_once("js.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

</html>