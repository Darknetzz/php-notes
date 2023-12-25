<script>
    $(document).ready(function() {

        /* ───────────────────────────────────────────────────────────────────── */
        /*                              Definitions                              */
        /* ───────────────────────────────────────────────────────────────────── */
        var addNoteDiv  = $("#addNoteDiv");
        var addNoteCard = $("#addNoteCard");
        var addNoteForm = $("#addNoteForm");
        var allNotes    = $("#allNotes");

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

            key     = $(this).closest(".displayContent").data("key");
            note    = $(".displayContent[data-key='"+key+"']");
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
                textarea = $(".displayContent[data-key='"+key+"']").find("textarea")[0];
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

            note = $(".displayContent[data-key='"+key+"']");
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
            $(".displayContent[data-key='"+key+"']").remove();

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
                    $(".displayContent").remove();
                }
            });
        });


        /* ───────────────────────────────────────────────────────────────────── */
        /*                                ADD NOTE                               */
        /* ───────────────────────────────────────────────────────────────────── */
        $(document).on("click", ".addNote", addNote);

        function addNote(e) {
            e.preventDefault();

            // Submit form to formhandler.php
            var closestForm = $(this).closest("form");
            var id_input    = closestForm.find("input[name='id']");
            var id          = id_input.val();
            var title       = closestForm.find("input[name='title']").val();
            var text        = closestForm.find("textarea[name='text']").val();

            // Submit AJAX request to formhandler.php
            $.ajax({
                type: "POST",
                url: "api.php",
                data: {
                    id: id,
                    add: id,
                    title: title,
                    text: text
                },
                success: function(data) {
                    console.log(data);
                    $("#response").html(data);
                }
            });
        }

        /* ───────────────────────────────────────────────────────────────────── */
        /*                               TOGGLE BTN                              */
        /* ───────────────────────────────────────────────────────────────────── */
        $(".toggleBtn").on("click", function(e) {

            e.preventDefault();

            var target      = $(this).data("target");
            var target_obj  = $(target);
            var is_visible  = target_obj.is(":visible");
            var icon        = $(this).find("i").html();
            var text        = $(this).text();
            
            console.log("Toggling "+text);

            if (is_visible) {
                $(this).html("<?= icon('eye-slash') ?> "+text);
                target_obj.hide();
                return;
            }

            $(this).html("<?= icon('eye') ?> "+text);
            target_obj.show();
            
        });

        /* ───────────────────────────────────────────────────────────────────── */
        /*                                NAV BTN                                */
        /* ───────────────────────────────────────────────────────────────────── */
        $(".navBtn").on("click", function(e) {
            e.preventDefault();
            
            
            $(".navBtn").removeClass("active");
            $(this).addClass("active");

            $(".displayContent").hide();
            
            var target = $(this).data("target");
            console.log("Navigating to "+target);
            
            $(target).show();
        });

        });

</script>