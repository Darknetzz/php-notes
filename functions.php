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
    
    // Sort by descending array index/key
    krsort($notes);

    return $notes;
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                                relativeTime                                */
/* ────────────────────────────────────────────────────────────────────────── */
function relativeTime(string $date) {
    $date = new DateTime($date);
    $now = new DateTime();
    $diff = $now->diff($date);

    if ($diff->y > 0) {
        return $diff->format('%y years ago');
    } elseif ($diff->m > 0) {
        return $diff->format('%m months ago');
    } elseif ($diff->d > 0) {
        return $diff->format('%d days ago');
    } elseif ($diff->h > 0) {
        return $diff->format('%h hours ago');
    } elseif ($diff->i > 0) {
        return $diff->format('%i minutes ago');
    } else {
        return 'Just now';
    }
}

/* ───────────────────────────────────────────────────────────────────── */
/*                             md (markdown)                             */
/* ───────────────────────────────────────────────────────────────────── */
# NOTE: No longer using this, using showdownjs instead.
// function md(string $text) {
//     require_once("Michelf/Markdown.inc.php");
//     return \Michelf\Markdown::defaultTransform($text);
// }
?>