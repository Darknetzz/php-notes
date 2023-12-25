<?php

$notesFile      = "notes.json";
$notes          = getNotes($notesFile);
$edit           = "";
$md_preview     = "<div class='md-preview'><span class='text-muted'>When you start typing, you can see the preview here.</span></div>";
$github_url     = "https://github.com/Darknetzz";
$project_url    = "$github_url/php-notes";

# Classes
$addCardClass       = "border-success";
$addCardTitleClass  = "bg-success bg-opacity-50 text-white";
$editCardClass      = "border-warning";
$editCardTitleClass = "bg-warning bg-opacity-50 text-white";
$notesCardClass     = "border-info";
$notesCardTitleClass= "bg-info bg-opacity-50 text-white";
?>