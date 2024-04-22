$(document).ready(function() {
    
console.log("Document ready.");

// Submit form with Ctrl+Enter
$("#text").keydown(function(event) {
    if (event.ctrlKey && event.key === 'Enter') {
        event.preventDefault();
        $("#text").closest('form').submit();
    }
});

$(".md").each(function() {
    showdownOpts = {
        tables: true,
        strikethrough: true,
        tasklists: true,
        simpleLineBreaks: true,
        openLinksInNewWindow: true,
        emoji: true,
        parseImgDimensions: true,
        simplifiedAutoLink: true,
    };
    var converter = new showdown.Converter(showdownOpts),
        text      = $(this).text(),
        html      = converter.makeHtml(text);
    $(this).html(html);
    console.log("Converted markdown to HTML.");
});

$(".debugBtn").on('click', function() {
    console.log("Debug button clicked.");
    $(".debugInfo").toggle();
});

}); // End of $.ready