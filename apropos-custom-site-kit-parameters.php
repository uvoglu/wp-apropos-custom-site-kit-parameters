<?php

/*
 * Plugin Name:       Custom Google Analytics Parameters
 * Plugin URI:        https://github.com/uvoglu/wp-apropos-custom-site-kit-parameters
 * Description:       Add custom post metadata to Google Analytics. This plugin acts as an extension for the Site Kit by Google plugin and configuration needs to be done in the Site Kit plugin.
 * Version:           0.1.2
 * Author:            Simon Schuhmacher
 * Author URI:        https://uvoglu.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       apropos-custom-site-kit-parameters
 */

class Apropos_CustomSiteKitParameters {
    const CUSTOM_DIMENSION_ISSUE_TITLE = 'apropos_issue_title';
    const CUSTOM_DIMENSION_ISSUE_SLUG = 'apropos_issue_slug';
    const CUSTOM_DIMENSION_POST_TITLE = 'apropos_post_title';
    const CUSTOM_DIMENSION_POST_SLUG = 'apropos_post_slug';
    const CUSTOM_DIMENSION_POST_LANGUAGE = 'apropos_post_language';

    public static function run() {
        $instance = new Apropos_CustomSiteKitParameters();
        add_filter( 'googlesitekit_gtag_opt', array( $instance, 'add_additional_gtag_config' ), 10, 1 );
    }

    function add_additional_gtag_config( $gtag_opt ) {
        $post = get_queried_object();
        $filtered_gtag_opt = $gtag_opt;
    
        if ( is_singular( 'post' ) ) {
            $issues_current = get_the_terms( $post->ID, 'issue' );
            if ( ! empty( $issues_current ) && is_array( $issues_current ) ) {
                $issues_en = $this->get_issues_translated( $issues_current, 'en' );
                $issues = ! empty( $issues_en ) ? $issues_en : $issues_current;
    
                $issue_names = wp_list_pluck( $issues, 'name' );
                $filtered_gtag_opt[ self::CUSTOM_DIMENSION_ISSUE_TITLE ] = implode( '; ', $issue_names );
    
                $issue_slugs = wp_list_pluck( $issues, 'slug' );
                $filtered_gtag_opt[ self::CUSTOM_DIMENSION_ISSUE_SLUG ] = implode( '; ', $issue_slugs );
            }
    
            $post_en = $this->get_post_translated( $post->ID, 'en' );
            if ( ! empty( $post_en ) ) {
                $filtered_gtag_opt[ self::CUSTOM_DIMENSION_POST_TITLE ] = $post_en->post_title;
                $filtered_gtag_opt[ self::CUSTOM_DIMENSION_POST_SLUG ] = $post_en->post_name;
            }
    
            if ( function_exists( 'pll_get_post_language' ) ) {
                $post_language = pll_get_post_language( $post->ID, 'slug' );
                if ( ! empty( $post_language ) ) {
                    $filtered_gtag_opt[ self::CUSTOM_DIMENSION_POST_LANGUAGE ] = $post_language;
                }   
            }
        }
    
        return $filtered_gtag_opt;
    }

    private function get_issues_translated( $issues, $language ) {
        $issues_translated = array();
    
        foreach ( $issues as $issue ) {
            if ( function_exists( 'pll_get_term_translations' ) ) {
                $issue_translations = pll_get_term_translations( $issue->term_id );
                if ( ! empty( $issue_translations[ $language ] ) ) {
                    $issue_translated = get_term( $issue_translations[ $language ], 'issue' );
                    if ( isset( $issue_translated ) ) {
                        array_push( $issues_translated, $issue_translated );
                    }
                }
            }
        }
    
        return $issues_translated;
    }

    private function get_post_translated( $post_id, $language ) {
        if ( function_exists( 'pll_get_post_translations' ) ) {
            $post_translations = pll_get_post_translations( $post_id );
        
            if ( ! empty( $post_translations[ $language ] ) ) {
                return get_post( $post_translations[ $language ] );
            }
        }
    
        return null;
    }
}

Apropos_CustomSiteKitParameters::run();
