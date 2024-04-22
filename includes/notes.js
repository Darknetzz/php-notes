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
});