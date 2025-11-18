$(document).ready(function () {
    "use strict";

    // ======= Khởi tạo kích thước màn hình ======= //
    const window_width = $(window).width(),
        window_height = window.innerHeight,
        header_height = $(".default-header").height(),
        header_height_static = $(".site-header.static").outerHeight(),
        fitscreen = window_height - header_height;

    $(".fitscreen").css("height", fitscreen);

    // ======= Datepicker ======= //
    if ($.fn.datepicker) {
        $(".date-picker").datepicker();
    }

    // ======= Nice Select ======= //
    if ($("#default-select, #default-select2, #service-select").length) {
        $("select").niceSelect();
    }

    // ======= Magnific Popup (lightbox) ======= //
    if ($.fn.magnificPopup) {
        $(".img-gal").magnificPopup({
            type: "image",
            gallery: { enabled: true },
        });

        $(".play-btn").magnificPopup({
            type: "iframe",
            mainClass: "mfp-fade",
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false,
        });
    }

    // ======= Superfish Menu ======= //
    if ($.fn.superfish) {
        $(".nav-menu").superfish({
            animation: { opacity: "show" },
            speed: 400,
        });
    }

    // ======= Owl Carousel (bài viết) ======= //
    const $carousel = $(".active-recent-blog-carusel");
    if ($carousel.length && $.fn.owlCarousel) {
        $carousel.addClass("owl-carousel").owlCarousel({
            items: 3,
            loop: true,
            margin: 30,
            dots: true,
            autoplayHoverPause: true,
            smartSpeed: 500,
            autoplay: true,
            responsive: {
                0: { items: 1 },
                480: { items: 1 },
                768: { items: 2 },
                961: { items: 3 },
            },
        });
    }

    // ======= Mobile Nav ======= //
    if ($("#nav-menu-container").length) {
        const $mobile_nav = $("#nav-menu-container")
            .clone()
            .prop({ id: "mobile-nav" });
        $mobile_nav.find("> ul").attr({ class: "", id: "" });
        $("body .main-menu").append($mobile_nav);
        $("body .main-menu").prepend(
            '<button type="button" id="mobile-nav-toggle"><i class="lnr lnr-menu"></i></button>'
        );
        $("body .main-menu").append('<div id="mobile-body-overly"></div>');
        $("#mobile-nav")
            .find(".menu-has-children")
            .prepend('<i class="lnr lnr-chevron-down"></i>');

        $(document).on("click", ".menu-has-children i", function () {
            $(this).next().toggleClass("menu-item-active");
            $(this).nextAll("ul").eq(0).slideToggle();
            $(this).toggleClass("lnr-chevron-up lnr-chevron-down");
        });

        $(document).on("click", "#mobile-nav-toggle", function () {
            $("body").toggleClass("mobile-nav-active");
            $("#mobile-nav-toggle i").toggleClass("lnr-cross lnr-menu");
            $("#mobile-body-overly").toggle();
        });

        $(document).on("click", function (e) {
            const container = $("#mobile-nav, #mobile-nav-toggle");
            if (
                !container.is(e.target) &&
                container.has(e.target).length === 0 &&
                $("body").hasClass("mobile-nav-active")
            ) {
                $("body").removeClass("mobile-nav-active");
                $("#mobile-nav-toggle i").toggleClass("lnr-cross lnr-menu");
                $("#mobile-body-overly").fadeOut();
            }
        });
    } else if ($("#mobile-nav, #mobile-nav-toggle").length) {
        $("#mobile-nav, #mobile-nav-toggle").hide();
    }

    // ======= Smooth Scroll ======= //
    $(".nav-menu a, #mobile-nav a, .scrollto").on("click", function (e) {
        if (
            location.pathname.replace(/^\//, "") ==
                this.pathname.replace(/^\//, "") &&
            location.hostname == this.hostname
        ) {
            const target = $(this.hash);
            if (target.length) {
                e.preventDefault();
                let top_space = 0;

                if ($("#header").length) {
                    top_space = $("#header").outerHeight();
                    if (!$("#header").hasClass("header-fixed")) {
                        top_space = top_space;
                    }
                }

                $("html, body").animate(
                    { scrollTop: target.offset().top - top_space },
                    1500,
                    "easeInOutExpo"
                );

                if ($(this).parents(".nav-menu").length) {
                    $(".nav-menu .menu-active").removeClass("menu-active");
                    $(this).closest("li").addClass("menu-active");
                }

                if ($("body").hasClass("mobile-nav-active")) {
                    $("body").removeClass("mobile-nav-active");
                    $("#mobile-nav-toggle i").toggleClass("lnr-times lnr-bars");
                    $("#mobile-body-overly").fadeOut();
                }
            }
        }
    });

    // ======= Scroll animation nếu có hash ======= //
    if (window.location.hash) {
        $("html, body").hide();
        setTimeout(function () {
            $("html, body").scrollTop(0).show();
            $("html, body").animate(
                { scrollTop: $(window.location.hash).offset().top - 108 },
                1000
            );
        }, 0);
    }

    // ======= Active menu link ======= //
    const path = window.location.pathname.split("/").pop() || "index.html";
    const target = $('nav a[href="' + path + '"]');
    target.addClass("menu-active");

    if ($(".menu-has-children ul>li a").hasClass("menu-active")) {
        $(".menu-active")
            .closest("ul")
            .parentsUntil("a")
            .addClass("parent-active");
    }

    // ======= Header scroll ======= //
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $("#header").addClass("header-scrolled");
        } else {
            $("#header").removeClass("header-scrolled");
        }
    });

    // ======= Mailchimp ======= //
    if ($.fn.ajaxChimp) {
        $("#mc_embed_signup").find("form").ajaxChimp();
    }

    const roundTrip = document.getElementById("roundTrip");
    if (roundTrip) {
        roundTrip.addEventListener("change", function () {
            const returnDateGroup = document.getElementById("returnDateGroup");
            if (returnDateGroup) {
                returnDateGroup.style.display = this.checked ? "block" : "none";
            }
        });
    }

    $(".menu-item").click(function () {
        $(".menu-item").removeClass("active");
        $(this).addClass("active");
    });
});
