<!DOCTYPE html>
<!-- Import latest bootstrap and jquery -->
<?php
require_once("includes/config.php");

# Put content in `notes/$id.md` file
if (!file_exists(NOTES_DIR)) {
    die(alert("function addNote()", "'notes' directory not found."));
}
?>
<html data-bs-theme="dark" class="theme-dark">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NOTES</title>

    <?php
    if (!defined("THEME")) {
        define("THEME", "tabler");
    }
    if (THEME == "tabler") {
        $themecss = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">';
        $themejs  = '<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>';
    } else {
        $themecss = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">';
        $themejs  = '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>';
    }

    $css = '
        <link rel="stylesheet" href="includes/notes.css">
        '.$themecss.'
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">';

    $js  = '
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/2.1.0/showdown.min.js"></script>
        <script src="includes/notes.js"></script>
        '.$themejs;
    ?>

    <?= $css ?>
</head>

<body data-bs-theme="dark">

<div class="container" style="padding-top:25px;height:100%">

<?php
require_once("includes/functions.php");


$notes = getNotes();
if (!$notes) {
    $notes = [];
    echo alert("No notes found.", "warning");
}

$action       = (!empty($_GET['action']) ? $_GET['action'] : Null);
$action       = (!empty($_POST['action']) ? $_POST['action'] : $action);
$title        = (!empty($_POST['title']) ? $_POST['title'] : "Untitled");
$id           = (!empty($_GET['id']) ? slugify($_GET['id']) : Null);
$id           = (!empty($id) ? slugify($id) : Null);
$titleContent = "";
$editContent  = "";
$buttons      = "";


if (!empty($action)) {

    # Check that we have an ID
    if ($action != "add" && $action != "delall") {
        if (empty($id)) {
            echo alert("ID is empty", "danger");
            die();
        }
        if (empty($title)) {
            $title = "Untitled";
        }
    }

    /* ────────────────────────────────────────────────────────────────────────── */
    /*                                  add note                                  */
    /* ────────────────────────────────────────────────────────────────────────── */
    if ($action == "add") {
        # $id, $title, $content = ""
        addNote($_POST['title'], $_POST['content']);
    }

    /* ────────────────────────────────────────────────────────────────────────── */
    /*                                 update note                                */
    /* ────────────────────────────────────────────────────────────────────────── */
    if ($action == "update") {
        # $id, $title, $content = ""
        updateNote($_POST['title'], $_POST['content']);
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
        $titleContent = getNote($id)['title'];
        $editContent  = getNote($id)['content'];
    }
}
    ?>

<?php
/* ────────────────────────────────────────────────────────────────────────── */
/*                             EDIT/ADD NOTE FORM                             */
/* ────────────────────────────────────────────────────────────────────────── */
?>
<div class="card">
    <h3 class="card-header"><?= (!empty($id) ? "Editing note $id" : "New note") ?></h3>
    <div class="card-body">
        <form action="index.php" method="POST">
            <input class="form-control my-2" name="title" id="title" value="<?= $titleContent ?>" placeholder="Title">
            <textarea class="form-control my-2" name="content" id="text" cols="30" rows="10" placeholder="Content (supports markdown!)"><?= $editContent ?></textarea>
            <br>
            <div class="btn-group">
                <?php
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
            <h4>$title</h4>
            <div class='d-flex justify-content-between'>
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


/* ────────────────────────────────────────────────────────────────────────── */
/*                                    Debug                                   */
/* ────────────────────────────────────────────────────────────────────────── */
if (!empty($_REQUEST)) {
    echo '
    <button class="btn btn-info debugBtn" type="button">
        Debug
    </button>
    </p>

    <div class="debugInfo hidden">
        '.alert('
        <div class="card">
            <h5 class="card-header">Debug</h5>
            <div class="card-body">
                '.json_encode($_REQUEST, JSON_PRETTY_PRINT).'
            </div>
        </div>
        ').
    "</div>";
}

?>
</div>
</body>
<?= $js ?>