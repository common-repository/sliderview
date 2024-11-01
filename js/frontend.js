jQuery(document).ready(function ($) {
  initSlideShow($);

  // fade gallery init
  function initSlideShow($) {
    $("div.slideshow").each((idx, item) => {
      $(item).attr("id", `slider${idx}`);
      $($(item).find(".prevBtn")[0]).attr("id", `slider${idx}-prev`);
      $($(item).find(".nextBtn")[0]).attr("id", `slider${idx}-next`);

      $(`div#slider${idx}`).cycle({
        fx: "scrollHorz",
        slides: "> .slideset > div",
        pager: "> .pagination ul",
        pagerTemplate: "<li><a href='#'>{{slideNum}}</a></li>",
        pagerActiveClass: "active",
        swipe: 1,
        speed: 500,
        timeout: 5000,
        prev: `#slider${idx}-prev`,
        next: `#slider${idx}-next`,
      });
    });
  }

  $(".slider-container #btn-play").click(function (e) {
    e.preventDefault();
    var sliderContainer = $(e.target).closest(".slider-container")[0];
    var videoIframe = $(e.target)
      .closest(".text")
      .find(".video-iframe iframe")[0];
    console.log(videoIframe);

    $(sliderContainer).addClass("video-active");
    $(sliderContainer).find(".video-overlap").remove();

    var overlap =
      '<div class="content"></div>' + '<span class="btn-close">&times</span>';
    $(sliderContainer).find(".slideshow").after(overlap);
    $(sliderContainer).find(".content").html($(videoIframe).clone());

    let video = $(sliderContainer).find(".content").find("iframe")[0];

    let videoHeight = $(video).height();
    $(sliderContainer).css("max-height", videoHeight);
    $(sliderContainer).css("padding-top", videoHeight);
    $(sliderContainer)
      .find(".content")
      .css("width", $(sliderContainer).find(".slideshow").width());
    $(video).attr("src", $($(video)[0]).attr("src") + "&autoplay=1");

    $(sliderContainer)
      .find(".btn-close")
      .click(function (e) {
        $(sliderContainer).css("padding-top", 0);
        $(sliderContainer).css("max-height", "unset");
        $(video).attr("src", $($(video)[0]).attr("src") + "&autoplay=0");
        setTimeout(function () {
          $(sliderContainer).removeClass("video-active");
          $(sliderContainer).find(".content, .btn-close").remove();
        }, 1000);
      });

    // Play/pause video when scroll
    // $(window).scroll(function () {
    //     let video = $(sliderContainer).find('.video-overlap .content').find('iframe');
    //     videoType = $(video).attr('data-type');

    //     if(video.length>0){
    //         let window_offset = $(video[0]).offset().top - $(window).scrollTop();

    //         if ((window_offset <= 400) && (window_offset > 10)) {
    //             if(videoType === 'vimeo'){
    //                 var player = new Vimeo.Player(video);
    //                 player.play();
    //             }else{
    //                 $(video)[0].contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*')
    //             }
    //         } else {
    //             if(videoType === 'vimeo'){
    //                 var player = new Vimeo.Player(video);
    //                 player.pause();
    //             }else{
    //                 $(video)[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*')
    //             }
    //         }
    //     }
    // });
  });
});
