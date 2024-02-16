<script>
    $(document).ready(function() {

        /* ───────────────────────────────────────────────────────────────────── */
        /*                              Definitions                              */
        /* ───────────────────────────────────────────────────────────────────── */
        var addNoteDiv          = $("#addNoteDiv");
        var addNoteCard         = $("#addNoteCard");
        var addNoteForm         = $("#addNoteForm");
        var notes               = $("#notes");
        var responseDiv         = $("#response");
        var breadcrumbs         = $(".breadcrumb");
        var h                   = window.location.hash;
        var justLoaded          = false;
        var lastClickedNavBtn   = null;

        /* ───────────────────────────────────────────────────────────────────── */
        /*                          Submit on CTRL+ENTER                         */
        /* ───────────────────────────────────────────────────────────────────── */
        $(".textarea").keydown(function(e) {
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
        $(".textarea").on("keyup", function(e) {
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

            key     = $(this).closest(".noteCard").data("key");
            note    = $(".noteCard[data-key='"+key+"']");
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
                textarea = $(".noteCard[data-key='"+key+"']").find("textarea")[0];
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

            note = $(".noteCard[data-key='"+key+"']");
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
            $(".noteCard[data-key='"+key+"']").remove();

            // Submit form to formhandler.php
            var closestForm = $(this).closest("form");
            var id          = $(this).val();

            // Submit AJAX request to formhandler.php
            $.ajax({
                type: "POST",
                url: "includes/api.php",
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
                url: "includes/api.php",
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
                url: "includes/api.php",
                data: {
                    delallconfirm: 1
                },
                success: function(data) {
                    console.log(data);
                    $("#response").html(data);
                    $(".noteCard").remove();
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
                url: "includes/api.php",
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
        // This is supposed to toggle the visibility of the target, as opposed
        // to navBtn, which is supposed to hide everything, and then show the
        // desired page.
        /* ───────────────────────────────────────────────────────────────────── */
        // $(".toggleBtn").on("click", function(e) {

        //     e.preventDefault();

        //     var target      = $(this).data("target");
        //     var target_obj  = $(target);
        //     var is_visible  = target_obj.is(":visible");
        //     var icon        = $(this).find("i").html();
        //     var text        = $(this).text();
            
        //     console.log("Toggling "+target);

        //     var toggle_on   = icon+" "+text+" <?= icon('eye-slash') ?>";
        //     var toggle_off  = icon+" "+text+" <?= icon('eye') ?>";
        //     var on_class    = "text-success";
        //     var off_class   = "text-secondary";

        //     if (is_visible) {
        //         $(this).html(toggle_off);
        //         $(this).addClass(off_class).removeClass(on_class);
        //         target_obj.hide();
        //         return;
        //     }

        //     $(this).html(toggle_on);
        //     $(this).addClass(on_class).removeClass(off_class);
        //     target_obj.show();
            
        // });

        /* ───────────────────────────────────────────────────────────────────── */
        /*                                NAV BTN                                */
        /* ───────────────────────────────────────────────────────────────────── */
        // This is supposed to hide everything, and then show the desired page,
        // as opposed to toggleBtn, which toggles the visibility of the target.
        /* ───────────────────────────────────────────────────────────────────── */
        $(".navigator-link").on("click", function(e) {
            e.preventDefault();

            var href = $(this).find("a").attr("href");
            console.log(".navigator-link clicked, sending to "+href);
            if (href[0] == "#") {

                if (h == href) {
                    console.log("Already on "+href);
                    return;
                }
                
                navigate(href);
            }
        });

        /* ───────────────────────────────────────────────────────────────────── */
        /*                           update breadcrumbs                          */
        /* ───────────────────────────────────────────────────────────────────── */
        /**
         * Updates the breadcrumb navigation based on the current page.
         */
        function updateBreadcrumb() {
            var breadcrumb_count = breadcrumbs.find("li").length;

            if (breadcrumb_count > 10) {
                // Remove first breadcrumb
                breadcrumbs.find("li:first-child").remove();
            }

            var current_page = $(".page:visible").attr("id");

            // Find breadcrumbs with current page as text and remove them
            breadcrumbs.find("li").each(function() {
                if ($(this).text() == current_page) {
                    $(this).remove();
                }
            });

            breadcrumbs.append("<li class='breadcrumb-item'><a href='#"+current_page+"'>"+current_page+"</li>");
        }

         /* ───────────────────────────────────────────────────────────────────── */
         /*                                navigate                               */
         /* ───────────────────────────────────────────────────────────────────── */
        /**
         * Function to navigate to a target page and update the UI accordingly.
         *
         * @param string $target The target page to navigate to.
         * @param null|jQuery $button The button element that triggered the navigation (optional).
         * @return void
         */
        function navigate(target, button = null) {

            justLoaded = false;

            if (h == target) {
                console.log("Already on "+target);
                // responseDiv.html("Already on "+target);
                return;
            }

            if (target == undefined) {
                console.log("Target is undefined");
                responseDiv.html("Target is undefined");
                $("#404").show();
                return;
            }

            $(".page").hide();
            $(".page").removeClass("current");
            $(".navBtn").removeClass(on_class);

            if (button != null) {
                var icon        = button.find("i").html();
                var text        = button.text();

                var toggle_on   = icon+" "+text+" <?= icon('eye-slash') ?>";
                var toggle_off  = icon+" "+text+" <?= icon('eye') ?>";
                var on_class    = "text-success";
                var off_class   = "text-secondary";

                button.removeClass(off_class);
                button.addClass(on_class);
            }
            
            var target_obj  = $(target);
            target_obj.addClass("current");

            // Update url with hashtag
            window.location.hash = h = target.substring(1);

            // check if target object exists
            if (target_obj.length == 0) {
                $("#404").show();
                // $("#response").html("Target object "+target+" does not exist");
                console.log("Target object "+target+" does not exist");
                return;
            }

            target_obj.show();
            updateBreadcrumb();
        }

        /* ───────────────────────────────────────────────────────────────────── */
        /*                             INITIAL STATES                            */
        /* ───────────────────────────────────────────────────────────────────── */
        $(".page").hide();
        $("#home").show();
        $(".editContent").hide();

});
</script>