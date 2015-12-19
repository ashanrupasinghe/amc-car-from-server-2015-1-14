<?php
if (!defined("AT_DIR")) die('!!!');
class AT_post_widget extends AT_Model {

    private $_method = '';

    public function __construct($params = array()) {
        parent::__construct();
    }

    public function render($params = array()) {
        $view = new AT_View();
        $view->use_widget( 'post' );
        $method_exec = '_' . $params['data'] . '_data';
        $view->add_block('content', 'content', array( 'content' => $this->$method_exec( $view, $params ) ) );
        return $view->render()->display(TRUE);
    }

    private function _meta_data( $view, $params ) {
        $defaults = array(
            'class' => '',
        );
        $params = wp_parse_args( $params, $defaults );

        $content = '';
        // Date
        $content .= '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '" class="blog_date"><i class="icon-calendar"></i>' . get_the_date( 'F j, Y' ) . '</a>';

        // Categories
        $post_categories = wp_get_post_categories( get_the_ID() );
        $categories = array();
        foreach($post_categories as $c){
            $cat = get_category( $c );
            $categories[] = '<a href="' . get_category_link( $cat->term_id ) . '">' . $cat->name . '</a>';
        }
        if( count( $categories ) > 0 ) {
            $content .= '<div class="blog_category"><i class="icon-tag"></i>' . implode( ', ', $categories ) . '</div>';
        }

        // Author
        $content .= '<span class="blog_author"><i class="icon-user"></i>' . get_the_author() . '</span>';

        $post_tags = wp_get_post_tags( get_the_ID() );
        $tags = array();
        foreach($post_tags as $tag){
            $tags[] = '<a href="' . get_tag_link( $tag->term_id ) . '">' . $tag->name . '</a>';
        }
        
        if( count( $tags ) > 0 ) {
            $content .= '<div class="blog_category"><i class="icon-tag"></i>' . implode( ', ', $tags ) . '</div>';
        }
        return '<div class="' . $params['class'] . '">' . $content . '</div>';
    }

    private function _thumbnail_data( $view, $params ){
        $defaults = array(
            'is_link' => false,
            'is_preview' => false,
            'class' => '',
            'size' => 'medium',
        );
        $params = wp_parse_args( $params, $defaults );
        if ( has_post_thumbnail() && !get_post_meta( get_the_ID(), '_disable_post_image', true ) ) {
            //$img = get_the_post_thumbnail( get_the_ID(), $params['size'] );
            $src = wp_get_attachment_image_src( get_post_thumbnail_id(), $params['size'] );
            $img = '<img src="' . $src[0] . '" title="' . get_the_title( get_the_ID() ) . '" />';

            if ( $params['is_link'] ){
                $content = '<a href="' . get_permalink() . '" class="thumb ' . $params['class'] . '">' . $img . '</a>';
            } else if ( $params['is_preview'] ){
                $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
                $content = '<a href="' . $large_image_url[0] . '" class="thumb ' . $params['class'] . '" rel="zoom_image">' . $img . '</a>';
            } else {
                $content = '<div class="thumb ' . $params['class'] . '">' . $img . '</div>';
            }
        } else {
            $content = '';
        }
        return $content;
    }

}
