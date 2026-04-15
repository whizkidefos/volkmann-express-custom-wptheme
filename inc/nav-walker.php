<?php
/**
 * Volkmann Express — Custom Navigation Walker
 */

defined( 'ABSPATH' ) || exit;

class VE_Nav_Walker extends Walker_Nav_Menu {

    private int $depth_track = 0;

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $this->depth_track = $depth + 1;
        if ( $depth === 0 ) {
            $output .= '<ul class="ve-nav__dropdown">';
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            $output .= '</ul>';
        }
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes   = empty( $item->classes ) ? [] : (array) $item->classes;
        $has_child = in_array( 'menu-item-has-children', $classes );
        $is_active = in_array( 'current-menu-item', $classes ) || in_array( 'current-menu-ancestor', $classes );

        $item_class = 've-nav__item';
        if ( $has_child ) $item_class .= ' ve-nav__item--has-children';
        if ( $is_active ) $item_class .= ' ve-nav__item--active';
        if ( $depth > 0 ) $item_class .= ' ve-nav__item--sub';

        $output .= "<li class=\"{$item_class}\">";

        $link_class = $depth === 0 ? 've-nav__link' : 've-nav__sublink';
        if ( $is_active ) $link_class .= ' active';

        $url    = esc_url( $item->url );
        $title  = apply_filters( 'the_title', $item->title, $item->ID );
        $target = $item->target ? " target=\"{$item->target}\"" : '';

        if ( $has_child && $depth === 0 ) {
            $output .= "<a href=\"{$url}\" class=\"{$link_class}\"{$target}>";
            $output .= "<span>{$title}</span>";
            $output .= '<svg class="ve-nav__arrow" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 6 8 10 12 6"/></svg>';
            $output .= '</a>';
        } else {
            $output .= "<a href=\"{$url}\" class=\"{$link_class}\"{$target}>{$title}</a>";
        }
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= '</li>';
    }
}
