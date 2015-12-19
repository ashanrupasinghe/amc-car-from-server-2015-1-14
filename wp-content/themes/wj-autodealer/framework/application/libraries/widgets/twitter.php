<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Twitter_Widget extends WP_Widget { 
  
    public function AT_Twitter_Widget() {
        $widget_ops = array('description' => __('Fetch twitter feed.',AT_ADMIN_TEXTDOMAIN) );
        $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'wj_twitter' );
        $this->WP_Widget( 'wj_twitter', sprintf( __('%1$s - Twitter', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ), $widget_ops, $control_ops );
    }
  
    public function widget($args, $instance) {
        extract($args);
        $defaults = array( 'title' => '', 'label' => '', 'url'=>'', 'content' => '', 'id' => '');
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = '';
        $html = '';

        $id = $instance['id'];
        
        if ( !$number = (int) $instance['number'] ) {
            $number = 5;
        } else if ( $number < 1 ) {
            $number = 1;
        } else if ( $number > 40 ) {
            $number = 40;
        }
        
        $username = isset($instance['id']) ? trim( $instance['id'] ) : '';
        $type = 'widget';

        $wj_timeline_store = new wj_timeline_store(
          $oauth_access_token = ( !empty($instance['oauth_access_token'] ) ) ? $instance['oauth_access_token'] : '',
          $oauth_access_token_secret = ( !empty( $instance['oauth_access_token_secret'] ) ) ? $instance['oauth_access_token_secret'] : '',
          $consumer_key = ( !empty( $instance['consumer_key'] ) ) ? $instance['consumer_key'] : '',
          $consumer_secret = ( !empty( $instance['consumer_secret'] ) ) ? $instance['consumer_secret'] : '',
          $screen_name = $username,
          $count = $number
        );

        $results = $wj_timeline_store->returnTweet();

        // Create beautiful title
        if (isset($instance['title'])) {
            $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
            if ( !empty($title) ) {
                $title_array = explode("\x20",$title);
                $title = "";
                foreach($title_array as $tcount => $word) {
                    if ( $tcount == 0 ) {
                        $word = "<strong>" . $word . "</strong>";
                    }
                    $title .= $word . " ";
                }
                $title = "<h2>" . $title . "</h2>";
            }
        }


        echo '<div class="widget">';
        echo $before_widget;
        echo $title;
        echo '<ul class="twitter_feed">';
        if ( isset( $results ) && is_array( $results ) && !empty( $results ) ) {
          foreach ( $results as $key => $tweet ) {
            if ( $key == "errors" || $key == "error" ) {
                // echo '<li>';
                // echo $tweet;
                // echo '</li>';
            } else {
                echo '<li>';
                echo '<i class="icon-twitter"></i>';
                echo $tweet['text'];
                echo '<a class="target_blank" target="_BLANK" href="http://twitter.com/1/status/' . $tweet['id_str'] . '" title="' . sprintf( esc_attr__( '%1$s&nbsp;ago', AT_TEXTDOMAIN ), AT_Common::convert_time(strtotime( $tweet['created_at'] ) ) ) . '">@' . $username . '</a>';
                echo '</li>';
            }
          }
        } else {
            echo '<li>' . __('Tweets not found.', AT_TEXTDOMAIN ) . '</li>';
        }
        echo '</ul>';

        echo $after_widget;
        echo '</div>';
    }
  
    /* Store */
    public function update( $new_instance, $old_instance ) {  
        $instance = $old_instance; 
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['id'] = strip_tags($new_instance['id']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['oauth_access_token'] = strip_tags($new_instance['oauth_access_token']);
        $instance['oauth_access_token_secret'] = strip_tags($new_instance['oauth_access_token_secret']);
        $instance['consumer_key'] = strip_tags($new_instance['consumer_key']);
        $instance['consumer_secret'] = strip_tags($new_instance['consumer_secret']);

        return $instance;
    }

    /* Settings */
    public function form($instance) {
        $defaults = array(
            'title' => 'Twitter feed',
            'number' => '3',
            'id' => '',
            'oauth_access_token' => '',
            'oauth_access_token_secret' => '',
            'consumer_key' => '',
            'consumer_secret' => '',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );

        // field: Widget title
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'title' ) . '">Widget Title:</label>';
        echo '<input class="widefat" id="' . $this->get_field_id( 'title' ) .'" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" />';
        echo '</p>';

        echo '<p>';
        echo '<label for="' .  $this->get_field_id('id') . '">';
        echo 'Twitter username:';
        echo '</label>';
        echo '<input class="widefat" id="' .  $this->get_field_id('id') . '" name="' .  $this->get_field_name('id') . '" type="text" value="' .  $instance['id'] . '" />';
        echo 'Please enter twitter username.';
        echo '</p>';

        echo '<p>';
        echo '<label for="' .  $this->get_field_id('number') . '">Enter the number of tweets you\'d like to display:</label>';
        echo '<input class="widefat" id="' .  $this->get_field_id('number') . '" name="' .  $this->get_field_name('number') . '" type="number" value="' .  $instance['number'] . '" />';
        echo '</p>';

        echo '<h3>Twitter Authentication</h3>';
        echo '<div class="block" id="oauth-tool">';
        echo 'Before using the OAuth tool, please double check you have registered at least one <a href="https://dev.twitter.com/apps" target="_BLANK">Twitter Application</a>.';
        echo '</div>';

        echo '<p>';
        echo '<label for="' .  $this->get_field_id('consumer_key') . '">';
        echo 'Consumer key:';
        echo '</label>';
        echo '<input class="widefat" id="' .  $this->get_field_id('consumer_key') . '" name="' .  $this->get_field_name('consumer_key') . '" type="text" value="' .  $instance['consumer_key'] . '" />';
        echo 'Please enter API consumer key.';
        echo '</p>';

        echo '<p>';
        echo '<label for="' .  $this->get_field_id('consumer_secret') . '">';
        echo 'Consumer secret:';
        echo '</label>';
        echo '<input class="widefat" id="' .  $this->get_field_id('consumer_secret') . '" name="' .  $this->get_field_name('consumer_secret') . '" type="text" value="' .  $instance['consumer_secret'] . '" />';
        echo 'Please enter API consumer secret.';
        echo '</p>';

        echo '<p>';
        echo '<label for="' .  $this->get_field_id('oauth_access_token') . '">';
        echo 'Access token:';
        echo '</label>';
        echo '<input class="widefat" id="' .  $this->get_field_id('oauth_access_token') . '" name="' .  $this->get_field_name('oauth_access_token') . '" type="text" value="' .  $instance['oauth_access_token'] . '" />';
        echo 'Please enter API access token.';
        echo '</p>';

        echo '<p>';
        echo '<label for="' .  $this->get_field_id('oauth_access_token_secret') . '">';
        echo 'Access secret:';
        echo '</label>';
        echo '<input class="widefat" id="' .  $this->get_field_id('oauth_access_token_secret') . '" name="' .  $this->get_field_name('oauth_access_token_secret') . '" type="text" value="' .  $instance['oauth_access_token_secret'] . '" />';
        echo 'Please enter API access secret';
        echo '</p>';

    }
}



/**
 * Twitter TimeLine 1.1 Builder
 * author: Jarod Denison
 *
 * @since 1.7
 */
class wj_timeline_store {
    protected $oauth_access_token;
    protected $oauth_access_token_secret;
    protected $consumer_key;
    protected $consumer_secret;
    protected $screen_name;
    protected $count;
    protected $encryption = 'HMAC-SHA1';
    protected $oauth_version = '1.0';
    protected $timeline_store = 'user_timeline';
    protected $method = 'GET';

    function __construct( $oauth_access_token, $oauth_access_token_secret, $consumer_key, $consumer_secret, $screen_name, $count ) {
        $this->oauth_access_token = $oauth_access_token;
        $this->oauth_access_token_secret = $oauth_access_token_secret;
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
        $this->screen_name = $screen_name;
        $this->count = $count;
    }


    function buildBaseString($baseURI, $params) {
        $r = array();
        ksort($params);
        foreach($params as $key=>$value){
                $r[] = "$key=" . rawurlencode($value);
        }
        return $this->method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }

    function oauthHeader($oauth) {
        $r = 'Authorization: OAuth ';
        $values = array();
        foreach($oauth as $key=>$value)
                $values[] = "$key=\"" . rawurlencode($value) . "\"";
        $r .= implode(', ', $values);
        return $r;
    }

    function returnTweet(){

        $timeline_store = "user_timeline";  //  mentions_timeline / user_timeline / home_timeline / retweets_of_me

        $query = array(
                'screen_name' => $this->screen_name,
                'count' => $this->count
        );

        $oauth = array(
                'oauth_consumer_key' => $this->consumer_key,
                'oauth_nonce' => time(),
                'oauth_signature_method' => $this->encryption,
                'oauth_token' => $this->oauth_access_token,
                'oauth_timestamp' => time(),
                'oauth_version' => $this->oauth_version
        );
        $oauth = array_merge($oauth, $query);

        $base_info = $this->buildBaseString("https://api.twitter.com/1.1/statuses/$timeline_store.json", $oauth);
        $composite_key = rawurlencode($this->consumer_secret) . '&' . rawurlencode($this->oauth_access_token_secret);
        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;

        $query_header = array( $this->oauthHeader($oauth), 'Expect:');
        $params = array(
            CURLOPT_HTTPHEADER => $query_header,
            CURLOPT_HEADER => false,
            CURLOPT_URL => "https://api.twitter.com/1.1/statuses/" . $this->timeline_store . ".json?". http_build_query($query),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        );

        $feed = curl_init();
        curl_setopt_array($feed, $params);
        $json = curl_exec($feed);
        curl_close($feed);

        return json_decode($json, true);
    }

}