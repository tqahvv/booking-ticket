$(document).ready(function () {
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
                alert("Có lỗi xảy ra!");
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
});
