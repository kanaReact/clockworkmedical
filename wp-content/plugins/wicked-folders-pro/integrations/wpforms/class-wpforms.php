<?php

namespace Wicked_Folders\Pro\Integrations\WPForms;

use Wicked_Folders;
use Wicked_Folders\Admin as Core_Admin;

// Disable direct load
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

class WPForms {

    public $post_type_name = 'wpforms';

    public function __construct() {
        add_filter( 'wicked_folders_get_current_screen_post_type',      array( $this, 'get_current_screen_post_type' ) );
        add_filter( 'wpforms_overview_table_column_value',              array( $this, 'populate_move_to_folder_column' ), 10, 3 );  
        add_filter( 'wicked_folders_post_type_objects',                 array( $this, 'add_wpforms_post_type_to_wicked_folders_post_types' ) );   
        add_filter( 'wpforms_overview_table_prepare_items_args',        array( $this, 'filter_list_table' ) ); 
        add_filter( 'wicked_folders_after_ajax_scripts',                array( $this, 'after_ajax_scripts' ) );
        add_filter( 'wpforms_overview_table_columns',                   array( $this, 'add_move_to_folder_column' ) );
    }

    public function is_post_type_screen() {
        return isset( $_GET['page'] ) && 'wpforms-overview' == $_GET['page'] && ! isset( $_GET['form_id'] );        
    }

    public function add_move_to_folder_column( $columns ) {
        $columns = array( 'wicked_move' => __( 'Move to Folder', 'wicked-folders' ) ) + $columns;

        return $columns;
    }

    public function populate_move_to_folder_column( $value, $form, $column_name ) {
        if ( 'wicked_move' == $column_name ) {
			return '<div class="wicked-move-multiple" data-object-id="' . esc_attr( $form->ID ) . '"><span class="wicked-move-file dashicons dashicons-move"></span><div class="wicked-items"><div class="wicked-item" data-object-id="' . esc_attr( $form->ID ) . '">' . esc_html( $form->post_title ) . '</div></div>';
        }

        return $value;
    }

    public function add_wpforms_post_type_to_wicked_folders_post_types( $post_types ) {
        $enabled_post_types = Wicked_Folders::get_instance()->post_types();

        if ( in_array( $this->post_type_name, $enabled_post_types ) ) {
            if ( null !== $wpforms_post_type = get_post_type_object( $this->post_type_name ) ) {
                $post_types[] = $wpforms_post_type;
            }
        }

        return $post_types;
    }

    public function filter_list_table( $args ) {
        $state      = Core_Admin::get_instance()->get_screen_state();
        $taxonomy   = Wicked_Folders::get_tax_name( $this->post_type_name );

        if ( $state->folder ) {
            if ( 'unassigned_dynamic_folder' == $state->folder ) {
                $folder_ids = get_terms( $taxonomy, array( 'fields' => 'ids' ) );

                $tax_query = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $folder_ids,
                    'operator' => 'NOT IN',
                );
            } else {
                $tax_query = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $state->folder,
                );
            }

            if ( empty( $args['tax_query'] ) ) {
                $args['tax_query'] = array( $tax_query );
            } else {
                $args['tax_query'][] = $tax_query;
            }
        }
        
        return $args;
    }

    public function get_current_screen_post_type( $post_type ) {
        if ( $this->is_post_type_screen() ) {
            $post_type = $this->post_type_name;
        }

        return $post_type;
    }

    public function after_ajax_scripts( $scripts ) {
        if ( $this->is_post_type_screen() ) {
            $scripts[] = plugins_url( 'wpforms-lite/assets/js/admin/forms/overview.min.js' );
        }

        return $scripts;        
    }
}