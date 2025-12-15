$(document).ready(function () {
    //manage_users//
    $(".role-btn").on("click", function () {
        const role = $(this).data("role");
        const baseUrl = window.routes.adminUsers;
        const url = role ? baseUrl + "?role=" + role : baseUrl;

        $(".role-btn").removeClass("btn-primary").addClass("btn-default");
        $(this).removeClass("btn-default").addClass("btn-primary");

        $(".user-container").html(
            '<div class="col-12 text-center py-4"><i class="fa fa-spinner fa-spin fa-2x"></i> Đang tải...</div>'
        );

        $.ajax({
            url: url,
            type: "GET",
            success: function (html) {
                $(".user-container").html(html);
            },
            error: function () {
                $(".user-container").html(
                    '<div class="col-12 text-center text-danger">Lỗi tải dữ liệu!</div>'
                );
            },
        });
    });

    $(document).on("click", ".changeStatus", function () {
        const userId = $(this).data("userid");
        const status = $(this).data("status");

        $.ajax({
            url: "/admin/users/updateStatus",
            type: "POST",
            data: {
                user_id: userId,
                status: status,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                toastr.success(res.message);
                $(".role-btn.btn-primary").click();
            },
            error: function (xhr) {
                if (xhr.status === 403) {
                    toastr.error("Bạn không có quyền thực hiện thao tác này.");
                } else {
                    toastr.error("Có lỗi xảy ra, vui lòng thử lại.");
                }
            },
        });
    });

    //manage_posts//
    $(".post-image").on("change", function () {
        let id = $(this).data("id");
        let preview = $("#image-preview-" + id);
        let file = this.files[0];

        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                preview.attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    $(".btn-update-submit-post").on("click", function () {
        let id = $(this).data("id");
        let form = $("#update-post-" + id)[0];

        $("#post-title-" + id).val(editors["title-" + id].getData());
        $("#post-excerpt-" + id).val(editors["excerpt-" + id].getData());
        $("#post-content-" + id).val(editors["content-" + id].getData());

        let formData = new FormData(form);
        formData.append("id", id);

        $.ajax({
            url: "/admin/post/update",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                let cleanTitle = $("<div>").html(response.title).text();
                let cleanExcerpt = $("<div>").html(response.excerpt).text();

                $("#post-row-" + id + " td:nth-child(1) img").attr(
                    "src",
                    response.image_url
                );
                $("#post-row-" + id + " td:nth-child(2)").text(cleanTitle);
                $("#post-row-" + id + " td:nth-child(3)").text(cleanExcerpt);

                $("#modalUpdate-" + id).modal("hide");
                alert("Cập nhật bài viết thành công");
            },
            error: function (xhr) {
                alert("Có lỗi xảy ra, vui lòng thử lại");
                console.log(xhr.responseText);
            },
        });
    });

    $(document).on("click", ".btn-delete-post", function () {
        let id = $(this).data("id");
        let row = $(this).closest("tr");

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        if (!confirm("Bạn có chắc chắn muốn xóa bài viết này?")) {
            return;
        }

        $.ajax({
            url: "/admin/posts/" + id,
            type: "POST",
            success: function (res) {
                if (res.status === "success") {
                    row.fadeOut(300, function () {
                        $(this).remove();
                    });
                }
            },
            error: function () {
                alert("Đã xảy ra lỗi. Không thể xóa bài viết.");
            },
        });
    });

    $(document).on("click", ".btn-toggle-status", function () {
        let id = $(this).data("id");
        let button = $(this);
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: `/admin/posts/toggleStatus/${id}`,
            type: "POST",
            success: function (res) {
                if (res.success) {
                    button.text(res.status);

                    if (res.status === "published") {
                        button
                            .removeClass("btn-secondary")
                            .addClass("btn-success");
                    } else {
                        button
                            .removeClass("btn-success")
                            .addClass("btn-secondary");
                    }
                }
            },
            error: function () {
                toastr.error("Có lỗi xảy ra!");
            },
        });
    });

    $("#post-images").on("change", function () {
        let preview = $("#image-preview-container");
        preview.html("");

        [...this.files].forEach((file) => {
            let reader = new FileReader();
            reader.onload = (e) => {
                let img = $("<img/>", {
                    src: e.target.result,
                    style: "width:120px; height:120px; object-fit:cover; border:1px solid #ccc; border-radius:6px;",
                });
                preview.append(img);
            };
            reader.readAsDataURL(file);
        });
    });

    //manage_category
    $(document).on("submit", ".update-category-form", function (e) {
        e.preventDefault();

        let form = $(this);
        let id = form.data("id");
        let url = form.data("url");

        $.ajax({
            url: url,
            type: "POST",
            data: form.serialize(),
            success: function (res) {
                if (res.success) {
                    let row = $("#category-row-" + id);
                    row.find("td:eq(0)").text(res.data.name);
                    row.find("td:eq(1)").text(res.data.slug);
                    row.find("td:eq(2)").text(res.data.description);

                    $("#modalUpdate-" + id).modal("hide");
                    toastr.success(res.message);
                }
            },
            error: function () {
                toastr.error("Có lỗi xảy ra!");
            },
        });
    });

    $("#add-category-form").submit(function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.data("url");

        $.ajax({
            url: url,
            type: "POST",
            data: form.serialize(),
            success: function (res) {
                if (res.success) {
                    toastr.success(res.message);
                    form.trigger("reset");
                }
            },
            error: function () {
                toastr.error("Lỗi thêm danh mục");
            },
        });
    });

    $(document).on("click", ".btn-delete-category", function () {
        if (!confirm("Bạn có chắc muốn xóa?")) return;

        let btn = $(this);
        let url = btn.data("url");
        let id = btn.data("id");

        $.ajax({
            url: url,
            type: "DELETE",
            data: {
                _token: $("meta[name='csrf-token']").attr("content"),
            },
            success: function (res) {
                $("#category-row-" + id).remove();
                toastr.success(res.message);
            },
            error: function () {
                toastr.error("Không thể xóa!");
            },
        });
    });

    //manage_booking
    $(document).on("change", "#booking-status", function () {
        let select = $(this);
        let url = select.data("url");
        let status = select.val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: url,
            type: "POST",
            data: { status: status },
            success: function (res) {
                toastr.success(res.message);
                window.location.href = "/admin/bookings";
            },
            error: function () {
                toastr.error("Có lỗi xảy ra khi cập nhật trạng thái!");
            },
        });
    });

    $(document).on("click", "#btn-confirm-transfer", function () {
        let button = $(this);
        let url = button.data("url");

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: url,
            type: "POST",
            success: function (res) {
                toastr.success(res.message);
                window.location.href = "/admin/bookings";
            },
            error: function () {
                toastr.error("Có lỗi xảy ra khi xác nhận chuyển khoản!");
            },
        });
    });

    $(document).on("click", ".btn-delete-booking", function () {
        let id = $(this).data("id");
        let row = $(this).closest("tr");

        if (confirm("Bạn có chắc chắn muốn xóa?")) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });

            $.ajax({
                url: `/admin/bookings/${id}`,
                type: "DELETE",
                success: function (res) {
                    toastr.success(res.message);
                    row.fadeOut(300, function () {
                        $(this).remove();
                    });
                },
                error: function () {
                    toastr.error("Có lỗi xảy ra khi xóa booking!");
                },
            });
        }
    });

    //booking_tickets
    $(document).on("change", ".ticket-status", function () {
        let id = $(this).data("id");
        let status = $(this).val();

        $.ajax({
            url: "/admin/tickets/" + id + "/update-status",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                status: status,
            },
            success: function (res) {
                toastr.success(res.message);
            },
            error: function () {
                toastr.error("Không thể cập nhật trạng thái vé");
            },
        });
    });

    $(document).on("click", ".btn-view-ticket", function () {
        let id = $(this).data("id");

        $.get("/admin/tickets/" + id, function (res) {
            if (!res.success) {
                toastr.error(res.message);
                return;
            }

            let t = res.data;

            let passenger = null;
            if (t.booking && t.booking.passengers) {
                passenger = t.booking.passengers.find(
                    (p) => p.seat_number === t.seat_number
                );
            }

            let paymentMethod =
                t.booking?.payment_method?.name ?? "Chưa thanh toán";

            const ticketStatusText = {
                unused: "Chưa sử dụng",
                used: "Đã sử dụng",
                expired: "Hết hạn",
                cancelled: "Đã hủy",
            };

            $("#d-ticket-code").text(t.ticket_code);
            $("#d-seat").text(t.seat_number ?? "-");
            $("#d-time").text(t.valid_from + " → " + t.valid_to);
            $("#d-status").text(ticketStatusText[t.status] ?? t.status);
            $("#d-payment-method").text(paymentMethod);

            $("#d-passenger-name").text(passenger?.passenger_name ?? "-");
            $("#d-passenger-phone").text(passenger?.passenger_phone ?? "-");
            $("#d-passenger-email").text(passenger?.passenger_email ?? "-");

            $("#ticketDetailModal").modal("show");
        });
    });

    $(document).on("click", ".btn-delete-ticket", function () {
        if (!confirm("Bạn có chắc chắn muốn xóa vé này?")) return;

        let id = $(this).data("id");
        let url = $(this).data("url");

        $.ajax({
            url: url,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                $("#ticket-row-" + id).remove();
                toastr.success(res.message);
            },
            error: function (xhr) {
                toastr.error(xhr.responseJSON?.message ?? "Xóa vé thất bại");
            },
        });
    });

    //manage_schedule
    $(document).on("submit", ".update-schedule-form", function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.data("url");
        let id = form.data("id");

        let formData = new FormData(this);

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.success) {
                    $("#modalUpdate-" + id).modal("hide");

                    let row = $("#schedule-row-" + id);
                    row.find("td:nth-child(2)").text(
                        formData.get("departure_datetime").replace("T", " ")
                    );
                    row.find("td:nth-child(4)").text(
                        new Intl.NumberFormat("vi-VN").format(
                            formData.get("base_fare")
                        ) + " VNĐ"
                    );

                    toastr.success(res.message);
                }
            },
            error: function (xhr) {
                let msg = "Lỗi server";
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join("\n");
                }
                alert(msg);
            },
        });
    });

    $("[id^=update-schedule-template-]").on("submit", function (e) {
        e.preventDefault();

        let form = $(this);
        let id = form.attr("id").replace("update-schedule-template-", "");
        let url = "/admin/schedule-templates/" + id;

        let formData = form.serialize();
        console.log(formData);
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                if (!res.success) {
                    alert("Có lỗi xảy ra!");
                    return;
                }

                let row = $("#template-row-" + id);

                row.find("td:eq(3)").text(res.data.departure_time);
                row.find("td:eq(4)").text(res.data.travel_duration_minutes);
                row.find("td:eq(5)").text(res.data.running_days.join(","));
                row.find("td:eq(6)").text(
                    Number(res.data.base_fare).toLocaleString("vi-VN", {
                        minimumFractionDigits: 0,
                    }) + " VNĐ"
                );
                row.find("td:eq(7)").text(res.data.default_seats);

                $("#modalUpdate-" + id).modal("hide");

                toastr.success("Cập nhật thành công!");
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                alert("Lỗi server khi update.");
            },
        });
    });

    $("#add-schedule-template").submit(function (e) {
        e.preventDefault();

        let form = $("#add-schedule-template");
        let storeUrl = form.data("store-url");
        let formData = form.serialize();

        $.ajax({
            url: storeUrl,
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                toastr.success(response.message);
                form.trigger("reset");
                $("#form-result").html("");
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors || {};
                let html = '<div class="alert alert-danger"><ul>';
                $.each(errors, function (key, value) {
                    html += "<li>" + value[0] + "</li>";
                });
                html += "</ul></div>";
                $("#form-result").html(html);
            },
        });
    });

    $(document).on("click", ".btn-delete-schedule-template", function (e) {
        e.preventDefault();

        if (!confirm("Bạn có chắc chắn muốn xóa chuyến xe này?")) return;

        let button = $(this);
        let deleteUrl = button.data("delete-url");
        let rowId = "#template-row-" + button.data("id");

        $.ajax({
            url: deleteUrl,
            type: "DELETE",
            data: {
                _token: $("meta[name='csrf-token']").attr("content"),
            },
            success: function (response) {
                $(rowId).remove();
                toastr.success(response.message);
            },
            error: function (xhr) {
                alert("Có lỗi xảy ra, vui lòng thử lại.");
            },
        });
    });

    //manage_routes
    $(document).on("submit", "[id^=update-route-]", function (e) {
        e.preventDefault();

        let form = $(this);
        let id = form.attr("id").replace("update-route-", "");
        let url = "/admin/routes/update/" + id;

        $.ajax({
            url: url,
            type: "POST",
            data: form.serialize(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                if (!res.success) {
                    alert("Có lỗi xảy ra!");
                    return;
                }

                let row = $("#route-row-" + id);
                row.find("td:eq(1)").text(res.data.origin_name);
                row.find("td:eq(2)").text(res.data.destination_name);
                row.find("td:eq(3)").text(res.data.operator_name);
                row.find("td:eq(4)").text(res.data.distance);
                row.find("td:eq(5)").text(res.data.description);

                $("#modalUpdate-" + id).modal("hide");
                toastr.success("Cập nhật thành công!");
            },
            error: function (xhr) {
                alert("Lỗi server khi update.");
            },
        });
    });

    $(document).on("click", ".btn-delete-route", function (e) {
        e.preventDefault();

        let btn = $(this);
        let id = btn.data("id");

        if (!confirm("Bạn có chắc chắn muốn xóa tuyến đường này?")) return;

        $.ajax({
            url: "/admin/routes/delete/" + id,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                if (res.success) {
                    $("#route-row-" + id).remove();
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message || "Có lỗi xảy ra khi xóa!");
                }
            },
            error: function (xhr) {
                toastr.error("Lỗi server khi xóa tuyến đường.");
            },
        });
    });

    $("#add-route-form").submit(function (e) {
        e.preventDefault();

        let form = $(this);
        let storeUrl = form.data("store-url");
        let formData = form.serialize();

        $.ajax({
            url: storeUrl,
            type: "POST",
            data: formData,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                toastr.success(response.message);
                form.trigger("reset");
                $("#form-result").html("");
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors || {};
                let html = '<div class="alert alert-danger"><ul>';
                $.each(errors, function (key, value) {
                    html += "<li>" + value[0] + "</li>";
                });
                html += "</ul></div>";
                $("#form-result").html(html);
            },
        });
    });

    //manage_operator
    $(document).on("submit", "[id^=update-operator-]", function (e) {
        e.preventDefault();

        let form = $(this);
        let id = form.attr("id").replace("update-operator-", "");
        let url = "/admin/operators/update/" + id;

        let formData = new FormData(form[0]);

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                if (!res.success) {
                    alert("Có lỗi xảy ra!");
                    return;
                }

                let row = $("#operator-row-" + id);
                row.find("td:eq(0)").text(res.data.name);
                row.find("td:eq(1)").text(res.data.description);
                row.find("td:eq(2)").text(res.data.rating);
                row.find("td:eq(3)").text(res.data.contact_info);

                $("#updateOperator-" + id).modal("hide");
                toastr.success("Cập nhật thành công!");
            },
            error: function () {
                alert("Lỗi server khi update.");
            },
        });
    });

    $(document).on("click", ".btn-delete-operator", function (e) {
        e.preventDefault();

        if (!confirm("Bạn có chắc chắn muốn xóa nhà xe này?")) return;

        let id = $(this).data("id");
        let url = $(this).data("url");

        $.ajax({
            url: url,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                if (res.success) {
                    $("#operator-row-" + id).remove();
                    toastr.success(res.message);
                }
            },
            error: function () {
                toastr.error("Lỗi server khi xóa nhà xe.");
            },
        });
    });

    $("#add-operator-form").submit(function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.data("store-url");
        let formData = new FormData(this);

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                toastr.success(response.message);
                form.trigger("reset");
                $("#form-result").html("");
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors || {};
                let html = '<div class="alert alert-danger"><ul>';
                $.each(errors, function (k, v) {
                    html += "<li>" + v[0] + "</li>";
                });
                html += "</ul></div>";
                $("#form-result").html(html);
            },
        });
    });

    //manage_location
    $(document).on("submit", "[id^=update-location-]", function (e) {
        e.preventDefault();

        let form = $(this);
        let id = form.attr("id").replace("update-location-", "");
        let url = "/admin/locations/update/" + id;

        let formData = new FormData(form[0]);

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                if (!res.success) {
                    alert("Có lỗi xảy ra!");
                    return;
                }

                let row = $("#location-row-" + id);
                row.find("td:eq(0)").text(res.data.name);
                row.find("td:eq(1)").text(res.data.city);
                row.find("td:eq(2)").text(res.data.address);
                row.find("td:eq(3)").text(res.data.province);

                if (res.data.image_url) {
                    row.find("td:eq(4)").html(
                        `<img src="${res.data.image_url}" width="60">`
                    );
                }

                $("#modalUpdate-" + id).modal("hide");
                toastr.success("Cập nhật thành công!");
            },
            error: function () {
                alert("Lỗi server khi update.");
            },
        });
    });

    $(document).on("click", ".btn-delete-location", function (e) {
        e.preventDefault();

        let btn = $(this);
        let id = btn.data("id");

        if (!confirm("Bạn có chắc chắn muốn xóa điểm này?")) return;

        $.ajax({
            url: "/admin/locations/delete/" + id,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                if (res.success) {
                    $("#location-row-" + id).remove();
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message || "Có lỗi xảy ra khi xóa!");
                }
            },
            error: function () {
                toastr.error("Lỗi server khi xóa điểm.");
            },
        });
    });

    $("#add-location-form").submit(function (e) {
        e.preventDefault();

        let form = $(this);
        let storeUrl = form.data("store-url");
        let formData = new FormData(form[0]);

        $.ajax({
            url: storeUrl,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                toastr.success(response.message);
                form.trigger("reset");
                $("#form-result").html("");
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors || {};
                let html = '<div class="alert alert-danger"><ul>';

                $.each(errors, function (key, value) {
                    html += "<li>" + value[0] + "</li>";
                });

                html += "</ul></div>";
                $("#form-result").html(html);
            },
        });
    });

    //manage_vehicle
    $(document).on("submit", "[id^=update-vehicleType-]", function (e) {
        e.preventDefault();
        let form = $(this);
        let id = form.attr("id").replace("update-vehicleType-", "");
        let url = "/admin/vehicle-types/update/" + id;

        $.ajax({
            url: url,
            type: "POST",
            data: form.serialize(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                if (!res.success) {
                    alert("Có lỗi xảy ra!");
                    return;
                }
                let row = $("#vehicleType-row-" + id);
                row.find("td:eq(0)").text(res.data.name);
                row.find("td:eq(1)").text(res.data.capacity_total);
                row.find("td:eq(2)").text(res.data.number_of_floors);
                row.find("td:eq(3)").text(res.data.description);
                $("#modalUpdate-" + id).modal("hide");
                toastr.success("Cập nhật thành công!");
            },
            error: function (xhr) {
                alert("Lỗi server khi update.");
            },
        });
    });

    $(document).on("click", ".btn-delete-vehicleType", function (e) {
        e.preventDefault();
        let btn = $(this);
        let id = btn.data("id");
        if (!confirm("Bạn có chắc chắn muốn xóa loại xe này?")) return;

        $.ajax({
            url: "/admin/vehicle-types/delete/" + id,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                if (res.success) {
                    $("#vehicleType-row-" + id).remove();
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message || "Có lỗi xảy ra khi xóa!");
                }
            },
            error: function (xhr) {
                toastr.error("Lỗi server khi xóa loại xe.");
            },
        });
    });

    $("#add-vehicleType-form").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let storeUrl = form.data("store-url");
        $.ajax({
            url: storeUrl,
            type: "POST",
            data: form.serialize(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                toastr.success(res.message);
                form.trigger("reset");
                $("#form-result").html("");
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors || {};
                let html = '<div class="alert alert-danger"><ul>';
                $.each(errors, function (key, value) {
                    html += "<li>" + value[0] + "</li>";
                });
                html += "</ul></div>";
                $("#form-result").html(html);
            },
        });
    });

    //contact//
    if ($("#editor-contact").length) {
        CKEDITOR.replace("editor-contact");
    }

    $(document).on("click", ".contact-item", function (e) {
        let contactName = $(this).data("name");
        let contactEmail = $(this).data("email");
        let contactMessage = $(this).data("message");
        let contactId = $(this).data("id");
        let isReplied = $(this).attr("data-is_replied");

        $(".mail_view .inbox-body .sender-info strong").text(contactName);
        $(".mail_view .inbox-body .sender-info span").text(
            "(" + contactEmail + ")"
        );
        $(".mail_view .view-mail p").text(contactMessage);

        $(".mail_view").show();

        if (isReplied != 0) {
            $("#compose").hide();
        } else {
            $(".send-reply-contact").attr("data-email", contactEmail);
            $(".send-reply-contact").attr("data-id", contactId);
            $("#compose").show();
        }
    });

    $(document).on("click", ".send-reply-contact", function (e) {
        e.preventDefault();
        let button = $(this);
        let email = button.data("email");
        let contactId = button.data("id");
        let message = CKEDITOR.instances["editor-contact"].getData();

        if (message.trim() === "") {
            toastr.error("Nội dung phản hồi không được để trống.");
            return;
        }

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        button.prop("disabled", true).text("Đang gửi...");

        $.ajax({
            url: "contact/reply",
            type: "POST",
            data: {
                email: email,
                message: message,
                contact_id: contactId,
            },
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    $(".mail_view").hide();
                    $("#compose").hide();
                    CKEDITOR.instances["editor-contact"].setData("");
                    $("#editor-contact").empty();

                    let contactItem = $(
                        '.contact-item[data-id="' + contactId + '"]'
                    );
                    contactItem.attr("data-is_replied", 1);
                    contactItem.find("i.fa-circle").css("color", "green");

                    $(".compose").slideToggle();
                    button
                        .removeAttr("data-email")
                        .removeAttr("data-contactId");
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr) {
                alert(xhr.responseJSON.error);
            },
            complete: function () {
                button.prop("disabled", false);
                button.text("Gửi");
            },
        });
    });
});
