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

function textTools(string $key = "new") {
    return "
    <div class='btn-group' data-key='$key'>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='h1'>".icon("type-h1")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='h2'>".icon("type-h2")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='h3'>".icon("type-h3")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='h4'>".icon("type-h4")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='h5'>".icon("type-h5")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='h6'>".icon("type-h6")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='bold'>".icon("type-bold")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='italic'>".icon("type-italic")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='underline'>".icon("type-underline")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='strikethrough'>".icon("type-strikethrough")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='code'>".icon("code")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='blockquote'>".icon("blockquote-left")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='list-ul'>".icon("list-ul")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='list-ol'>".icon("list-ol")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='text'>".icon("text-paragraph")."</a>
        <a href='javascript:void(0);' class='btn btn-primary textTools' data-type='left'>".icon("justify-left")."</a>
    </div>
    ";
} 
?>