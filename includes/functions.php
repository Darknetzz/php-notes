<?php

/* ────────────────────────────────────────────────────────────────────────── */
/*                                    icon                                    */
/* ────────────────────────────────────────────────────────────────────────── */
function icon(string $icon, float $px = 15) {
    return "<i class='bi bi-$icon' style='font-size:{$px}px'></i>";
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                                    alert                                   */
/* ────────────────────────────────────────────────────────────────────────── */
function alert(string $text, string $type = "success", bool $showicon = True) {
    $icon = "";
    if ($showicon) {
        if ($type == "danger") $icon = icon("x-circle");
        else if ($type == "warning") $icon = icon("exclamation-circle");
        else if ($type == "info") $icon = icon("info-circle");
        else if ($type == "success") $icon = icon("check-circle");
    }

    return "<div class='alert alert-$type'><span class='text-$type'>$icon $text</span></div>";
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                                   talert                                   */
/* ────────────────────────────────────────────────────────────────────────── */
function talert(?string $title, string $text, string $type = "primary", bool $showicon = True,  bool $prefixType = True) {

    $icon = "";
    if ($showicon) {
        if ($type == "danger") {
            $typePrefix = "[ERROR]";
            $icon = icon("x-circle");
        }
        else if ($type == "warning") {
            $typePrefix = "[WARNING]";
            $icon = icon("exclamation-circle");
        }
        else if ($type == "info") {
            $typePrefix = "[INFO]";
            $icon = icon("info-circle");
        }
        else if ($type == "success") {
            $typePrefix = "[SUCCESS]";
            $icon = icon("check-circle");
        }
    }

    if (empty($title)) {
        $title = $typePrefix;
    } elseif ($prefixType) {
        $title = $typePrefix." ".$title;
    } else {
        $title = $title;
    }
    return "
        <div class='alert alert-$type'>
            <h3 class='text-$type'>$icon $title</h3>
            <hr>
            <p>$text</p>
        </div>
    ";
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                                   slugify                                  */
/* ────────────────────────────────────────────────────────────────────────── */
function slugify(string $text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}

/* ───────────────────────────────────────────────────────────────────── */
/*                           createNoteMetadata                          */
/* ───────────────────────────────────────────────────────────────────── */
function createNoteMetadata($title) {
    return trim(str_replace(" ", "", "
    <!-- ID: ".slugify($title)." -->
    <!-- Title: $title -->
    <!-- Date: ".date("Y-m-d H:i:s")." -->
    <!-- File: ".NOTES_DIR."/".slugify($title).".md -->"));
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                               getNoteMetadata                              */
/* ────────────────────────────────────────────────────────────────────────── */
function extractNoteMetadata($content) {
    // Regular expression to extract the ID
    $idMatches = '/<!--\s*ID:\s*(.*?)\s*-->/';
    // Regular expression to extract the title
    $titlePattern = '/<!--\s*Title:\s*(.*?)\s*-->/';
    // Regular expression to extract the date
    $datePattern = '/<!--\s*Date:\s*(.*?)\s*-->/';

    // Attempt to extract the ID
    $id = "ID not found.";
    if (preg_match($datePattern, $content, $idMatches)) {
        $id = $idMatches[1]; // The first captured group
    }

    // Attempt to extract the title
    $title = "Title not found.";
    if (preg_match($titlePattern, $content, $titleMatches)) {
        $title = $titleMatches[1]; // The first captured group
    }

    // Attempt to extract the date
    $date = "Date not found.";
    if (preg_match($datePattern, $content, $dateMatches)) {
        $date = $dateMatches[1]; // The first captured group
    }


    return [
        "id"    => slugify($title),
        "title" => $title,
        "file"  => NOTES_DIR."/".slugify($title).".md",
        "date"  => $date,
    ];
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                                stripMetadata                               */
/* ────────────────────────────────────────────────────────────────────────── */
function stripMetadata($content) {
    // Regular expression to match HTML-style comments
    $commentPattern = '/<!--(.*?)-->/s';
    return preg_replace($commentPattern, Null, $content);
}

/* ───────────────────────────────────────────────────────────────────── */
/*                         getNoteContent($note)                         */
/* ───────────────────────────────────────────────────────────────────── */
function getNote($id) {
    if (!file_exists(NOTES_DIR."/$id.md")) {
        echo talert("function getNoteContent()", "Note not found.", "danger");
        return False;
    }
    $title   = extractNoteMetadata($content)['title'];
    $content = stripMetaData(trim(file_get_contents(NOTES_DIR."/$id.md")));
    return ["title" => $title, "content" => $content];
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                                  getNotes                                  */
/* ────────────────────────────────────────────────────────────────────────── */
function getNotes() {
    # Check if 'notes' directory exists
    if (!is_dir(NOTES_DIR)) {
        echo talert("function getNotes()", "'notes' directory not found. Please create it.", "danger");
        die();
    }

    # Check if there are any notes
    $markdownFiles = glob("notes/*.md");

    # Empty
    if (empty($markdownFiles)) {
        echo talert("function getNotes()", "No notes found.", "info");
        return [];
    }

    $notes_array = [];
    foreach ($markdownFiles as $file) {
        $content  = file_get_contents($file);
        $metadata = extractNoteMetadata($content);
        if (empty($metadata['id']) || empty($metadata['title']) || empty($metadata['date']) || empty($metadata['file'])) {
            echo talert("function getNotes()", "Note metadata not found for $file, attempting to add.", "danger");
            stripMetadata($content);
            addNote($content, "Untitled");
            continue;
        }
        $id       = $metadata['id'];
        $title    = $metadata['title'];
        $date     = $metadata['date'];

        // Remove the comments from the content displayed
        $content = trim(stripMetadata($content));
        $notes_array[] = [
            "id"      => $id,
            "title"   => $title,
            "date"    => $date,
            "file"    => $file,
            "content" => $content,
        ];
    }

    return $notes_array;
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                                  addNote                                   */
/* ────────────────────────────────────────────────────────────────────────── */
function addNote($content = "", $title = Null) {
    if (empty($title)) {
        $title = "Untitled";
    }
    if (empty($content)) {
        echo talert("function addNote()", "Note content is empty.", "danger");
        return False;
    }

    $metadata     = createNoteMetadata($title);
    $safe_content = $metadata.htmlspecialchars($content);

    $notes = getNotes();

    $id   = slugify($title);
    $file = NOTES_DIR."/".$id.".md";
    
    $append = 1;
    while (file_exists($file) === True) {
        echo talert("function addNote()", "Note <b>$id</b> already exists. Appending $append after..", "danger");
        $file = NOTES_DIR."/${id}_${append}.md";
        $append++;
    }

    file_put_contents($file, $safe_content);

    if (!file_exists($file)) {
        echo talert("function addNote()", "Unable to create note file <b>$file</b>", "danger");
        return False;
    }

    echo talert(Null, "Note added successfully.", "success");
    return True;
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                                 updateNote                                 */
/* ────────────────────────────────────────────────────────────────────────── */
function updateNote($title, $content = "") {

    # Update `notes/$id.md` file
    if (!file_exists(NOTES_DIR."/$id.md")) {
        echo talert("function updateNote()", "Note not found.", "danger");
        return False;
    }

    $content  = stripNoteMetadata($content);
    $metadata = createNoteMetadata($title);

    $safe_content = trim("
    <!-- ID: $id -->\n
    <!-- Title: $title -->\n
    <!-- Date: ".$date." -->\n
    ".htmlspecialchars($content));

    $file_slug = slugify($title);

    file_put_contents(NOTES_DIR."/$file_slug.md", $safe_content);

    echo talert(Null, "Note updated successfully.", "success");
    return True;
}


/* ────────────────────────────────────────────────────────────────────────── */
/*                                   delNote                                  */
/* ────────────────────────────────────────────────────────────────────────── */
function delNote($file) {
    unlink(NOTES_DIR."/$file.md");
    echo talert(Null, "Note deleted successfully.", "success");
    return True;
}

/* ────────────────────────────────────────────────────────────────────────── */
/*                                 delAllNotes                                */
/* ────────────────────────────────────────────────────────────────────────── */
function delAllNotes() {
    array_map('unlink', glob(NOTES_DIR."/*.md"));
    echo talert(Null, "All notes deleted successfully.", "success");
    return True;
}


/* ────────────────────────────────────────────────────────────────────────── */
/*                                relativeTime                                */
/* ────────────────────────────────────────────────────────────────────────── */
function relativeTime(string $date) {
    
    $now = new DateTime();
    if (empty($date)) return $now;

    $date = new DateTime($date);
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