$(document).ready(function () {
    // ================================
    // ĐĂNG KÝ & ĐĂNG NHẬP
    // ================================
    $("#registerForm").on("submit", function (e) {
        e.preventDefault();

        Swal.fire({
            title: "Đang xử lý...",
            text: "Hệ thống đang gửi email kích hoạt",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
        });

        $.ajax({
            url: "/register",
            method: "POST",
            data: $(this).serialize(),

            success: function () {
                Swal.close();

                const email = $('input[name="email"]').val();
                const expiresAt = Date.now() + 15 * 60 * 1000;

                localStorage.setItem(
                    "pending_activation",
                    JSON.stringify({
                        email: email,
                        expires_at: expiresAt,
                    })
                );

                showActivationSwal(email, expiresAt);
            },

            error: function (xhr) {
                Swal.close();
                $(".text-danger").text("");

                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        $(".error-" + key).text(value[0]);
                    });
                } else if (xhr.status === 409) {
                    Swal.fire("Thông báo", xhr.responseJSON.message, "warning");
                } else {
                    Swal.fire(
                        "Lỗi",
                        "Có lỗi xảy ra, vui lòng thử lại",
                        "error"
                    );
                }
            },
        });
    });

    function showActivationSwal(email, expiresAt) {
        let timerInterval;
        let activationChecker;

        Swal.fire({
            title: "Xác nhận email",
            html: `
                <p>Chúng tôi đã gửi email kích hoạt.</p>
                <p>Vui lòng kiểm tra hộp thư.</p>
                <b id="countdown"></b>
            `,
            icon: "info",
            allowOutsideClick: false,
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: "Đóng",
            timer: expiresAt - Date.now(),

            didOpen: () => {
                const countdown =
                    Swal.getHtmlContainer().querySelector("#countdown");

                timerInterval = setInterval(() => {
                    const timeLeft = Swal.getTimerLeft();
                    if (!timeLeft || timeLeft <= 0) return;

                    const minutes = Math.floor(timeLeft / 60000);
                    const seconds = Math.floor((timeLeft % 60000) / 1000);

                    countdown.textContent = `⏳ Thời gian còn lại: ${minutes}:${seconds
                        .toString()
                        .padStart(2, "0")}`;
                }, 1000);

                activationChecker = setInterval(() => {
                    $.get("/check-activation", { email }, function (res) {
                        if (res.active) {
                            clearInterval(timerInterval);
                            clearInterval(activationChecker);
                            localStorage.removeItem("pending_activation");
                            Swal.close();
                            window.location.href = "/login?activated=1";
                        }
                    });
                }, 5000);
            },

            willClose: () => {
                clearInterval(timerInterval);
                clearInterval(activationChecker);
            },
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                localStorage.removeItem("pending_activation");

                Swal.fire({
                    icon: "warning",
                    title: "Hết thời gian kích hoạt",
                    text: "Vui lòng gửi lại email kích hoạt",
                    showCancelButton: true,
                    confirmButtonText: "Gửi lại email",
                }).then((result) => {
                    if (result.isConfirmed) {
                        resendActivation(email);
                    }
                });
            }
        });
    }

    function resendActivation(email) {
        $.post("/resend-activation", {
            email: email,
            _token: $('meta[name="csrf-token"]').attr("content"),
        }).done(() => {
            const newExpires = Date.now() + 15 * 60 * 1000;

            localStorage.setItem(
                "pending_activation",
                JSON.stringify({
                    email: email,
                    expires_at: newExpires,
                })
            );

            Swal.fire(
                "Đã gửi",
                "Email kích hoạt đã được gửi lại",
                "success"
            ).then(() => {
                showActivationSwal(email, newExpires);
            });
        });
    }

    $(document).ready(function () {
        const pending = localStorage.getItem("pending_activation");
        if (!pending) return;

        const data = JSON.parse(pending);

        if (Date.now() < data.expires_at) {
            showActivationSwal(data.email, data.expires_at);
        } else {
            localStorage.removeItem("pending_activation");
        }
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
    const ajaxUrl = $("#pageData").data("ajax-url");
    const baseDate = $("#pageData").data("date");
    const baseSeats = $("#pageData").data("seats");

    let filters = {
        pickup: "",
        dropoff: "",
        time: "",
        operator: "",
    };

    const resultsDiv = $("#tripResults");

    $(document).on("click", ".filter-toggle", function (e) {
        e.preventDefault();

        let $li = $(this).closest(".filter-item");
        let $child = $li.children(".child_menu").first();

        $li.siblings(".filter-item")
            .removeClass("open")
            .children(".child_menu")
            .slideUp(160);

        $li.toggleClass("open");
        $child.stop(true, true).slideToggle(160);
    });

    $(document).on("click", ".filter-options .filter-option", function (e) {
        e.preventDefault();

        let $this = $(this);
        let $list = $this.closest(".filter-options");

        // Toggle active
        if ($this.hasClass("active")) {
            $list.find(".filter-option").removeClass("active");
        } else {
            $list.find(".filter-option").removeClass("active");
            $this.addClass("active");
        }

        let selectedValue = $this.data("id") || "";
        let listId = $list.attr("id");

        // Gán vào filters
        switch (listId) {
            case "pickupOptions":
                filters.pickup = selectedValue;
                break;
            case "dropoffOptions":
                filters.dropoff = selectedValue;
                break;
            case "timeOptions":
                filters.time = selectedValue;
                break;
            case "operatorOptions":
                filters.operator = selectedValue;
                break;
        }

        applyFilter();
    });

    $(document).on("click", ".reset-filter-btn", function (e) {
        e.preventDefault();

        $(".filter-options .filter-option").removeClass("active");

        filters = { pickup: "", dropoff: "", time: "", operator: "" };

        applyFilter();
    });

    function applyFilter() {
        const from = $("#fromVal").val();
        const to = $("#toVal").val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: ajaxUrl,
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                from,
                to,
                date: baseDate,
                seats: baseSeats,
                ...filters,
            }),
            success: function (res) {
                resultsDiv.html("");

                if (!res.data || res.data.length === 0) {
                    resultsDiv.html(
                        `<p class="text-danger">Không có chuyến xe phù hợp</p>`
                    );
                    return;
                }

                res.data.forEach((sc) => {
                    resultsDiv.append(buildCard(sc));
                });
            },
        });
    }

    function buildCard(sc) {
        return `
        <div class="trip-card">
            <div class="trip-header">
                <div class="company-details">
                    <span class="company-name">${
                        sc.operator.name ?? "Không rõ"
                    }</span>
                    <span class="bus-type">${sc.vehicle_type.name ?? ""}</span>
                </div>
            </div>

            <div class="trip-details">
                <div class="time-route">
                    <div class="departure">
                        <span class="time">${sc.departure_time.substring(
                            0,
                            5
                        )}</span>
                        <span class="station">${sc.route.origin.name}</span>
                    </div>

                    <div class="duration-icons">
                        <i class="fa fa-arrow-right"></i>
                    </div>

                    <div class="arrival">
                        <span class="time">${sc.arrival_time.substring(
                            0,
                            5
                        )}</span>
                        <span class="station">${
                            sc.route.destination.name
                        }</span>
                    </div>
                </div>

                <div class="vertical-separator"></div>

                <div class="trip-duration-block">
                    <span class="trip-duration">${sc.duration}</span>
                </div>

                <div class="vertical-separator"></div>

                <div class="amenity-list">
                    <i class="fa fa-snowflake"></i>
                    <i class="fa fa-wifi"></i>
                    <i class="fa fa-bolt"></i>
                </div>

                <div class="price-action">
                    <div class="price-info">
                        <span class="price">${Number(
                            sc.base_fare
                        ).toLocaleString("vi-VN")} VND</span>
                        <span class="per-person">/khách</span>
                    </div>

                    <span class="available-seats">Còn ${
                        sc.seats_available
                    } vé trống</span>

                    <a href="/booking/pickup?schedule_id=${
                        sc.id
                    }&date=${baseDate}&seats=${baseSeats}"
                       class="book-btn">Đặt Ngay</a>
                </div>
            </div>
        </div>`;
    }

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

        const scheduleId =
            $('input[name="schedule_id"]').val() || $("#schedule_id").val();
        const pickupId = $("#pickup_id").val();
        const dropoffId = $("#dropoff_id").val();

        let totalPriceText = $("#summary-total-fare").text() || "0";
        const totalPrice = totalPriceText.replace(/[^\d]/g, "");

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
                selected_seats: selectedSeats,
                seats: selectedSeats.length,
                total_price: totalPrice,
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

    $("#applyPromo").on("click", function () {
        const promoCode = $("input[name='promo_select']:checked").val();
        let totalPrice = parseFloat($("#total_price").val());
        let finalPrice = parseFloat($("#final_price").val());
        let discountAmount = parseFloat($("#discount_amount").val());

        if (promoCode === "") {
            $("#promoMessage").text("Vui lòng nhập mã giảm giá.");
            return;
        }

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: "/apply-promo",
            type: "POST",
            data: {
                promo_code: promoCode,
                total_price: totalPrice,
                discount_amount: discountAmount,
                final_price: finalPrice,
            },
            success: function (res) {
                $("#voucherName").text(promoCode);
                $("#voucherDesc").text(
                    "Giảm " + res.discount.toLocaleString() + " VNĐ"
                );
                $("#voucherCard").removeClass("d-none");

                $("#discount_amount").val(res.discount);
                $("#final_price").val(res.final);

                $("#finalPriceLabel").text(res.final.toLocaleString() + " VNĐ");

                $("#discountLabel").text(
                    "-" + res.discount.toLocaleString() + " VNĐ"
                );
                $("#discountRow").removeClass("d-none");

                $("#promoMessage").text("");
            },
            error: function (xhr) {
                let msg =
                    xhr.responseJSON?.error || "Mã giảm giá không hợp lệ!";
                $("#promoMessage").text(msg);

                $("#discount_amount").val(0);
                $("#final_price").val(totalPrice);
            },
        });
    });

    $("#removeVoucher").on("click", function () {
        let totalPrice = parseFloat($("#total_price").val());

        $("#voucherCard").addClass("d-none");
        $("#promo_code").val("");

        $("#discountRow").addClass("d-none");
        $("#discountLabel").text("");

        $("#discount_amount").val(0);
        $("#final_price").val(totalPrice);

        $("#finalPriceLabel").text(totalPrice.toLocaleString() + " VNĐ");

        $("#promoMessage").text("");
    });

    //chatbot//

    $("#chat-toggle").on("click", function () {
        $("#chat-box").addClass("active");
        loadMessages();
    });

    $("#chat-close").on("click", function () {
        $("#chat-box").removeClass("active");
    });

    $("#send-btn").on("click", function () {
        let msg = $("#message-input").val().trim();
        if (!msg) return;

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.post("/chatbot/send", { message: msg }, function (res) {
            if (res.user) appendOne(res.user);
            if (res.bot) appendOne(res.bot);
            $("#message-input").val("");
        }).fail(function () {
            appendOne({
                sender: "bot",
                message: "Lỗi: không gửi được tin nhắn.",
            });
        });
    });

    $("#message-input").on("keypress", function (e) {
        if (e.which === 13) {
            $("#send-btn").click();
            return false;
        }
    });

    function loadMessages() {
        $("#chat-messages").html("");
        $.get("/chatbot/messages", function (msgs) {
            if (!msgs || msgs.length === 0) {
                $("#chat-messages").append(
                    '<div class="bot-msg">Chào bạn! Tôi có thể giúp gì cho bạn?</div>'
                );
                return;
            }

            msgs.forEach(function (m) {
                appendOne(m);
            });
            $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
        });
    }

    function appendOne(m) {
        let cls = m.sender === "user" ? "user-msg" : "bot-msg";

        $("#chat-messages").append(
            `<div class="${cls}">${escapeHtml(m.message)}</div>`
        );

        $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
    }

    function escapeHtml(text) {
        return $("<div>").text(text).html();
    }

    //contact//
    $("#contact-form").on("submit", function (e) {
        let name = $('input[name="name"]').val().trim();
        let email = $('input[name="email"]').val().trim();
        let phone = $('input[name="phone"]').val().trim();
        let message = $('textarea[name="message"]').val().trim();
        let errorMessage = "";

        if (name.length < 3) {
            errorMessage += "Họ và tên phải có ít nhất 3 ký tự.<br>";
        }

        if (phone.length < 10 || phone.length > 11 || !/^\d+$/.test(phone)) {
            errorMessage += "Số điện thoại phải từ 10–11 chữ số.<br>";
        }

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errorMessage += "Email không hợp lệ.<br>";
        }

        if (message.length < 5) {
            errorMessage += "Nội dung phải có ít nhất 5 ký tự.<br>";
        }

        if (errorMessage !== "") {
            toastr.error(errorMessage, "Lỗi");
            e.preventDefault();
        }
    });

    $(".btn-cancel-booking").on("click", function () {
        if (!confirm("Bạn có chắc chắn muốn hủy vé này?")) return;

        const bookingId = $(this).data("id");
        const email = $(this).data("email");
        const phone = $(this).data("phone");

        $.ajax({
            url: `/booking/${bookingId}/cancel`,
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                email: email,
                phone: phone,
            },
            success: function (res) {
                alert(res.message);
                location.reload();
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || "Có lỗi xảy ra");
            },
        });
    });
});
