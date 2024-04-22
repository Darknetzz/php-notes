<?php
function icon(string $icon, float $px = 15) {
    return "<i class='bi bi-$icon' style='font-size:{$px}px'></i>";
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

/* ────────────────────────────────────────────────────────────────────────── */
/*                                  getNotes                                  */
/* ────────────────────────────────────────────────────────────────────────── */
function getNotes() {

    # Check if 'notes' directory exists
    if (!is_dir(NOTES_DIR)) {
        echo alert("<h3>getNotes</h3>Error: 'notes' directory not found. Please create it.", "danger");
        die();
    }
    
    # Check if there are any notes
    $markdownFiles = glob("notes/*.md");
    if (empty($markdownFiles)) {
        echo alert("<h3>getNotes</h3>No notes found.", "info");
        return [];
    }

    # Get notes metadata and sort it
    $notes_json    = file_get_contents(NOTES_META_FILE_PATH);
    if (!json_validate($notes_json)) {
        echo alert("<h3>getNotes</h3>Error: Invalid JSON in <b>".NOTES_META_FILE_PATH."</b>", "danger");
        die();
    }
    $notes_array   = json_decode($notes_json, True);
    // usort($notes_array, fn($a, $b) => $a['date'] <=> $b['date']);

    # Get notes content
    foreach ($notes_array as $id => $note) {
        
        $note_id       = $note["id"];
        $note_title    = $note["title"];
        $note_date     = $note["date"];

        if (empty($note_id) || $note_id == 0) {
            echo alert("<h3>getNotes</h3>Error: Note ID is empty or 0.", "danger");
            continue;
        }
        if (!file_exists(NOTES_DIR."/$note_id.md")) {
            echo alert("<h3>getNotes</h3>Note not found: $note_id\n", "warning");
            continue;
        }
        $notes_content = file_get_contents(NOTES_DIR."/$note_id.md");


        $notes[$note_id] = [
            "id"      => $note_id,
            "title"   => $note_title,
            "date"    => $note_date,
            "file"    => NOTES_DIR."/$note_id.md",
            "content" => $notes_content,
        ];
    }
    return $notes;
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                                  addNote                                   */
/* ────────────────────────────────────────────────────────────────────────── */
function addNote($id, $title, $content = "") {
    $safe_title   = htmlspecialchars($title);
    $safe_content = htmlspecialchars($content);

    $note_content_file = NOTES_DIR."/$id.md";
    $notes             = getNotes();

    if (empty($notes)) {
        $notes = [];
    }

    # Put content in `notes/$id.md` file
    if (!file_exists(NOTES_DIR)) {
        die("[addNote] Error: 'notes' directory not found.");
    }
    
    if (file_exists($note_content_file)) {
        echo alert("<h3>addNote</h3> Error: Note <b>$note_content_file</b> already exists.", "danger");
        return False;
    }

    file_put_contents($note_content_file, $safe_content);

    if (!file_exists($note_content_file)) {
        echo alert("<h3>addNote</h3> Error: Unable to create note file <b>$note_content_file</b>", "danger");
        return False;
    }



    # Insert metadata
    array_push(
        $notes, 
            [
                "id"    => $id,
                "title" => $safe_title,
                "date"  => date("Y-m-d H:i:s"),
                "file"  => NOTES_DIR."/$id.md",
            ]
    );
    $notes_json = json_encode($notes);
    file_put_contents(NOTES_META_FILE_PATH, $notes_json);
    echo alert("<h3>addNote</h3> Note added successfully.", "success");
    return True;
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                                 updateNote                                 */
/* ────────────────────────────────────────────────────────────────────────── */
function updateNote($id, $title, $content = "") {

    # Update `notes/$id.md` file
    if (!file_exists(NOTES_DIR."/$id.md")) {
        echo alert("<h3>updateNote</h3> Error: Note not found.", "danger");
        return False;
    }
    $safe_content = htmlspecialchars($content);
    file_put_contents(NOTES_DIR."/$id.md", $safe_content);

    # Update metadata
    $notes = getNotes();
    $notes[$id] = [
        "id"    => $id,
        "title" => $title,
        "date"  => date("Y-m-d H:i:s"),
        "file"  => NOTES_DIR."/$id.md",
    ];
    $notes_json = json_encode($notes);
    file_put_contents(NOTES_META_FILE_PATH, $notes_json);
    echo alert("<h3>editNote</h3> Note updated successfully.", "success");
    return True;
}


/* ────────────────────────────────────────────────────────────────────────── */
/*                                   delNote                                  */
/* ────────────────────────────────────────────────────────────────────────── */
function delNote($id) {
    $notes = getNotes();
    if (!file_exists(NOTES_DIR."/$id.md")) {
        echo alert("<h3>delNote</h3> Error: Note not found.", "danger");
        return False;
    }
    unlink(NOTES_DIR."/$id.md");
    unset($notes[$id]);
    $notes_json = json_encode($notes);
    file_put_contents(NOTES_META_FILE_PATH, $notes_json);
    echo alert("<h3>delNote</h3> Note deleted successfully.", "success");
    return True;

}

/* ────────────────────────────────────────────────────────────────────────── */
/*                                 delAllNotes                                */
/* ────────────────────────────────────────────────────────────────────────── */
function delAllNotes() {
    file_put_contents(NOTES_META_FILE_PATH, "{}");
    array_map('unlink', glob(NOTES_DIR."/*.md"));
    echo alert("All notes deleted successfully.", "success");
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