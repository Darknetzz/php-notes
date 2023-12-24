<?php

/* ───────────────────────────────────────────────────────────────────── */
/*                                  ADD                                  */
/* ───────────────────────────────────────────────────────────────────── */
if (isset($_POST['add']) && !empty($_POST['text']) && !empty($_POST['id'])) {

    $uniqid = $_POST['id'];

    foreach ($notes as $note) {
        if ($uniqid == $note['id']) {
            echo alert("Note already exists.", "warning");
            return false;
        }
    }

    $title      = strip_tags($_POST['title']);
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
    file_put_contents($notesFile, $notes_json);
    echo alert("Note added successfully. ($uniqid)", "success");
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
    file_put_contents($notesFile, $notes_json);
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

?>