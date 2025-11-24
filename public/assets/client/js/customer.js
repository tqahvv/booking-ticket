$(document).ready(function () {
    // ================================
    // ĐĂNG KÝ & ĐĂNG NHẬP
    // ================================
    $("#registerForm").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: "/register",
            method: "POST",
            data: $(this).serialize(),
            success: function (res) {
                Swal.fire({
                    icon: "success",
                    title: "Đăng ký thành công",
                    text: "Bạn có thể đăng nhập ngay bây giờ!",
                }).then(() => (window.location.href = "/login"));
            },
            error: function (xhr) {
                $(".text-danger").text("");
                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        $(".error-" + key).text(value[0]);
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi hệ thống",
                        text: "Có lỗi xảy ra, vui lòng thử lại sau!",
                    });
                }
            },
        });
    });

    $("#loginForm").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: "/login",
            method: "POST",
            data: $(this).serialize(),
            success: function (res) {
                Swal.fire({
                    icon: "success",
                    title: res.message || "Đăng nhập thành công",
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => (window.location.href = "/"));
            },
            error: function (xhr) {
                $(".error-email, .error-password").text("");
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    if (xhr.responseJSON.errors.email)
                        $(".error-email").text(
                            xhr.responseJSON.errors.email[0]
                        );
                    if (xhr.responseJSON.errors.password)
                        $(".error-password").text(
                            xhr.responseJSON.errors.password[0]
                        );
                }
            },
        });
    });

    // ================================
    // LOGOUT
    // ================================
    $("#logout-link").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: "Bạn có chắc chắn muốn đăng xuất?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Có, đăng xuất",
            cancelButtonText: "Không",
        }).then((result) => {
            if (result.isConfirmed) $("#logout-form").submit();
        });
    });

    // ================================
    // CẬP NHẬT PROFILE & AVATAR
    // ================================
    $("#avatar").on("change", function () {
        const [file] = this.files;
        if (file) $("#avatar-preview").attr("src", URL.createObjectURL(file));
    });

    $("#profile-form").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "/account/update",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(".button-save").text("Đang lưu...").prop("disabled", true);
            },
            success: function (res) {
                Swal.fire({
                    icon: "success",
                    title: res.message || "Cập nhật thành công!",
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => location.reload());
            },
            error: function (xhr) {
                var msg =
                    xhr.responseJSON?.message ||
                    "Có lỗi xảy ra, vui lòng thử lại!";
                Swal.fire({
                    icon: "error",
                    title: "Thất bại",
                    text: msg,
                    confirmButtonText: "Đóng",
                });
            },
            complete: function () {
                $(".button-save").text("Lưu").prop("disabled", false);
            },
        });
    });

    // ================================
    // TÌM KIẾM ĐỊA ĐIỂM
    // ================================
    function escapeHtml(str) {
        return String(str || "").replace(/[&<>"'`=\/]/g, function (s) {
            return {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#39;",
                "/": "&#x2F;",
                "`": "&#x60;",
                "=": "&#x3D;",
            }[s];
        });
    }

    function renderSuggestions(data, targetDiv) {
        let html = '<div class="list-group">';
        if (Array.isArray(data) && data.length > 0) {
            $.each(data, function (i, loc) {
                let city = loc.city || "";
                let province = loc.province || "";
                html += `<div class="list-group-item location-item" data-name="${escapeHtml(
                    city
                )}">
                    <div style="font-weight:600">${escapeHtml(city)}</div>
                    <div class="muted" style="margin-top:4px; font-size:13px">Tất cả các điểm lên xe ở ${escapeHtml(
                        city
                    )}</div>
                </div>`;
            });
        } else {
            html += '<div class="list-group-item muted">Không có kết quả</div>';
        }
        html += "</div>";
        $(targetDiv).html(html).show();
    }

    function fetchSuggestions(query, targetDiv) {
        $.ajax({
            url: "/locations/search",
            method: "GET",
            data: { q: query },
            success: function (data) {
                renderSuggestions(data, targetDiv);
            },
            error: function (err) {
                console.error("Lỗi khi lấy gợi ý:", err);
            },
        });
    }

    $("#fromCity").on("focus input", function () {
        fetchSuggestions($(this).val().trim(), "#fromSuggestions");
        $("#toSuggestions").hide();
    });
    $("#toCity").on("focus input", function () {
        fetchSuggestions($(this).val().trim(), "#toSuggestions");
        $("#fromSuggestions").hide();
    });

    $("#fromCity, #toCity").on("blur", function () {
        let target =
            $(this).attr("id") === "fromCity"
                ? "#fromSuggestions"
                : "#toSuggestions";
        setTimeout(() => {
            if (!$(":focus").closest(target).length) $(target).hide();
        }, 200);
    });

    $(document).on("click", ".location-item", function () {
        let name = $(this).data("name");
        let parent = $(this).closest(".suggestion-box").attr("id");
        if (parent === "fromSuggestions") {
            $("#fromCity").val(name).data("location-id", $(this).data("id"));
            $("#fromSuggestions").hide();
        } else {
            $("#toCity").val(name).data("location-id", $(this).data("id"));
            $("#toSuggestions").hide();
        }
    });

    $(document).on("click", function (e) {
        if (!$(e.target).closest(".search-input-wrap").length) {
            $("#fromSuggestions, #toSuggestions").hide();
        }
    });

    $("#swapBtn").on("click", function () {
        let f = $("#fromCity").val(),
            t = $("#toCity").val();
        $("#fromCity").val(t);
        $("#toCity").val(f);
    });

    // ================================
    // TRIP CARD TOGGLE
    // ================================
    $(".trip-card").each(function () {
        var card = $(this);
        var featureBtn = card.find(".footer-btn.featured");
        var routeBtn = card.find(".route-btn");
        var extraBox = card.find(".trip-extra");
        var featureContent = card.find(".features-content");
        var routeContent = card.find(".route-content");

        featureBtn.on("click", function () {
            extraBox.show();
            featureContent.show();
            routeContent.hide();
            featureBtn.addClass("active");
            routeBtn.removeClass("active");
        });
        routeBtn.on("click", function () {
            extraBox.show();
            featureContent.hide();
            routeContent.show();
            routeBtn.addClass("active");
            featureBtn.removeClass("active");
        });
    });

    // ================================
    // PICKUP / DROPOFF & SUMMARY
    // ================================
    function updateSummary() {
        let pickup =
            $('input[name="pickup_id"]:checked')
                .closest(".location-item-updated")
                .data("location-name") || "";
        let dropoff =
            $('input[name="dropoff_id"]:checked')
                .closest(".location-item-updated")
                .data("location-name") || "";
        $("#summary-pickup-name").text(pickup);
        $("#summary-dropoff-name").text(dropoff);
    }

    if ($('input[name="pickup_id"]').length) {
        updateSummary();
    }

    $(document).on(
        "change",
        'input[name="pickup_id"], input[name="dropoff_id"]',
        updateSummary
    );

    $("#btn-choose-seat").on("click", function () {
        let schedule_id = $(this).data("schedule");
        let seatsNeeded = $(this).data("seats");
        let pickup = $('input[name="pickup_id"]:checked').val();
        let dropoff = $('input[name="dropoff_id"]:checked').val();
        if (!pickup || !dropoff) {
            alert("Vui lòng chọn điểm đón và điểm trả.");
            return;
        }

        $.ajax({
            url: "/booking/check-pickup",
            type: "GET",
            data: {
                schedule_id,
                pickup_id: pickup,
                dropoff_id: dropoff,
                seats: seatsNeeded,
            },
            success: function (res) {
                if (res.status === "ok")
                    window.location.href = `/booking/seat?schedule_id=${schedule_id}&pickup_id=${pickup}&dropoff_id=${dropoff}&seats=${seatsNeeded}`;
                else alert(res.message);
            },
            error: function () {
                alert("Có lỗi xảy ra khi kiểm tra dữ liệu.");
            },
        });
    });

    // ================================
    // SEAT SELECTION & SUMMARY UPDATE
    // ================================
    const selectableSeats = $(".seat-only.available, .bed-item.available");
    const $summarySeats = $("#summary-selected-seats");
    const $summaryTotal = $("#summary-total-fare");
    const $btnContinue = $("#btn-continue-booking");

    const basePrice = parseFloat($summaryTotal.data("base-price") || 0);
    const maxSeats = parseInt($summaryTotal.data("max-seats") || 1);

    function formatCurrency(amount) {
        return new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        }).format(amount);
    }

    function updateSeatSummary() {
        const selectedSeats = $(".seat-only.selected, .bed-item.selected")
            .map(function () {
                return $(this).data("seat");
            })
            .get();
        const count = selectedSeats.length;
        const totalFare = count * basePrice;
        $btnContinue.prop("disabled", count === 0);

        if (count > 0) {
            $summarySeats
                .text(selectedSeats.join(","))
                .removeClass("text-danger")
                .addClass("text-primary");
            $summaryTotal.text(formatCurrency(totalFare));
        } else {
            $summarySeats
                .text("Chưa chọn")
                .removeClass("text-primary")
                .addClass("text-danger");
            $summaryTotal.text(formatCurrency(0));
        }
    }

    selectableSeats.on("click", function () {
        const selectedCount = $(
            ".seat-only.selected, .bed-item.selected"
        ).length;
        if (!$(this).hasClass("selected") && selectedCount >= maxSeats) return;

        $(this).toggleClass("selected");
        updateSeatSummary();

        const scheduleId = $('input[name="schedule_id"]').val();
        if (!scheduleId) return;
        const selectedSeats = $(".seat-only.selected, .bed-item.selected")
            .map(function () {
                return $(this).data("seat");
            })
            .get();
        $.ajax({
            url: "/booking/update-selected-seats",
            type: "POST",
            data: JSON.stringify({
                schedule_id: scheduleId,
                selected_seats: selectedSeats,
            }),
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                console.log("Seats updated", res);
            },
            error: function (err) {
                console.error(err);
            },
        });
    });

    updateSeatSummary();

    $("#btn-continue-booking").on("click", function () {
        const selectedSeats = $(".seat-only.selected, .bed-item.selected")
            .map(function () {
                return $(this).data("seat");
            })
            .get();

        if (selectedSeats.length === 0) {
            alert("Vui lòng chọn ghế trước khi tiếp tục!");
            return;
        }

        const scheduleId = $("#schedule_id").val();
        const pickupId = $("#pickup_id").val();
        const dropoffId = $("#dropoff_id").val();

        let totalPriceText = $("#summary-total-fare").text() || "";
        const totalPrice = totalPriceText.replace(/[^\d]/g, "") || 0;

        if (!scheduleId || !pickupId || !dropoffId) {
            alert("Vui lòng chọn đầy đủ điểm đón và điểm trả!");
            return;
        }

        $.ajax({
            url: "/booking/cache-seat-selection",
            type: "POST",
            data: {
                schedule_id: scheduleId,
                pickup_id: pickupId,
                dropoff_id: dropoffId,
                selected_seats: selectedSeats.join(","),
                seats: selectedSeats.length,
                total_price: totalPrice,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                if (res.status === "ok") {
                    window.location.href = "/booking/customer-info";
                } else {
                    alert(res.message || "Có lỗi khi lưu thông tin.");
                }
            },
            error: function (xhr) {
                console.error("cache-seat-selection error:", xhr);
                alert("Server lỗi 419 – kiểm tra CSRF token.");
            },
        });
    });

    $("#customer-form").on("submit", function (e) {
        var name = $("#passenger_name").val();
        var phone = $("#passenger_phone").val();
        var email = $("#passenger_email").val();

        if (!name || !phone || !email) {
            alert("Vui lòng điền đầy đủ thông tin khách hàng");
            e.preventDefault();
        }
    });

    $("#btn-continue-payment").on("click", function () {
        let isLoggedIn = $("body").data("logged-in") == 1;

        if (!isLoggedIn) {
            let name = $("#passenger_name").val().trim();
            let phone = $("#passenger_phone").val().trim();
            let email = $("#passenger_email").val().trim();

            if (!name || !phone || !email) {
                alert("Vui lòng điền đầy đủ thông tin khách hàng");
                return;
            }
        }
        $("#customer-form").submit();
    });
});
