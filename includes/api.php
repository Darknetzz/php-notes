<?php

require_once("functions.php");
require_once("config.php");

/* ───────────────────────────────────────────────────────────────────── */
/*                                  ADD                                  */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['add']) && !empty($_POST['text']) && !empty($_POST['id'])) {
    
    $uniqid = strip_tags($_POST['id']);
    foreach ($notes as $note) {
        if ($uniqid == $note['id']) {
            echo alert("Note already exists.", "warning");
            return False;
        }
    }
    
    $title      = (!empty($_POST['title']) ? strip_tags($_POST['title']) : Null);
    $safe_text  = strip_tags($_POST['text']);

    $insert    = [
        "id"                => $uniqid,
        "title"             => $title,
        "content"           => $safe_text,
        "created_at"        => date('Y-m-d H:i:s'),
        "last_modified_at"  => date('Y-m-d H:i:s'),
    ];

    array_push($notes, $insert);
    $notes_json = json_encode($notes);
    file_put_contents(NOTES_FILE, $notes_json);
    echo alert("Note added successfully. ($uniqid)", "success");
}

/* ───────────────────────────────────────────────────────────────────── */
/*                               DELETE ONE                              */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['del'])) {

    $id = strip_tags($_POST['del']);

    if (empty($id)) {
        echo alert("No note selected.", "warning");
        return False;
    }

    if (empty($notes[$id])) {
        echo alert("Note ".badge($id)." not found.", "warning");
        return False;
    }

    unset($notes[$id]);
    $notes_json = json_encode($notes);
    file_put_contents(NOTES_FILE, $notes_json);
    echo alert("Note deleted successfully.", "success");
    unset($_POST);
}

/* ───────────────────────────────────────────────────────────────────── */
/*                               DELETE ALL                              */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['delall']) && !empty($notes)) {
    echo alert("
    <h4>".icon('exclamation-triangle')." Are you sure you want to delete <b>all</b> your notes? This cannot be undone!</h4>
    <hr>
    <form action='index.php' method='POST'>
        <button type='button' class='btn btn-danger delAllConfirm'>".icon('trash')." Delete all</button>
        <a href='index.php' class='btn btn-secondary'>".icon('x-circle')." Cancel</a>
    </form>", "danger", False);
    die();
}

/* ───────────────────────────────────────────────────────────────────── */
/*                           DELETE ALL CONFIRM                          */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['delallconfirm']) && !empty($notes)) {
    $notes_json = json_encode([]);
    file_put_contents(NOTES_FILE, $notes_json);
    echo alert("All notes deleted successfully.", "success");
}

/* ───────────────────────────────────────────────────────────────────── */
/*                                 GET ID                                */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_GET['genid'])) {
    echo uniqid();
}

/* ───────────────────────────────────────────────────────────────────── */
/*                              UPDATE/EDIT                              */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['update']) && !empty($_POST['text'])) {
    $safe_title                                 = strip_tags($_POST['title']);
    $safe_text                                  = strip_tags($_POST['text']);
    $notes[$_POST['id']]["title"]               = $safe_title;
    $notes[$_POST['id']]["content"]             = $safe_text;
    $notes[$_POST['id']]["last_modified_at"]    = date('Y-m-d H:i:s');
    $notes_json = json_encode($notes);
    file_put_contents(NOTES_FILE, $notes_json);
    echo alert("Note updated successfully.", "success");
    $edit = "";
    unset($_GET);
}



/* ───────────────────────────────────────────────────────────────────── */
/*                                 EDIT?                                 */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_GET['edit'])) {
    $edit = $notes[$_GET['edit']];
}

/* ───────────────────────────────────────────────────────────────────── */
/*                                markdown                               */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_GET['md'])) {
    echo md($_GET['md']);
}

?>