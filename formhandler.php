<?php

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