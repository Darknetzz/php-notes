<?php
function icon(string $icon, float $px = 15) {
    return "<i class='bi bi-$icon' style='font-size:{$size}px'></i>";
}

function alert(string $text, string $type = "success", bool $showicon = True) {
    $icon = "";
    if ($showicon) {
        if ($type == "danger") $icon = icon("x-circle");
        else if ($type == "warning") $icon = icon("exclamation-circle");
        else if ($type == "info") $icon = icon("info-circle");
        else if ($type == "success") $icon = icon("check-circle");
    }
    return "<div class='alert alert-$type'>$icon $text</div>";
}

function getNotes(string $notesFile = "notes.json") {
    $notes = [];
    if (is_file($notesFile)) {
        $notes = file_get_contents($notesFile);
        $notes = json_decode($notes, True);
    }
    return $notes;
}
?>