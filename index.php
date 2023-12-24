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
        .textbox {
            border: 1px solid #555;
            padding: 10px;
            margin-bottom: 10px;
        }

        .md-preview {
            border: 1px solid #555;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>

</head>

<body data-bs-theme="dark">

<div class="container" style="margin-top:15px">

<?php
require_once "functions.php";
$notesFile = "notes.json";
$notes = getNotes($notesFile);
$edit  = "";
$md_preview = "
<h5>Preview:</h5>
<div class='md-preview'><span class='text-muted'>When you start typing, you can see the preview here.</span></div>
";

require_once("formhandler.php");
?>

<div class="card">
    <h3 class="card-header">Notes</h3>
    <div class="card-body">
        <form action="index.php" method="POST">
            <?= textTools() ?>
            <textarea class="form-control" name="text" id="newNote" cols="30" rows="10"><?= $edit ?></textarea>
            <br>
            <?= $md_preview ?>
            <br>
            <div class="btn-group">
                <?php
                if (isset($_GET['edit'])) {
                    echo "
                    <button type='submit' class='btn btn-success' name='update'>".icon('floppy')." Update</button>
                    <a href='index.php' class='btn btn-secondary'>".icon('x-circle')." Cancel</a>";
                } else {
                    echo '
                    <button type="submit" class="btn btn-success" name="add">'.icon('plus-circle').' Add</button>
                    <button type="submit" class="btn btn-danger" name="delall">'.icon('trash').' Delete all</button>';
                }
                ?>
            </div>
        </form>
    </div>
</div>
<hr>

<?php
$notes = getNotes("notes.json");
if (!empty($notes)) {
    foreach ($notes as $key => $value) {
        echo "
            <div class='textbox' data-key='$key'>

            <div class='editContent' data-key='$key' style='display:none;'>
                <form action='' method='POST'>
                    <input type='hidden' name='id' value='$key'>
                    <div class='form-group'>

                        ".textTools($key)."

                        <textarea class='form-control' name='text' cols='30' rows='10'>$value</textarea>
                        <div class='form-group mt-2'>
                            <div class='btn-group'>
                                <button type='submit' name='update' class='btn btn-success'>".icon('floppy')." Save</button>
                                <button type='submit' name='del' value='$key' class='btn btn-danger'>".icon('trash')." Delete</button>
                                <button type='button' class='btn btn-secondary cancelEdit' data-key='$key'>".icon('x-circle')." Cancel</button>
                            </div>
                        </div>
                    </div>
                    $md_preview
                </form>
            </div>

            <div class='content' data-key='$key'>
            <form action='' method='POST'>
                <div class='d-flex justify-content-between align-items-center'>
                    <span class='note'>$value</span>

                    <div class='btn-group'>
                        <button class='btn btn-primary editNote'>".icon("pen")." Edit</button>
                        <button class='btn btn-danger' type='submit' name='del' value='$key'>".icon('trash')." Delete</button>
                    </div>
                </div>
            </form>

            </div>

            </div>
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
        $("textarea").keyup(function(e) {
            text = $(this).val();
            var parsed = marked.parse(text);
            // Replace ASCII smiley with emoji
            parsed = parsed.replace(":)", "😊");
            parsed = parsed.replace(/:d/gi, "😁");
            parsed = parsed.replace(/:p/gi, "😛");
            parsed = parsed.replace(":(", "😞");
            
            $(this).parent().find(".md-preview").html(parsed);
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

            console.log("Editing note "+key);
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
                textarea = $(".textbox[data-key='"+key+"']").find("textarea")[0];
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
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

</html>