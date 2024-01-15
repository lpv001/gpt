$(document).ready(function () {
    $("#selectAll").attr("checked", "checked");
    $(".checkbox").attr("checked", "checked");
    selectCart();
});

$(document).on("click", "#selectAll", function () {
    $(".checkbox").prop("checked", $(this).prop("checked"));
});

// delete cart item by id
$(document).on("click", ".btn-delete", function (event) {
    event.preventDefault();
    $(this).closest("div.cart-items").remove();
    if ($(".carts").children().length <= 0) {
        $("#empty-item").css("display", "block");
    }

    $.ajax({
        type: "POST",
        url: `/cart/destroy/${$(this).data("id")}`,
        data: {
            _token: "{{ csrf_token() }}",
        },
        success: function (response) {
            console.log(response);
            $("#subtotal").text("US $" + response.total);
            $("#total").text("US $" + response.total);
            $(".shopping-cart-qty").text(response.data);
        },
    });
});

//
$(document).on("click", ".btn-increa", function (event) {
    event.preventDefault();
    let qtyElement = $(this).closest("div.section-qty").find("span");
    let qty = parseInt(qtyElement.text());
    let total = 0;

    qty = $(this).data("increa") ? ++qty : --qty;
    total = qty <= 0 ? 1 : qty;
    qtyElement.text(total);
    // $(this).closest('div.cart-items').find('[name="qty[]"]').val(total);

    let url = "{{ route('update-cart', ['id']) }}";
    $.ajax({
        type: "PUT",
        url: url.replace("id", $(this).data("id")),
        data: {
            _token: "{{ csrf_token() }}",
            qty: total,
        },
        success: function (response) {
            $(this)
                .closest("div.cart-items")
                .find('[name="qty[]"]')
                .val(response.sub_total);
        },
    });
});

$(document).on("click", ".select-cart", function () {
    selectCart();
});

function selectCart() {
    let total = 0;
    $(".select-cart").each(function (key, element) {
        // console.log(element)
        if ($(element).prop("checked")) {
            price = $(element)
                .closest("div.cart-items")
                .find('[name="price[]"]')
                .val();
            qty = $(element)
                .closest("div.cart-items")
                .find('[name="qty[]"]')
                .val();
            if (typeof qty !== "undefined" && typeof price !== "undefined") {
                total += price * qty;
                $("#form_checkout").append(
                    `<input type="hidden" name="carts[]" value="${$(
                        element
                    ).data("id")}">`
                );
            }
        }
    });

    $("#subtotal").text(`US $${total.toFixed(2)}`);
    $("#total").text(`US $${total.toFixed(2)}`);

    if (total <= 0) {
        $(".checkout").attr("disabled", true);
    } else {
        $(".checkout").attr("disabled", false);
    }
}

$(".qty-increa").on("click", function () {
    let subTotal = parseFloat($("#subtotal").text().split("$")[1]);
    let total = parseFloat($("#total").text().split("$")[1]);

    let totalPrice = $(this).data("price") + subTotal;
    $("#subtotal").text("US $" + totalPrice.toFixed(2));
    $("#total").text("US $" + totalPrice.toFixed(2));
});

$(".qty-decrea").on("click", function () {
    let subTotal = parseFloat($("#subtotal").text().split("$")[1]);
    let total = parseFloat($("#total").text().split("$")[1]);

    let totalPrice = subTotal - $(this).data("price");
    if (totalPrice <= 0) return;

    $("#subtotal").text("US $" + totalPrice.toFixed(2));
    $("#total").text("US $" + totalPrice.toFixed(2));
});
