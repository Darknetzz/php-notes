<?php

/* ───────────────────────────────────────────────────────────────────── */
/*                                  ADD                                  */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['add']) && !empty($_POST['text'])) {
    $safe_text = strip_tags($_POST['text']);
    $insert    = [
        "id"                => uniqid("NOTE_"),
        "content"           => $safe_text,
        "created_at"        => time(),
        "last_modified_at"  => time(),
    ];
    array_push($notes, $insert);
    $notes_json = json_encode($notes);
    file_put_contents($notesFile, $notes_json);
    echo alert("Note added successfully.", "success");
}

/* ───────────────────────────────────────────────────────────────────── */
/*                              UPDATE/EDIT                              */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['update']) && !empty($_POST['text'])) {
    $safe_text = strip_tags($_POST['text']);
    $notes[$_POST['id']]["content"] = $safe_text;
    $notes[$_POST['id']]["last_modified_at"] = time();
    $notes_json = json_encode($notes);
    file_put_contents($notesFile, $notes_json);
    echo alert("Note updated successfully.", "success");
    $edit = "";
    unset($_GET);
}

/* ───────────────────────────────────────────────────────────────────── */
/*                               DELETE ALL                              */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['delall']) && !empty($notes)) {
    echo alert("
    <h4>".icon('exclamation-triangle')." Are you sure you want to delete <b>all</b> your notes? This cannot be undone!</h4>
    <hr>
    <form action='index.php' method='POST'>
        <button type='submit' class='btn btn-danger' name='delallconfirm'>".icon('trash')." Delete all</button>
        <a href='index.php' class='btn btn-secondary'>".icon('x-circle')." Cancel</a>
    </form>", "danger", False);
}

/* ───────────────────────────────────────────────────────────────────── */
/*                           DELETE ALL CONFIRM                          */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['delallconfirm']) && !empty($notes)) {
    $notes_json = json_encode([]);
    file_put_contents($notesFile, $notes_json);
    echo alert("All notes deleted successfully.", "success");
}

/* ───────────────────────────────────────────────────────────────────── */
/*                               DELETE ONE                              */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['del'])) {
    if (!empty($notes[$_POST['del']])){
        unset($notes[$_POST['del']]);
        $notes_json = json_encode($notes);
        file_put_contents($notesFile, $notes_json);
        echo alert("Note deleted successfully.", "success");
    } else {
        echo alert("Note not found.", "warning");
    }
    unset($_POST);
}

/* ───────────────────────────────────────────────────────────────────── */
/*                                 EDIT?                                 */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_GET['edit'])) {
    $edit = $notes[$_GET['edit']];
}

?>