<?php

$plugin['name'] = 'arc_twitter_intents';
$plugin['version'] = '1.0-dev';
$plugin['author'] = 'Andy Carter';
$plugin['author_uri'] = 'http://redhotchilliproject.com/';
$plugin['description'] = '<a href="http://www.twitter.com">Twitter</a> for Textpattern';
$plugin['order'] = '5';
$plugin['type'] = '0';

// Plugin "flags" signal the presence of optional capabilities to the core plugin loader.
// Use an appropriately OR-ed combination of these flags.
// The four high-order bits 0xf000 are available for this plugin's private use
if (!defined('PLUGIN_HAS_PREFS')) define('PLUGIN_HAS_PREFS', 0x0001); // This plugin wants to receive "plugin_prefs.{$plugin['name']}" events
if (!defined('PLUGIN_LIFECYCLE_NOTIFY')) define('PLUGIN_LIFECYCLE_NOTIFY', 0x0002); // This plugin wants to receive "plugin_lifecycle.{$plugin['name']}" events

$plugin['flags'] = '3';

@include_once('zem_tpl.php');

# --- BEGIN PLUGIN CODE ---
global $prefs,$txpcfg;

// arc_twitter_intents requires arc_twitter v3 or higher
require_plugin('arc_twitter');

function arc_twitter_intent_follow($atts, $thing=null)
{
    global $prefs;

    extract(lAtts(array(
        'user'      => $prefs['arc_twitter_user'],
        'user_id'   => '',
        'lang'      => '',
        'include_js'=> true,
        'class'     => ''
    ),$atts));
    
    $q = ($user_id) ? 'user_id='.$user_id : 'screen_name='.$user;
    
    switch ($lang) {
      case 'fr': break; case 'it': break; case 'es': break; 
      case 'ko': break; case 'jp': break;
      default:
        $lang = 'en';
    }
    $q .= '&amp;lang='.urlencode($lang);
    
    $html = href($thing,'http://twitter.com/intent/user?'.$q
      , ' class="'.$class.'"');
      
    $js = ($include_js) ? _arc_twitter_widget_js() : '';
      
    return $js.$html;
}

function arc_twitter_intent_favorite($atts, $thing=null)
{
    global $prefs, $thisarticle; 

    extract(lAtts(array(
        'user'      => $prefs['arc_twitter_user'],
        'related'   => '',
        'include_js'=> true,
        'id'        => '',
        'class'     => ''
    ),$atts));
    
    if ($id || $thisarticle['thisid']) {
    
      $q = 'related='.$user;
      if ($related) $q .= urlencode(($q?',':'related=').$related);
      
      if (!$id) {
        $row = safe_row("tweet_id"
        , 'arc_twitter', "article_id={$thisarticle['thisid']}");
        if (!$id = $row['tweet_id']) return false;
      }
      
      $q .= '&amp;tweet_id='.$id;
      
      $html = href($thing,'http://twitter.com/intent/favorite?'.$q
        , ' class="'.$class.'"');
        
      $js = ($include_js) ? _arc_twitter_widget_js() : '';
        
      return $js.$html;
    
    }
    
    return false;
}

function arc_twitter_intent_retweet($atts, $thing=null)
{
    global $prefs, $thisarticle; 

    extract(lAtts(array(
        'user'      => $prefs['arc_twitter_user'],
        'related'   => '',
        'include_js'=> true,
        'id'        => '',
        'class'     => ''
    ),$atts));
    
    if ($id || $thisarticle['thisid']) {
    
      $q = 'related='.$user;
      if ($related) $q .= urlencode(($q?',':'related=').$related);
      
      if (!$id) {
        $row = safe_row("tweet_id"
        , 'arc_twitter', "article_id={$thisarticle['thisid']}");
        if (!$id = $row['tweet_id']) return false;
      }
      
      $q .= '&amp;tweet_id='.$id;
      
      $html = href($thing,'http://twitter.com/intent/retweet?'.$q
        , ' class="'.$class.'"');
        
      $js = ($include_js) ? _arc_twitter_widget_js() : '';
        
      return $js.$html;
    
    }
    
    return false;
}

function arc_twitter_intent_reply($atts, $thing=null)
{
    global $prefs, $thisarticle; 

    extract(lAtts(array(
        'user'      => $prefs['arc_twitter_user'],
        'related'   => '',
        'text'      => '',
        'include_js'=> true,
        'id'        => '',
        'class'     => ''
    ),$atts));
    
    if ($id || $thisarticle['thisid']) {
    
      if (!$id) {
        $row = safe_row("tweet_id"
        , 'arc_twitter', "article_id={$thisarticle['thisid']}");
        if (!$id = $row['tweet_id']) return false;
      }
    
      $q = 'in_reply_to='.$id;
      
      if ($user) {
        $q .= '&amp;related='.urlencode($user);
      }
      if ($related) {
        $q .= urlencode(($user?',':'&related=').$related);
      }
      if ($text) {
        $q .= '&amp;text='.urlencode($text);
      }
      
      $html = href($thing,'http://twitter.com/intent/tweet?'.$q
        , ' class="'.$class.'"');
        
      $js = ($include_js) ? _arc_twitter_widget_js() : '';
        
      return $js.$html;
    
    }
    
    return false;
}


# --- END PLUGIN CODE ---
if (0) {
?>
<!--
# --- BEGIN PLUGIN HELP ---

h1(title). TXP Tweet Intents


# --- END PLUGIN HELP ---
-->
<?php
}
?>