<!DOCTYPE html>
<!-- Import latest bootstrap and jquery -->
<html>
<head>
    <meta charset="utf-8"/>
    <title>NOTES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <style>
        .md-preview {
            border: 1px solid #555;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #222;
        }

        .textarea {
            background-color: #222;
        }

        .toolbar {
            width:100%;
            background-color: #222;
            margin-bottom: 10px;
        }
    </style>

</head>

<body data-bs-theme="dark">

<div class="container" style="margin-top:15px">

<?php
require_once("functions.php");
$notesFile = "notes.json";
$notes = getNotes($notesFile);
$edit  = "";
$md_preview = "<div class='md-preview'><span class='text-muted'>When you start typing, you can see the preview here.</span></div>";

require_once("formhandler.php");

# Classes
$addCardClass       = "border-success";
$addCardTitleClass  = "bg-success bg-opacity-50 text-white";
$editCardClass      = "border-warning";
$editCardTitleClass = "bg-warning bg-opacity-50 text-white";
$notesCardClass     = "border-info";
$notesCardTitleClass= "bg-info bg-opacity-50 text-white";
?>

<div id="response"></div>

<div class="card <?= $addCardClass ?>">
    <h4 class="card-header <?= $addCardTitleClass ?>">Add note</h4>
    <div class="card-body mb-3">
        <form action="index.php" method="POST">

            <div class="editor">
                <div class="form-group">
                    <input type="text" class="form-control form-control-lg" name="title" placeholder="Title (optional)">
                </div>
                <br>
                <?= textTools() ?>
                <input type="hidden" name="id" value="<?= uniqid("NOTE_") ?>">
                <div class="form-group" style="margin-bottom: 20px;">
                    <textarea class="form-control textarea" name="text" id="newNote" cols="30" rows="10"><?= $edit ?></textarea>
                </div>
            </div>

            <br>
            <?= $md_preview ?>
            <hr>
            <div class="btn-group">
                <button type="submit" class="btn btn-success" name="add"><?= icon('plus-circle') ?> Add</button>
                <button type="submit" class="btn btn-danger delAll" name="delall"><?= icon('trash') ?> Delete all</button>
            </div>
        </form>
    </div>
</div>

<hr>

<?php
$notes = getNotes("notes.json");
if (!empty($notes)) {
    foreach ($notes as $key => $value) {
        $text               = $value["content"];

        $created_datetime   = $value["created_at"];
        $created            = "<br><span class='text-muted'>Created ".convertToRelativeTime($created_datetime)."</span>";

        $modified_datetime  = $value["last_modified_at"];
        $modified           = ($modified_datetime != $created_datetime) ? "<br><span class='text-muted'>Updated ".convertToRelativeTime($modified_datetime)."</span>" : Null;

        $title              = (!empty($value["title"])) ? $value["title"] : $value["id"];

        /* ───────────────────────────────────────────────────────────────────── */
        /*                              EDIT CONTENT                             */
        /* ───────────────────────────────────────────────────────────────────── */
            echo "
            <div class='card editContent ".$editCardClass."' data-key='$key' style='display:none;'>
            <form action='' method='POST'>
                <h4 class='card-header ".$editCardTitleClass."'><input type='text' class='form-control form-control-lg' value='$title' name='title'></h4>
                <div class='card-body'>
                        <input type='hidden' name='id' value='$key'>
                        <div class='form-group'>

                            ".textTools($key)."

                            <textarea class='form-control textarea' name='text' cols='30' rows='10'>$text</textarea>
                            $md_preview

                            <div class='form-group mt-2'>
                                <div class='btn-group'>
                                    <button type='submit' name='update' class='btn btn-success'>".icon('floppy')." Save</button>
                                    <button type='submit' name='del' value='$key' class='btn btn-danger deleteNote'>".icon('trash')." Delete</button>
                                    <button type='button' class='btn btn-secondary cancelEdit' data-key='$key'>".icon('x-circle')." Cancel</button>
                                </div>
                                    $created
                                    $modified
                            </div>
                        </div>
                    </form>
                    </div>
            </div>";

            /* ───────────────────────────────────────────────────────────────────── */
            /*                            DISPLAY CONTENT                            */
            /* ───────────────────────────────────────────────────────────────────── */
            echo "
            <div class='card content ".$notesCardClass."' data-key='$key'>
            <h4 class='card-header ".$notesCardTitleClass."'>$title</h4>
            <div class='card-body'>
                <form action='' method='POST'>
                    <div class='d-flex justify-content-between align-items-center'>
                        <span class='note'>$text</span>

                        <div>
                            $created
                            $modified
                            <br>
                            <div class='btn-group'>
                                <button class='btn btn-primary editNote'>".icon("pen")." Edit</button>
                                <button class='btn btn-danger deleteNote' type='submit' name='del' value='$key'>".icon('trash')." Delete</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            </div>
            <br>
        ";
    }
} else {
    alert("Nothing added yet.", "warning");
}
?>
</div>



</body>

<script>
    $(document).ready(function() {

        /* ───────────────────────────────────────────────────────────────────── */
        /*                          Submit on CTRL+ENTER                         */
        /* ───────────────────────────────────────────────────────────────────── */
        $("textarea").keydown(function(e) {
            if (e.ctrlKey && e.keyCode == 13) {
                var closestForm = $(this).closest("form");
                var submitButton = closestForm.find("button[type='submit']");
                var submitValue = submitButton.attr("name");
                closestForm.append("<input type='hidden' name='" + submitValue + "' value=''>");
                closestForm.submit();
                console.log("Submitting form");
            }
        });

        /* ───────────────────────────────────────────────────────────────────── */
        /*                               MD Preview                              */
        /* ───────────────────────────────────────────────────────────────────── */
        $("textarea").on("keyup", function(e) {
            text = $(this).val();
            var parsed = marked.parse(text);
            // Replace ASCII smiley with emoji
            parsed = parsed.replace(":)", "😊");
            parsed = parsed.replace(/:d/gi, "😁");
            parsed = parsed.replace(/:p/gi, "😛");
            parsed = parsed.replace(":(", "😞");

            $(this).parent().parent().find(".md-preview").html(parsed);
        });

        /* ───────────────────────────────────────────────────────────────────── */
        /*                       FORMAT NOTES WITH MARKDOWN                      */
        /* ───────────────────────────────────────────────────────────────────── */
        $(".note").each(function() {
            text = $(this).text();
            var parsed = marked.parse(text);
            $(this).html(parsed);
        });

        /* ───────────────────────────────────────────────────────────────────── */
        /*                               EDIT NOTE                               */
        /* ───────────────────────────────────────────────────────────────────── */
        $(".editNote").click(function(e) {
            e.preventDefault();

            key     = $(this).closest(".content").data("key");
            note    = $(".content[data-key='"+key+"']");
            edit    = $(".editContent[data-key='"+key+"']");

            note.hide();
            edit.show();

            parsed = marked.parse(note.closest("textarea").text());
        });
        /* ───────────────────────────────────────────────────────────────────── */

        /* ───────────────────────────────────────────────────────────────────── */
        /*                               TEXT TOOLS                              */
        /* ───────────────────────────────────────────────────────────────────── */
        $(".textTools").on('click', function() {
            type = $(this).data("type");
            key  = $(this).parent().data("key");

            if (key == "new") {
                textarea = $("#newNote")[0];
                textarea.focus();
            } else {
                textarea = $(".content[data-key='"+key+"']").find("textarea")[0];
            }

            console.log("Applying "+type+" to note "+key);

            var types = {
                "h1"           : "#",
                "h2"           : "##",
                "h3"           : "###",
                "h4"           : "####",
                "h5"           : "#####",
                "h6"           : "######",
                "bold"         : "**",
                "italic"       : "*",
                "strikethrough": "~~",
                "code"         : "`",
                "blockquote"   : ">",
                "list-ol"      : "1. ",
                "list-ul"      : "- ",
                "text"         : "",
                "left"         : "",
                "underline"    : "__",
            };

            /* ───────────────────────────── SYMBOL ───────────────────────────── */
                var symbol                  = types[type];

            /* ─────────────────────────── TEXT / CURSOR ─────────────────────────── */
                var text                    = textarea.value;
                var start                   = textarea.selectionStart;
                var end                     = textarea.selectionEnd;
                var cursorPosition          = start;
                var sel                     = text.substring(start, end);
                var hasSelection            = (sel.length > 0) ? true : false;

            /* ──────────────────────────────── LINE ─────────────────────────────── */
                // - Indexes
                var startLineIndex          = text.lastIndexOf("\n", start);
                var endLineIndex            = text.indexOf("\n", end);
                // - Content
                var beforeStartLine         = text.substring(0, startLineIndex);
                var afterEndLine            = text.substring(endLineIndex);
                // - Line number / text
                var currentLine             = text.substring(startLineIndex, endLineIndex);
                var currentLineText         = currentLine.trim();
                // SELECTION / CURSOR
                var beforeStart             = text.substring(0, start);
                var afterEnd                = text.substring(end);



            /* ───────────────────────────────────────────────────────────────────── */
            /*                             WRAP / NOWRAP                             */
            /* ───────────────────────────────────────────────────────────────────── */
            var wrap                    = false; 
            var wrapTypes               = ["bold", "italic", "strikethrough", "code", "blockquote", "underline"];
            if (wrapTypes.includes(type)) {
                wrap = true;
            }

            /* ───────────────────────────────────────────────────────────────────── */
            /*                                  WRAP                                 */
            /* ───────────────────────────────────────────────────────────────────── */
            if (wrap) {

                // SELECTION == TRUE
                if (hasSelection) {
                    textarea.value = symbol + sel + symbol;
                }

                // SELECTION == FALSE
                else if (!hasSelection) {
                    textarea.value = beforeStartLine + currentLineText + afterEndLine;
                }

            }
            /* ───────────────────────────────────────────────────────────────────── */

            /* ───────────────────────────────────────────────────────────────────── */
            /*                                NO WRAP                                */
            /* ───────────────────────────────────────────────────────────────────── */
            if (!wrap) {

                // SELECTION == TRUE
                if (hasSelection) {
                    textarea.value = "\n" + symbol + " " + sel;
                }

                // SELECTION == FALSE
                else if (!hasSelection) {
                    textarea.value = beforeStartLine + symbol + " " + currentLineText + afterEndLine;
                }

            }
            /* ───────────────────────────────────────────────────────────────────── */

            textarea.selectionStart = endLineIndex;
            textarea.focus();

            /* ───────────────────────────────────────────────────────────────────── */
            /*                                 DEBUG                                 */
            /* ───────────────────────────────────────────────────────────────────── */
            console.log("wrap = "+wrap);
            console.log("type = "+type);
            console.log("hasSelection = "+hasSelection);
            console.log("sel.length = "+sel.length);
            /* ───────────────────────────────────────────────────────────────────── */
        });
        /* ───────────────────────────────────────────────────────────────────── */


        /* ───────────────────────────────────────────────────────────────────── */
        /*                              CANCEL EDIT                              */
        /* ───────────────────────────────────────────────────────────────────── */
        $(".cancelEdit").click(function(e) {
            e.preventDefault();
            key = $(this).data("key");

            note = $(".content[data-key='"+key+"']");
            edit = $(".editContent[data-key='"+key+"']");

            note.show();
            edit.hide();
        });
        /* ───────────────────────────────────────────────────────────────────── */

        /* ───────────────────────────────────────────────────────────────────── */
        /*                              DELETE NOTE                              */
        /* ───────────────────────────────────────────────────────────────────── */
        $(".deleteNote").click(function(e) {
            e.preventDefault();
            key = $(this).val();
            console.log("Deleting note "+key);
            $(".content[data-key='"+key+"']").remove();

            // Submit form to formhandler.php
            var closestForm = $(this).closest("form");
            var id          = $(this).val();

            // Submit AJAX request to formhandler.php
            $.ajax({
                type: "POST",
                url: "api.php",
                data: {
                    del: id
                },
                success: function(data) {
                    console.log(data);
                    $("#response").html(data);
                }
            });

        });
        /* ───────────────────────────────────────────────────────────────────── */

        /* ───────────────────────────────────────────────────────────────────── */
        /*                               DELETE ALL                              */
        /* ───────────────────────────────────────────────────────────────────── */
        $(".delAll").click(function(e) {
            e.preventDefault();

            // Submit form to formhandler.php
            var closestForm = $(this).closest("form");

            // Submit AJAX request to formhandler.php
            $.ajax({
                type: "POST",
                url: "api.php",
                data: {
                    delall: 1
                },
                success: function(data) {
                    console.log(data);
                    $("#response").html(data);
                }
            });

        });

        /* ───────────────────────────────────────────────────────────────────── */
        /*                           DELETE ALL CONFIRM                          */
        /* ───────────────────────────────────────────────────────────────────── */
        $(document).on("click", ".delAllConfirm", function(e) {
            e.preventDefault();

            console.log("Deleting all notes");

            // Submit form to formhandler.php
            var closestForm = $(this).closest("form");

            // Submit AJAX request to formhandler.php
            $.ajax({
                type: "POST",
                url: "api.php",
                data: {
                    delallconfirm: 1
                },
                success: function(data) {
                    console.log(data);
                    $("#response").html(data);
                    $(".content").remove();
                }
            });
        });

    });
</script>


<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

</html>