<?php
function icon(string $icon, float $size = 15) {
    return "<i class='bi bi-$icon' style='font-size:".$size."px'></i>";
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

/* ───────────────────────────────────────────────────────────────────── */
/*                                getNotes                               */
/* ───────────────────────────────────────────────────────────────────── */
function getNotes(string $notesFile = "notes.json") {
    $notes = [];

    if (!is_file($notesFile) || filesize($notesFile) == 0) {
        file_put_contents($notesFile, "[]");
        echo alert("Notes file '$notesFile' not found. A new one has been created.", "warning");
    }

    $notes_json = file_get_contents($notesFile);

    if (!json_validate($notes_json)) {
        echo alert("Error: <b>$notesFile</b> is not a valid JSON file.", "danger");
        die();
    }

    $notes = json_decode($notes_json, True);
    return $notes;
}

/* ───────────────────────────────────────────────────────────────────── */
/*                               textTools                               */
/* ───────────────────────────────────────────────────────────────────── */
function textTools(string $key = "new") {
    $class = "btn btn-default border-info text-info textTools";
    return "
        <div class='btn-group toolbar' data-key='$key'>
            <a href='javascript:void(0);' class='$class' data-type='h1'>".icon("type-h1")."</a>
            <a href='javascript:void(0);' class='$class' data-type='h2'>".icon("type-h2")."</a>
            <a href='javascript:void(0);' class='$class' data-type='h3'>".icon("type-h3")."</a>
            <a href='javascript:void(0);' class='$class' data-type='h4'>".icon("type-h4")."</a>
            <a href='javascript:void(0);' class='$class' data-type='h5'>".icon("type-h5")."</a>
            <a href='javascript:void(0);' class='$class' data-type='h6'>".icon("type-h6")."</a>
            <a href='javascript:void(0);' class='$class' data-type='bold'>".icon("type-bold")."</a>
            <a href='javascript:void(0);' class='$class' data-type='italic'>".icon("type-italic")."</a>
            <a href='javascript:void(0);' class='$class' data-type='underline'>".icon("type-underline")."</a>
            <a href='javascript:void(0);' class='$class' data-type='strikethrough'>".icon("type-strikethrough")."</a>
            <a href='javascript:void(0);' class='$class' data-type='code'>".icon("code")."</a>
            <a href='javascript:void(0);' class='$class' data-type='blockquote'>".icon("blockquote-left")."</a>
            <a href='javascript:void(0);' class='$class' data-type='list-ul'>".icon("list-ul")."</a>
            <a href='javascript:void(0);' class='$class' data-type='list-ol'>".icon("list-ol")."</a>
            <a href='javascript:void(0);' class='$class' data-type='text'>".icon("text-paragraph")."</a>
            <a href='javascript:void(0);' class='$class' data-type='left'>".icon("justify-left")."</a>
        </div>
    ";
} 


/* ───────────────────────────────────────────────────────────────────── */
/*                         convertToRelativeTime                         */
/* ───────────────────────────────────────────────────────────────────── */
function convertToRelativeTime(string $date = Null) {
    if ($date == Null) {
        return "Never";
    }

    $timestamp = strtotime($date);
    $currentTime = time();
    $difference = $currentTime - $timestamp;

    $periods = array("second", "minute", "hour", "day", "week", "month", "year");
    $lengths = array("60", "60", "24", "7", "4.35", "12");

    for ($i = 0; $difference >= $lengths[$i] && $i < count($lengths) - 1; $i++) {
        $difference /= $lengths[$i];
    }

    $difference = round($difference);

    if ($difference != 1) {
        $periods[$i] .= "s";
    }

    return "$difference $periods[$i] ago";
}


/* ───────────────────────────────────────────────────────────────────── */
/*                                 badge                                 */
/* ───────────────────────────────────────────────────────────────────── */
function badge(string $text, string $type = "info", int $size = 16) {
    return "<span class='badge text-bg-$type' style='font-size:".$size."px;'>$text</span>";
}

/* ───────────────────────────────────────────────────────────────────── */
/*                                navItem                                */
/* ───────────────────────────────────────────────────────────────────── */
function navItem(
        string $text,
        string $icon,
        string $color = "light",
        string $class = Null,
        array  $data  = [],
        string $href  = Null
    ) {

        $href   = ($href == Null) ? "#$text" : $href;
        $color  = (!empty($color)) ? $color : "dark";
        $class  = "list-group-item list-group-item-$color $class leftMenu-list-item";
        $data = (count($data) > 0) ? implode(" ", array_map(function($key, $value) {
            return "data-$key='$value'";
        }, array_keys($data), $data)) : null;

    return "<a href='$href' class='$class' $data>".icon($icon)." $text</a>";
}
?>