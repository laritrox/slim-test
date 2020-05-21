$("#button").click(function () {
    $.ajax({
        type: "POST",
        url: "/",
        data: { text: $("#text").val() }
    }).done(function (msg) {
        $("#text").val(msg);
    });
});