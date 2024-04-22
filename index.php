<!DOCTYPE html>
<!-- Import latest bootstrap and jquery -->
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NOTES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/2.1.0/showdown.min.js"></script>
    <link rel="stylesheet" href="includes/notes.css">
</head>

<body data-bs-theme="dark">

<div class="container" style="margin-top:5px">

<?php
require_once("includes/functions.php");
require_once("includes/config.php");

echo alert("<h5>Debug</h5>".json_encode($_REQUEST), "primary");

$notes = getNotes();
if (!$notes) {
    $notes = [];
    echo alert("No notes found.", "warning");
}

$action       = (!empty($_GET['action']) ? $_GET['action'] : Null);
$action       = (!empty($_POST['action']) ? $_POST['action'] : $action);
$id           = (!empty($_POST['id']) ? $_POST['id'] : Null);
$id           = (!empty($_GET['id']) ? $_GET['id'] : $id);
$titleContent = "";
$editContent  = "";
$buttons      = "";


if (!empty($action)) {

    # Check that we have an ID
    if (empty($id)) {
        echo alert("ID not found.", "danger");
        die();
    }

    /* ────────────────────────────────────────────────────────────────────────── */
    /*                                  add note                                  */
    /* ────────────────────────────────────────────────────────────────────────── */
    if ($action == "add") {
        # $id, $title, $content = ""
        addNote($id, $_POST['title'], $_POST['content']);
    }

    /* ────────────────────────────────────────────────────────────────────────── */
    /*                                 update note                                */
    /* ────────────────────────────────────────────────────────────────────────── */
    if ($action == "update") {
        # $id, $title, $content = ""
        $updateNote = updateNote($id, $_POST['title'], $_POST['content']);
        if (!$updateNote) {
            echo alert("Error adding note.", "danger");
        }
        echo alert("Note added successfully.", "success");
    }

    /* ────────────────────────────────────────────────────────────────────────── */
    /*                              delete all notes                              */
    /* ────────────────────────────────────────────────────────────────────────── */
    if ($action == "delall") {
        echo alert("
        <h4>".icon('exclamation-triangle')." Are you sure you want to delete <b>all</b> your notes? This cannot be undone!</h4>
        <hr>
        <form action='index.php' method='POST'>
            <button type='submit' class='btn btn-danger' name='delallconfirm'>".icon('trash')." Delete all</button>
            <a href='index.php' class='btn btn-secondary'>".icon('x-circle')." Cancel</a>
        </form>", "danger", False);
    }

    /* ────────────────────────────────────────────────────────────────────────── */
    /*                             delete all confirm                             */
    /* ────────────────────────────────────────────────────────────────────────── */
    if ($action == "delallconfirm") {
        delAllNotes();
    }

    /* ────────────────────────────────────────────────────────────────────────── */
    /*                             delete single note                             */
    /* ────────────────────────────────────────────────────────────────────────── */
    if ($action == "del") {
        # $id, $title, $content = ""
        delNote($id);
    }

    /* ────────────────────────────────────────────────────────────────────────── */
    /*                                    edit                                    */
    /* ────────────────────────────────────────────────────────────────────────── */
    if ($action == "edit") {
        $editId       = $id;
        $titleContent = $notes[$id]['title'];
        $editContent  = $notes[$id]['content'];
    }
}
    ?>

<?php
/* ────────────────────────────────────────────────────────────────────────── */
/*                             EDIT/ADD NOTE FORM                             */
/* ────────────────────────────────────────────────────────────────────────── */
?>
<div class="card">
    <h3 class="card-header">Notes</h3>
    <div class="card-body">
        <form action="index.php" method="POST">
            <input class="form-control" name="title" id="title" value="<?= $titleContent ?>" placeholder="Title">
            <textarea class="form-control" name="content" id="text" cols="30" rows="10" placeholder="Content (supports markdown!)"><?= $editContent ?></textarea>
            <br>
            <div class="btn-group">
                <?php
                if (empty($id)) {
                    $id = uniqid("NOTE_");
                }

                if ($action == "edit") {
                    $action  = "update";
                    $buttons = "
                        <button type='submit' class='btn btn-success'>".icon('floppy')." Update</button>
                        <a href='index.php' class='btn btn-secondary'>".icon('x-circle')." Cancel</a>
                    ";
                } else {
                    $action  = "add";
                    $buttons = "
                        <button type='submit' class='btn btn-success'>".icon('plus-circle')." Add</button>
                        <button type='submit' class='btn btn-danger' name='action' value='delall'>".icon('trash')." Delete all</button>
                    ";
                }
                ?>
            </div>
            <input type='hidden' name='action' value='<?= $action ?>'>
            <input type='hidden' name='id' value='<?= $id ?>'>
            <?= $buttons ?>
        </form>
    </div>
</div>

<hr>

<?php
/* ────────────────────────────────────────────────────────────────────────── */
/*                                 Show notes                                 */
/* ────────────────────────────────────────────────────────────────────────── */
$notes = getNotes();
if (!empty($notes)) {
    foreach ($notes as $id => $data) {

        $id      = $data["id"];
        $title   = $data["title"];
        $file    = $data["file"];
        $date    = $data["date"];

        if (!file_exists($file)) {
            echo alert("Note not found: $id", "warning");
            continue;
        }

        if (empty($file)) {
            echo alert("Note filename is empty: $id", "warning");
            continue;
        }

        $content = file_get_contents($file);

        if (STRICT_LINEBREAK === False) {
            $content = str_replace("\n", "\n\n", $content);
        }

        echo "
        <div class='textbox'>
            <div class='d-flex justify-content-between'>
                <h4>$title</h4>
                <div class='md'>$content</div>
                ".(!empty($date) ? "<div class='text-muted' title='$date'>".relativeTime($date)."</div>" : "")."
            </div>
            <hr>
            <div class='btn-group'>
                <a href='?action=edit&id=$id' class='btn btn-primary'>".icon("pen")." Edit</a>
                <a href='?action=del&id=$id' class='btn btn-danger'>".icon('trash')." Delete</a>
            </div>
        </div>";
    }
} else {
    alert("Nothing added yet.", "warning");
}
?>
</div>
</body>