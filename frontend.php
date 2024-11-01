<?php

if (!class_exists('slider_view_frontend')) {

  class slider_view_frontend
  {

    private $options, $scripts, $version;

    public function __construct($version)
    {

      //get plugin options
      $this->options = get_option('sliderview_main_opts');

      //set version
      $this->version = $version;

      //add hooks
      add_shortcode('sliderview', array($this, 'slider'));
      add_action('wp_enqueue_scripts', array($this, 'add_includes'));
    }

    public function add_includes()
    {
      wp_enqueue_style('sliderview_frontend_style', plugins_url('css/frontend_style.css', __FILE__));
      wp_enqueue_script('jquery');
      wp_enqueue_script('cycle-script', plugins_url('js/jquery.cycle2.min.js', __FILE__), array('jquery'), '1.0.0', true);
      wp_enqueue_script('sv-frontend', plugins_url('js/frontend.js', __FILE__), array('jquery'), '1.0.0', true);
      wp_enqueue_script('vimeo-player', plugins_url('js/vimeo-player.js', __FILE__), array('jquery'), '1.0.0', true);
    }

    public function slider($atts)
    {

      global $wpdb;

      if (!isset($atts['id']))
        return '<span class="sv-invalid-code">Invalid Slider ID</span>';

      $id = sanitize_text_field($atts['id']);

      $dir = wp_upload_dir();
      $dir = $dir['basedir'];

      $slider_data = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'slider_data WHERE DATA_ID = ' . $id, ARRAY_A);
      $mediaData = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'slider_item WHERE DATA_ID = "' . $id . '" ORDER BY ITEM_POS', ARRAY_A);

      if ($slider_data == null || $mediaData == null)
        return '<span class="sv-invalid-code">Slider is empty</span>';

      $slider_data = $slider_data[0];
      $playerHeight = $slider_data['DATA_DISPLAYHEIGHT'];

      //default in case no valid height value given
      if ($playerHeight == null || $playerHeight == 0)
        $playerHeight = 480;

      $html = '<div class="slider-container"> 
      <div class="slideshow" data-cycle-pause-on-hover="true" style="height:' . $playerHeight . 'px">
          <div class="slideset">';

      //get data and process
      foreach ($mediaData as $val) {

        $image = $val['ITEM_PHOTO'];
        $title = $val['ITEM_TITLE'];
        $videoID = $val['ITEM_VIDEO_ID'];

        $slide = '';

        if ($val['ITEM_TYPE'] === 'link') {
          $content = '<a href="' . $val['ITEM_LINK'] . '">Learn more<img src="' . plugin_dir_url(__FILE__) . 'images/arrow-right.svg" alt="arrow right" /></a>';

          $slide = '<div class="slide">
          <a href="' . $val['ITEM_LINK'] . '" class="learn-more"></a>
                      <img src="' . $image . '" alt="image slide" />
                    <div class="text">
                        <h1>' . $title . '</h1>'
            . $content .
            '</div>
                  </div>';
        } elseif ($val['ITEM_TYPE'] === 'video') {
          $video = $val['ITEM_VIDEO_TYPE'];
          if ($val['ITEM_VIDEO_TYPE'] == 'vimeo') {
            $intern = "https://player.vimeo.com/video/" . $videoID  . "/?api=1&autopause=0";
            $video = '<iframe title="' . $title . '" src="' . $intern . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-type="' . $val['ITEM_VIDEO_TYPE'] . '"></iframe>';
          } elseif ($val['ITEM_VIDEO_TYPE'] == 'youtube') {
            $intern = "https://www.youtube.com/embed/" . $videoID . "?iv_load_policy=3&autohide=1&showinfo=0&controls=0&enablejsapi=1";
            $video = '<iframe src="' . $intern . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-type="' . $val['ITEM_VIDEO_TYPE'] . '"></iframe>';
          }

          $content = '<div class="video-iframe">' . $video . '</div>
          <a href="#" id="btn-play"><img src="' . plugin_dir_url(__FILE__) . 'images/play-button.svg" alt="play button" /></a>';

          $slide = '<div class="slide">
                      <img src="' . $image . '" alt="image slide" />
                    <div class="text">
                        <h1>' . $title . '</h1>'
            . $content .
            '</div>
                  </div>';
        }

        $html .= $slide;
      }
      $html .= '</div>';
      if ($slider_data['DATA_DISPLAYDOT'] == 1) {
        $dot = '<div class="pagination"><ul></ul></div>';
        $html .= $dot;
      }
      if ($slider_data['DATA_DISPLAYARROW'] == 1) {
        $arrow = '<a class="prevBtn" href="#">
        <img src="' . plugin_dir_url(__FILE__) . 'images/arrow-left.svg" alt="arrow left" /></a>
        </a> <a class="nextBtn" href="#">
        <img src="' . plugin_dir_url(__FILE__) . 'images/arrow-right.svg" alt="arrow right" /></a>
        </a>';
        $html .= $arrow;
      }

      $html .= '</div></div>';

      //return html
      return $html;
    }
  }
}
