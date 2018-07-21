$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "modules/news/update_news.php",
        success: null,
        dataType: "json"
    });
});