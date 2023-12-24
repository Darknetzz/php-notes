<!DOCTYPE html>
<!-- Import latest bootstrap and jquery -->
<html>
<head>
    <title>NOTES</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        .textbox {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>

</head>

<body style="background-color:#333;color:white;">

<div class="container" style="margin-top:5px">

<?php
$notesFile = "notes.json";
$notes = [];
$edit  = "";

if (is_file($notesFile)) {
    $notes = file_get_contents($notesFile);
    $notes = json_decode($notes, True);
}

if (isset($_POST['add']) && !empty($_POST['text'])) {
    array_push($notes, $_POST['text']);
    $notes_json = json_encode($notes);
    file_put_contents($notesFile, $notes_json);
    echo "<div class='alert alert-success'>Note added successfully.</div>";
}

if (!empty($_POST['delall'])) {
    $notes_json = json_encode([]);
    file_put_contents($notesFile, $notes_json);
    echo "<div class='alert alert-success'>All notes deleted successfully.</div>";
}

if (isset($_GET['del'])) {
    unset($notes[$_GET['del']]);
    $notes_json = json_encode($notes);
    file_put_contents($notesFile, $notes_json);
    echo "<div class='alert alert-success'>Note deleted successfully.</div>";
    unset($_GET);
}

if (isset($_GET['edit'])) {
    $edit = $notes[$_GET['edit']];
}

if (isset($_POST['update']) && !empty($_POST['text'])) {
    $notes[$_POST['id']] = $_POST['text'];
    $notes_json = json_encode($notes);
    file_put_contents($notesFile, $notes_json);
    echo "<div class='alert alert-success'>Note updated successfully.</div>";
    $edit = "";
    unset($_GET);
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
                    echo "<input type='hidden' name='id' value='$_GET[edit]'>";
                    echo "<input type='submit' class='btn btn-primary' name='update' value='Update'>";
                } else {
                    echo '
                    <input type="submit" class="btn btn-success" name="add" value="Add">
                    <input type="submit" class="btn btn-danger" name="delall" value="Delete all">';
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
        <a href='?edit=$key' class='btn btn-primary'>Edit</a>
        <a href='?del=$key' class='btn btn-danger'>Delete</a>
        </div>
        </div>";
    }
} else {
    echo "<div class='alert alert-warning'>Nothing added yet.</div>";
}
?>
</div>
</body>