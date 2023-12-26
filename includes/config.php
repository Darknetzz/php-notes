<?php

/* ───────────────────────────────────────────────────────────────────── */
/*                                 Notes                                 */
/* ───────────────────────────────────────────────────────────────────── */
define("NOTES_FILE", "includes/notes.json");
$notes          = getNotes(NOTES_FILE);
$edit           = "";

/* ───────────────────────────────────────────────────────────────────── */
/*                                Markdown                               */
/* ───────────────────────────────────────────────────────────────────── */
$md_preview     = "<div class='md-preview'><span class='text-muted'>When you start typing, you can see the preview here.</span></div>";

/* ───────────────────────────────────────────────────────────────────── */
/*                                 GitHub                                */
/* ───────────────────────────────────────────────────────────────────── */
$github_url     = "https://github.com/Darknetzz";
$project_url    = "$github_url/php-notes";

/* ───────────────────────────────────────────────────────────────────── */
/*                                 Users                                 */
/* ───────────────────────────────────────────────────────────────────── */
define("USERS_FILE", "includes/users.json");
define("USERS", getUsers(USERS_FILE));

/* ───────────────────────────────────────────────────────────────────── */
/*                                Classes                                */
/* ───────────────────────────────────────────────────────────────────── */
$addCardClass       = "border-success";
$addCardTitleClass  = "bg-success bg-opacity-50 text-white";
$editCardClass      = "border-warning";
$editCardTitleClass = "bg-warning bg-opacity-50 text-white";
$notesCardClass     = "border-info";
$notesCardTitleClass= "bg-info bg-opacity-50 text-white";
?>