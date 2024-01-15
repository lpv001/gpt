// $("#reciever").hide();
// $("#tranferByPhone").hide();
// $("#tranferByAccount").hide();
$("#p-body").hide();

// $(document).ready(function () {
$(".input-images").imageUploader({
    imagesInputName: "screen_shot",
    preloadedInputName: "old",
    maxSize: 2 * 1024 * 1024,
    maxFiles: 10,
});
// });

$(".account-provider").on("click", function () {
    $("#reciever").show();
    $("#p-body").show();

    $("#payeeAccountName").text(": " + $(this).data("name") ?? "None");
    $("#payeeAccountNumber").text(": " + $(this).data("account") ?? "None");
    $("#payeePhoneNumber").text(": " + $(this).data("phone") ?? "None");

    $("[name=payment_account_id]").val($(this).data("id"));
    $("#qrCode").attr("src", $(this).data("qrcode"));
    // $("#qrCode").attr("alt", $(this).data("qrcode"));

    let body = $(this).data("body");
    let html = "";
    if (body.length > 0) {
        $.each(body, function (key, body) {
            html += `
                <div class="col-sm-12 mt-3">
                    <label for="${key}">${body.field_name.toUpperCase()}</label>
                    <input type="text" class="form-control" name="${body.field_name.toLowerCase()}" value="" placeholder="${body.field_name.toUpperCase()}">
                </div>
            `;
        });

        $("#myBody").html(html);
    }
});
