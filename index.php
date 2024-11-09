<!DOCTYPE html>
<!-- Import latest bootstrap and jquery -->
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NOTES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/2.1.0/showdown.min.js"></script>
    <style>
        .textbox {
            border: 1px solid #555;
            border-top: 3px solid rgb(47, 111, 179); /* Different color for the top border */
            padding: 10px;
            margin-bottom: 10px;
        }
        .text {
            min-height: 200px;
        
        }
    </style>


    <!-- Ace editor -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.36.4/ace.min.js"></script>
    <link href=" https://cdn.jsdelivr.net/npm/ace-builds@1.36.4/css/ace.min.css " rel="stylesheet">

</head>

<body data-bs-theme="dark">

<div class="container" style="margin-top:5px">

<?php
require_once("functions.php");
require_once("config.php");
$notesFile = "notes.json";
$notes     = getNotes($notesFile);
$edit      = "";

# NOTE: Form submission
do {

    $do    = (!empty($_REQUEST['do']) ? $_REQUEST['do'] : "add");
    $text  = (!empty($_REQUEST['md']) ? $_REQUEST['md'] : Null);
    $id    = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : Null);
    $debug = "
        <div id='debugInfo' class='card' style='display:none;'>
            <h4 class='card-header bg-secondary-subtle'>Debug Info</h4>
            <div class='card-body'>
                <pre class='text-primary'>".json_encode($_REQUEST, JSON_PRETTY_PRINT)."</pre>
            </div>
        </div>
    ";

    if (!empty($text)) {
        if ($do == "add") {
            $safe_text = htmlspecialchars($text);
            array_push($notes, ["note" => $safe_text, "date" => date("Y-m-d H:i:s")]);
            $notes_json = json_encode($notes);
            file_put_contents($notesFile, $notes_json);
            echo alert("Note added successfully.", "success");
        }
    
        if ($do == "edit") {
            if (empty($id)) {
                echo alert("No ID provided when editing note.");
                break;
            }
            $safe_text = htmlspecialchars($text);
            $notes[$id] = ["note" => $safe_text, "date" => date("Y-m-d H:i:s")];
            $notes_json = json_encode($notes);
            file_put_contents($notesFile, $notes_json);
            echo alert("Note updated successfully.", "success");
            $edit = "";
        }
    }

    if (isset($_REQUEST['delall']) && !empty($notes)) {
        echo alert("
        <h4>".icon('exclamation-triangle')." Are you sure you want to delete <b>all</b> your notes? This cannot be undone!</h4>
        <hr>
        <form action='index.php' method='POST'>
            <button type='submit' class='btn btn-danger' name='delallconfirm'>".icon('trash')." Delete all</button>
            <a href='index.php' class='btn btn-secondary'>".icon('x-circle')." Cancel</a>
        </form>", "danger", False);
    }
    
    if (isset($_REQUEST['delallconfirm']) && !empty($notes)) {
        $notes_json = json_encode([]);
        file_put_contents($notesFile, $notes_json);
        echo alert("All notes deleted successfully.", "success");
    }
    
    if (isset($_REQUEST['del'])) {
        if (!empty($notes[$_REQUEST['del']])){
            unset($notes[$_REQUEST['del']]);
            $notes_json = json_encode($notes);
            file_put_contents($notesFile, $notes_json);
            echo alert("Note deleted successfully.", "success");
        } else {
            echo alert("Note not found.", "warning");
        }
    }

    unset($_REQUEST, $_GET, $_POST, $id, $text, $do);

} while (False);
?>

<?= $debug ?>

<div class="card mt-3">
    <div class="card-header bg-primary-subtle d-flex justify-content-between">
        <h3>Notes</h3>
        <button id="showDebugInfo" class="btn btn-primary"><?= icon("bug") ?></button>
    </div>
    <div class="card-body">
        <form action="index.php" class="noteForm" method="POST">
            <input type="hidden" name="id" class="noteFormHiddenInput">
            <input type="hidden" name="do" class="noteFormHiddenInput">
            <input type="hidden" name="md" class="noteFormHiddenInput">
        <!-- NOTE: textarea -->
            <div id="text"><?= $edit ?></div>
            <br>
            <div id="formAddButtons" class="btn-group formBtnGroup" style="display:none;">
                <button type='button' id="addNoteBtn" class='btn btn-success'><?= icon('plus-circle') ?> Add</button>
                <button type='submit' class='btn btn-danger' name='delall'><?= icon('trash') ?> Delete all</button>
            </div>
            <div id="formEditButtons" class="btn-group formBtnGroup" style="display:none;">
                <button type='button' id='updateNoteBtn' class='btn btn-success'><?= icon('floppy') ?> Update</button>
                <button type='button' id='cancelNoteBtn' class='btn btn-secondary'><?= icon('x-circle') ?> Cancel</button>
            </div>
        </form>
    </div>
</div>
<hr>

<?php
$notes = getNotes("notes.json");
if (!empty($notes)) {
    foreach ($notes as $key => $value) {
        $note = $value;
        $date = "";
        if (is_array($value)) {
            $note = $value['note'];
            $date = $value['date'];
        }
        if (STRICT_LINEBREAK === False) {
            $value = str_replace("\n", "\n\n", $value);
        }
        echo "
        <div class='textbox' data-id='$key'>
            <div class='d-flex justify-content-between'>
                <div class='md'>$note</div>
                <div class='markdownCode' style='display:none;'>$note</div>
                ".(!empty($date) ? "<div class='text-muted' title='$date'>".relativeTime($date)."</div>" : "")."
            </div>
            <hr>
            <div class='btn-group'>
                <a href='?edit=$key' class='btn btn-primary editNote'>".icon("pen")." Edit</a>
                <a href='?del=$key' class='btn btn-danger'>".icon('trash')." Delete</a>
            </div>
        </div>";
    }
} else {
    alert("Nothing added yet.", "warning");
}
?>
</div>
</body>

<script>

    // Initialize Ace editor
    const editor = ace.edit("text");
    // editor.setTheme("ace/theme/monokai");
    // editor.session.setMode("ace/mode/markdown");
    editor.setOptions({
        theme                 : "ace/theme/monokai",
        mode                  : "ace/mode/markdown",
        maxLines              : Infinity,
        wrap                  : true,
        minLines              : 20,
        tabSize               : 2,
        fontSize              : "1.5rem",
        copyWithEmptySelection: true,
        enableAutoIndent      : true,
    });

    // NOTE: document.ready
    $(document).ready(function() {

        // REVIEW: This doesn't work with Ace editor.
        // Submit form with Ctrl+Enter
        // $("#text").keydown(function(event) {
        //     event.preventDefault();
        //     if (event.ctrlKey && event.key === 'Enter') {
        //         event.preventDefault();
        //         $("#text").closest('form').submit();
        //     }
        // });
        editor.commands.addCommand({
            name   : 'submit',
            bindKey: {win: 'Ctrl-Enter',  mac: 'Command-Enter'},
            exec   : function(editor) {
                $("#updateNoteBtn").click();
            }
        });

        $(".md").each(function() {
            showdownOpts = {
                tables               : true,
                strikethrough        : true,
                tasklists            : true,
                simpleLineBreaks     : true,
                openLinksInNewWindow : true,
                emoji                : true,
                parseImgDimensions   : true,
                simplifiedAutoLink   : true,
            };
            var converter = new showdown.Converter(showdownOpts),
                text      = $(this).text(),
                html      = converter.makeHtml(text);
            $(this).html(html);
        });

        /* ────────────────────────────────────────────────────────────────────────── */
        /*                                    FORM                                    */
        /* ────────────────────────────────────────────────────────────────────────── */
        const form           = $(".noteForm");
        const formIdInput    = form.find("input[name='id']").prop("value", null);
        const formDoInput    = form.find("input[name='do']").val("add");
        const formMdInput    = form.find("input[name='md']");
        const formEditBtns   = form.find("#formEditButtons");
        const formAddButtons = form.find("#formAddButtons").show();


        console.log("formIdInput: " + formIdInput.length);
        console.log("formDoInput: " + formDoInput.length);
        console.log("formMdInput: " + formMdInput.length);

        $(".editNote").on("click", function(e) {
            e.preventDefault();
            var thisTextbox    = $(this).parents().closest(".textbox");
            var id             = thisTextbox.data("id");
            var markdownCode   = thisTextbox.find(".markdownCode").text();
            console.log(`Editing note #${id}`);
            
            if (markdownCode.length === 0) {
                console.log("Empty .markdownCode");
                return;
            }
            if (typeof id !== 'undefined' && Number.isInteger(parseInt(id))) {
                // id is set and is an integer
            }

            formIdInput.val(id);
            formDoInput.val("edit");
            editor.setValue(markdownCode);
            formEditBtns.show();
            formAddButtons.hide();
        });

        // updateNoteBtn
        $("#updateNoteBtn").on("click", function(e) {
            e.preventDefault();
            var markdown = editor.getValue();
            if (markdown.length === 0) {
                console.log("markdown empty");
                return;
            }
            formDoInput.val("edit");
            formMdInput.val(markdown);
            form.submit();
        });

        // addNoteBtn
        $("#addNoteBtn").on("click", function(e) {
            e.preventDefault();
            var markdown = editor.getValue();
            if (markdown.length === 0) {
                console.log("markdown empty");
                return;
            }
            formDoInput.val("add");
            formMdInput.val(markdown);
            form.submit();
        });

        // cancelNoteBtn
        $("#cancelNoteBtn").on("click", function() {
            formIdInput.prop("value", null);
            editor.setValue(null);
            formMdInput.val("");
            formDoInput.val("add");
            formAddButtons.show();
            formEditBtns.hide();
        });

        // DebugInfo
        $("#showDebugInfo").on("click", function() {
            console.log("Toggling #debugInfo")
            $("#debugInfo").toggle();
        });


    }); // end document.ready

</script>