<?php

/**
 * Plugin Name: WordPress Widget Embed
 * Description: Allow people to embed WordPress content in an iframe on other websites
 * Version: 1.0
 * Author: Sameer Borate
 * Author URI: http://www.codediesel.com
 */

class WPWidgetEmbed  
{
    public function __construct()
    {
        add_action('template_redirect', array($this, 'catch_widget_query'));
        add_action('init', array($this, 'widget_add_vars'));

    }
    
    /**
     * Adds our widget query variable to WordPress $vars
     */
    public function widget_add_vars() 
    { 
        global $wp; 
        $wp->add_query_var('em_embed'); 
        $wp->add_query_var('em_domain');
        $wp->add_query_var('size_button');
        $wp->add_query_var('posttype');
        $wp->add_query_var('count');
        $wp->add_query_var('border');
        $wp->add_query_var('bordercolor');
        $wp->add_query_var('fontt');
        $wp->add_query_var('button_color');
        //$size_button = 0;
    }
    
    //export for informer
    private function export_posts( $posttype = 'news', $count =3, $border='', $bordercolor='e6e6e6', $fontt="Arial")
    {
    	// var_dump($border);
    	 //var_dump($fontt);

    $outstring  = '<html>';
    $outstring .= '<head><style>';
    $outstring .= '

          ul {
            padding-top: 10px;
             padding-left:10px;
             margin:0;
          }
          li > a , .inf-content , .inf-content a {
             font-family:'.$fontt.', Helvetica, Sans-serif;
          }
          
          li > a , .inf-content , .inf-content a {
           
             
             font-size:14px;
             color: #263ce3;
          }
          .inf-content {
            padding-left: 10px;
            padding-right: 10px;
          }

          .inf-content .entry-meta {
              color: #999999;
              font-size: 12px;
          } 
          li {
              list-style: none;
              display: inline-block;
              width: 280px;
              padding: 10px 0 3px 0;
              vertical-align: top;
              border: 1px solid #c0c0c0;
              min-height: 90px;
              margin: 5px;
          }
          .inf-thumbnail {
            float:left;
              padding: 3px 10px 10px;
          }
           .inf-thumbnail img {
          	border:'.$border.' #'.$bordercolor.';
          }
          .clear {
              clear: both;
          }
          .widget-posts {
             margin-left: 3px;
          }';
    $outstring .= '</style></head><body>';
         
        /* Here we get recent posts for the blog */
        //var_dump($posttype);

        $args = array(
            'numberposts' => $count,
            'offset' => 0,
            'category_name' => $posttype,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'post_type' => 'post',
            'post_status' => 'publish',
            'suppress_filters' => true
        );
        
        $recent_posts = wp_get_recent_posts($args);
        
        
         ob_start();
         ?>
         <div class="widget-posts">
             <ul>
                <?php  foreach($recent_posts as $recent)  { 
                    $post_id = ($recent['ID']);
                    ?>
                    <li>
                        <?php if(has_post_thumbnail($post_id)){ ?>
                          <div class="inf-thumbnail">
                              <?php 
                              $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'post'); 
                              $params = array( 'width' => 100 , 'height' => 70);

                              echo "<img src='" . bfi_thumb( $thumb[0], $params ) . "'/>";
                              ?>
                          </div>
                        <?php } ?>

                        <div class="inf-content">
                            <div class="entry-meta">
                                <span class="entry-date">
                                    <abbr class="published"><?php echo get_the_time('d F в g:i', $post_id ); ?></abbr>
                                </span>
                            </div>

                            <a target="_blank" href="<?php echo get_permalink( $post_id ); ?>">
                                <?php //echo $recent["post_title"];
                                 echo short_title_widget('...',100, $recent["post_title"]);
                                 ?>
                            </a>
                        </div> 
                        <div class="clear"></div>   
                    </li>
                    

                <?php } ?>

            </ul>
        </div>
        </body></html>

        <?php 
        $outstring .= ob_get_clean();
        
        return $outstring;
    }

    //export for button
    private function export_button($size_button=0, $button_color)
    {
      
      if ($button_color === undefined) {
        $button_color='263ce3';
      }
       //var_dump($button_color);

    $outstring  = '<html>';
    $outstring .= '<head><style>';
    $outstring .= '
          .three-columns {
             width: 230px;
             display: inline-block;
             vertical-align: top;
          }
          .three-columns h3 {
            color: #1a1a1a;
            font-size: 17px;
            font-family: Helvetica,Arial,sans-serif;
            text-align: center;
          }
          .three-columns .led-button {
              margin: auto;
          }
          .led-button-3 {
             width: 31px;
             height:31px;
             background-color: #'.$button_color.';
          }
          .led-button-2 {
             width: 88px;
             height:15px;
             background-color: #'.$button_color.';
          }
          .led-button-1 {
             width: 88px;
             height:33px;
             background-color: #'.$button_color.';
          }';

          $outstring .= '</style></head><body>';

          //$outstring .= $size_button;

          ob_start(); ?>

         <?php if ($size_button ==1) { ?>
           <div class="led-button-1">
              <a href="<?php echo esc_url(home_url( '/' )); ?>">
                  <img src="<?php echo get_template_directory_uri(); ?>/images/led-button-soc.png" alt="lednews.ru">
              </a>
           </div>
         <?php } else if ($size_button ==2) {?>
            <div class="led-button-2">
              <a href="<?php echo esc_url(home_url( '/' )); ?>">
                  <img src="<?php echo get_template_directory_uri(); ?>/images/led-button-soc-2.png" alt="lednews.ru">
              </a>
           </div>
         <?php } else if ($size_button ==3) {?>
            <div class="led-button-3">
              <a href="<?php echo esc_url(home_url( '/' )); ?>">
                  <img src="<?php echo get_template_directory_uri(); ?>/images/led-button-soc-3.png" alt="lednews.ru">
              </a>
           </div>
         <?php } else if ($size_button ==999) {?>
          
            <!-- begin -->
            <div class="three-columns">
              <h3>Размер 88х33</h3>
              <div class="led-button-1 led-button">
                <a href="<?php echo esc_url(home_url( '/' )); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/led-button-soc.png" alt="lednews.ru">
                </a>
             </div>
            </div>
           
           <div class="three-columns">
              <h3>Размер 88х15</h3>
              <div class="led-button-2 led-button">
                <a href="<?php echo esc_url(home_url( '/' )); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/led-button-soc-2.png" alt="lednews.ru">
                </a>
             </div>
            </div>
           
           <div class="three-columns">
              <h3>Размер 31х31</h3>
              <div class="led-button-3 led-button">
                <a href="<?php echo esc_url(home_url( '/' )); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/led-button-soc-3.png" alt="lednews.ru">
                </a>
             </div>
            </div>
           <!-- end -->

         <?php } else  {?>
            <div class="led-button-1">
              <a href="<?php echo esc_url(home_url( '/' )); ?>">
                  <img src="<?php echo get_template_directory_uri(); ?>/images/led-button-soc.png" alt="lednews.ru">
              </a>
           </div>
         <?php } ?>
         

        <?php 
        $outstring .= ob_get_clean();
        $outstring .= '</body></html>';
        
        return $outstring;
    }

    /**
     * Catches our query variable. If it's there, we'll stop the
     * rest of WordPress from loading and do our thing, whatever 
     * that may be. 
     */
    public function catch_widget_query()
    {
        /* If no 'embed' parameter found, return */
        if(!get_query_var('em_embed')) return;
        
        /* 'embed' variable is set, export any content you like */
        
        // var_dump(get_query_var('posttype'));
        // var_dump(get_query_var('size_button'));



        if(get_query_var('em_embed') == 'posts')
          { 
              $data_to_embed = $this->export_posts( get_query_var('posttype'), get_query_var('count'), get_query_var('border'), get_query_var('bordercolor'), get_query_var('fontt')  );
              echo $data_to_embed;
          } 
        else 
        if(  (get_query_var('em_embed') == 'button') && (get_query_var('size_button') != 0) )
          { 
              
              $data_to_embed = $this->export_button( get_query_var('size_button'), get_query_var('button_color') );
              echo $data_to_embed;
          }
        else 
        // if( (get_query_var('em_embed') == 'button')  )
          { 
              
              // $data_to_embed = $this->export_button();
              // echo $data_to_embed;
                    $data_to_embed = $this->export_posts( get_query_var('posttype'), get_query_var('count'), get_query_var('border'), get_query_var('bordercolor'), get_query_var('fontt')  );
        echo $data_to_embed;
          }
        
        exit();
    }
}
 
$widget = new WPWidgetEmbed();

?>