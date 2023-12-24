<!DOCTYPE html>
<!-- Import latest bootstrap and jquery -->
<html>
<head>
    <title>NOTES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <style>
        .textbox {
            border: 1px solid #555;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>

</head>

<body data-bs-theme="dark">

<div class="container" style="margin-top:5px">

<?php
require_once "functions.php";
$notesFile = "notes.json";
$notes = [];
$edit  = "";

if (is_file($notesFile)) {
    $notes = file_get_contents($notesFile);
    $notes = json_decode($notes, True);
}

if (isset($_POST['add']) && !empty($_POST['text'])) {
    $safe_text = htmlspecialchars($_POST['text']);
    array_push($notes, $safe_text);
    $notes_json = json_encode($notes);
    file_put_contents($notesFile, $notes_json);
    alert("Note added successfully.", "success");
}

if (isset($_POST['update']) && !empty($_POST['text'])) {
    $safe_text = htmlspecialchars($_POST['text']);
    $notes[$_POST['id']] = $safe_text;
    $notes_json = json_encode($notes);
    file_put_contents($notesFile, $notes_json);
    echo alert("Note updated successfully.", "success");
    $edit = "";
    unset($_GET);
}

if (!empty($_POST['delall'])) {
    $notes_json = json_encode([]);
    file_put_contents($notesFile, $notes_json);
    echo alert("All notes deleted successfully.", "success");
}

if (isset($_GET['del'])) {
    if (!empty($notes[$_GET['del']])){
        unset($notes[$_GET['del']]);
        $notes_json = json_encode($notes);
        file_put_contents($notesFile, $notes_json);
        echo alert("Note deleted successfully.", "success");
    } else {
        echo alert("Note not found.", "warning");
    }
    unset($_GET);
}

if (isset($_GET['edit'])) {
    $edit = $notes[$_GET['edit']];
}
?>

<div class="card">
    <h3 class="card-header">Notes</h3>
    <div class="card-body">
        <form action="index.php" method="POST">
            <textarea class="form-control" name="text" id="text" cols="30" rows="10"><?= $edit ?></textarea>
            <br>
            <div class="btn-group">
                <?php
                if (isset($_GET['edit'])) {
                    echo "
                    <input type='hidden' name='id' value='$_GET[edit]'>
                    <button type='submit' class='btn btn-primary' name='update'>".icon('floppy')." Update</button>
                    <a href='index.php' class='btn btn-secondary'>".icon('x-circle')." Cancel</a>";
                } else {
                    echo '
                    <button type="submit" class="btn btn-success" name="add">'.icon('plus-circle').' Add</button>
                    <button type="submit" class="btn btn-danger" name="delall">'.icon('trash').' Delete all</button>';
                }
                ?>
            </div>
        </form>
    </div>
</div>
<hr>

<?php
if (!empty($notes)) {
    foreach ($notes as $key => $value) {
        echo "
        <div class='textbox'>
        $value
        <hr>
        <div class='btn-group'>
        <a href='?edit=$key' class='btn btn-primary'>".icon("pen")." Edit</a>
        <a href='?del=$key' class='btn btn-danger'>".icon('trash')." Delete</a>
        </div>
        </div>";
    }
} else {
    alert("Nothing added yet.", "warning");
}
?>
</div>
</body>