$(document).ready(function () {
    var token = "YOUR_TOKEN_HERE";
    $("#address").suggestions({
        token: token,
        type: "ADDRESS",
        /* Вызывается, когда пользователь выбирает одну из подсказок */
        onSelect: function (suggestion) {
            console.log(suggestion);
            setValue(suggestion['unrestricted_value']);
        }
    });
});
