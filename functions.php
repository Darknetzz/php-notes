<?php
function icon(string $icon, float $px = 15) {
    return "<i class='bi bi-$icon' style='font-size:{$size}px'></i>";
}

function alert(string $text, string $type = "success") {
    if ($type == "danger") $icon = icon("x-circle");
    else if ($type == "warning") $icon = icon("exclamation-circle");
    else if ($type == "info") $icon = icon("info-circle");
    else if ($type == "success") $icon = icon("check-circle");
    else $icon = "";
    return "<div class='alert alert-$type'>$icon $text</div>";
}
?>