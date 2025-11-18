$(document).ready(function () {
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
                }).then(() => {
                    window.location.href = "/login";
                });
            },
            error: function (xhr) {
                $(".text-danger").text("");
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
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
                }).then(() => {
                    window.location.href = "/";
                });
            },
            error: function (xhr) {
                $(".error-email").text("");
                $(".error-password").text("");

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    if (errors.email) {
                        $(".error-email").text(errors.email[0]);
                    }
                    if (errors.password) {
                        $(".error-password").text(errors.password[0]);
                    }
                }
            },
        });
    });

    const logoutLink = document.getElementById("logout-link");
    if (logoutLink) {
        logoutLink.addEventListener("click", function (e) {
            e.preventDefault();
            Swal.fire({
                title: "Bạn có chắc chắn muốn đăng xuất?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Có, đăng xuất",
                cancelButtonText: "Không",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("logout-form").submit();
                }
            });
        });
    }

    // Câp nhật thông tin tài khoản
    $("#avatar").on("change", function () {
        const [file] = this.files;
        if (file) $("#avatar-preview").attr("src", URL.createObjectURL(file));
    });

    $("#profile-form").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: "/account/update",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(".button-save").text("Đang lưu...").prop("disabled", true);
            },
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: response.message || "Cập nhật thành công!",
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function (xhr) {
                let msg = "Có lỗi xảy ra, vui lòng thử lại!";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }

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

    // =======================================================
    // CHỨC NĂNG TÌM KIẾM XE KHÁCH
    // =======================================================
    function renderSuggestions(data, targetDiv) {
        if (!Array.isArray(data)) data = [];
        if (data.length === 0) {
            $(targetDiv)
                .html(
                    '<div class="list-group"><div class="list-group-item muted">Không có kết quả</div></div>'
                )
                .show();
            return;
        }

        let html = '<div class="list-group">';
        data.forEach((loc) => {
            let city = loc.city || "";
            let province = loc.province || "";
            let subtitle = `Tất cả các điểm lên xe ở ${city}`;

            html += `<div class="list-group-item location-item" 
                    data-name="${escapeHtml(city)}">
                    <div style="font-weight:600">${escapeHtml(city)}</div>
                    <div class="muted" style="margin-top:4px; font-size:13px">
                        ${escapeHtml(subtitle)}
                    </div>
                 </div>`;
        });
        html += "</div>";
        $(targetDiv).html(html).show();
    }

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

    $("#fromCity").on("focus", function () {
        $("#toSuggestions").hide();
        fetchSuggestions("", "#fromSuggestions");
    });

    $("#toCity").on("focus", function () {
        $("#toSuggestions").hide();
        fetchSuggestions("", "#toSuggestions");
    });

    $("#fromCity").on("input", function () {
        let q = $(this).val().trim();
        $("#toSuggestions").hide();
        fetchSuggestions(q, "#fromSuggestions");
    });

    $("#toCity").on("input", function () {
        let q = $(this).val().trim();
        $("#toSuggestions").hide();
        fetchSuggestions(q, "#toSuggestions");
    });

    $("#fromCity").on("blur", function () {
        setTimeout(() => {
            if (!$(document.activeElement).closest("#fromSuggestions").length) {
                $("#fromSuggestions").hide();
            }
        }, 200);
    });

    $("#toCity").on("blur", function () {
        setTimeout(() => {
            if (!$(document.activeElement).closest("#toSuggestions").length) {
                $("#toSuggestions").hide();
            }
        }, 200);
    });

    $(document).on("click", ".location-item", function (e) {
        let name = $(this).data("name");
        let id = $(this).data("id");
        let parent = $(this).closest(".suggestion-box").attr("id");
        if (parent === "fromSuggestions") {
            $("#fromCity").val(name).data("location-id", id);
            $("#fromSuggestions").hide();
        } else {
            $("#toCity").val(name).data("location-id", id);
            $("#toSuggestions").hide();
        }
    });

    $(document).on("click", function (e) {
        if (!$(e.target).closest(".search-input-wrap").length) {
            $("#fromSuggestions").hide();
            $("#toSuggestions").hide();
        }
    });

    $("form#searchForm").attr("autocomplete", "off");

    $("#swapBtn").on("click", function () {
        let f = $("#fromCity").val();
        let t = $("#toCity").val();
        $("#fromCity").val(t);
        $("#toCity").val(f);
    });
});
