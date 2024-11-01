<?php
/*
Plugin Name: Web News By Axetue
Plugin URI: http://www.axetue.com/goodies/wordpress-plugin/
Description: Easily view the latest web and social media news and place it as a widget on your sidebar/footer.
Version: 1.1
Author: Sandeep Tripathy
Author URI: http://www.stven.net/
License: GPL3
*/

function axetuenews()
{
  $options = get_option("widget_axetuenews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Axetue News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Feed 
  $rss = simplexml_load_file( 
  'http://www.axetue.com/feed'); 
  ?> 
  
  <ul> 
  
  <?php 
  // max number of news slots, with 0 (zero) all display
  $max_news = $options['news'];
  // maximum length to which a title may be reduced if necessary,
  $max_length = $options['chars'];
  
  // RSS Elements 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Title
    $title = $i->title;
    // Length of title
    $length = strlen($title);
    // if the title is longer than the previously defined maximum length,
    // it'll he shortened and "..." added, or it'll output normaly
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_axetuenews($args)
{
  extract($args);
  
  $options = get_option("widget_axetuenews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Axetue News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  axetuenews();
  echo $after_widget;
}

function axetuenews_control()
{
  $options = get_option("widget_axetuenews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Axetue News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['axetuenews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['axetuenews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['axetuenews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['axetuenews-CharCount']);
    update_option("widget_axetuenews", $options);
  }
?> 
  <p>
    <label for="axetuenews-WidgetTitle">Widget Title: </label>
    <input type="text" id="axetuenews-WidgetTitle" name="axetuenews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="axetuenews-NewsCount">Max. News: </label>
    <input type="text" id="axetuenews-NewsCount" name="axetuenews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="axetuenews-CharCount">Max. Characters: </label>
    <input type="text" id="axetuenews-CharCount" name="axetuenews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="axetuenews-Submit"  name="axetuenews-Submit" value="1" />
  </p>
  
<?php
}

function axetuenews_init()
{
  register_sidebar_widget(__('Axetue News'), 'widget_axetuenews');    
  register_widget_control('Axetue News', 'axetuenews_control', 300, 200);
}
add_action("plugins_loaded", "axetuenews_init");
?>
