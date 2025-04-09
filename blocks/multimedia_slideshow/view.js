(function ($) {
    $(function () {
        let $slideshowContainer = $(".multimedia-slideshow").addClass("video-slideshow");
        let timerProc = null;

        let displayNextSlide = function () {
            let $active = $slideshowContainer.find('.slide.active');

            if ($active.length === 0) {
                $active = $slideshowContainer.find('.slide:first');
            }

            let $next = ($active.next().length > 0) ? $active.next() : $slideshowContainer.find('.slide:first');

            $next.css('z-index', 2);

            $active.fadeOut($slideshowContainer.data("speed"), function () {
                $active.css('z-index', 1).show().removeClass('active');
                $next.css('z-index', 3).addClass('active');

                if ($next.find("video").length && $next.find("video").get(0).tagName === "VIDEO") {
                    $next.find("video").get(0).currentTime = 0;
                    $next.find("video").get(0).play();
                    $next.find("video").get(0).addEventListener('ended', function () {
                        displayNextSlide();
                    }, false);
                } else {
                    if (timerProc !== null) {
                        clearTimeout(timerProc)
                    }

                    timerProc = setTimeout(displayNextSlide, $slideshowContainer.data("timeout"));
                }
            });
        };

        let $firstSlide = $slideshowContainer.find('.slide:first');

        $firstSlide.addClass('active').css('z-index', 3);

        setTimeout(function () {
            displayNextSlide();
        }, $slideshowContainer.data("timeout"));
    });
})(jQuery);
