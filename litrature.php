<?php

    /* 
    Plugin Name: Litrature
    Plugin URI: 
    Description: Plugin to manage literature and literature tables
    Author: Alex
    Version: 3.0
    Author URI:
    */ 

add_action( 'admin_menu', 'menu' );

function menu() {
    add_action( 'admin_init','your_css_and_js');

    add_menu_page( 'Literature', 'Literature', 'manage_options', 'litrature.php', '_page', 'dashicons-admin-page', 10 );
    add_submenu_page( 'litrature.php', 'Unassigned Literature', 'Unassigned Literature', 'manage_options', 'unassigned.php', 'unassigned' );

    //add_submenu_page( 'litrature.php', 'New Table', 'New Table', 'manage_options', 'new_table.php', 'new_table' );
    
    add_submenu_page( null, 'New Table', 'New Table', 'manage_options', 'new_table.php', 'new_table' );
    add_submenu_page( null, 'New Literature', 'New Literature', 'manage_options', 'new_lit.php', 'new_lit' );
    add_submenu_page( null, 'Update PDF', 'Update PDF', 'manage_options', 'update_pdf.php', 'update_pdf' );
    add_submenu_page( null, 'Edit Litrature', 'Edit Litrature', 'manage_options', 'edit_lit.php', 'edit_lit' );
    add_submenu_page( null, 'New PDF', 'New PDF', 'manage_options', 'new_pdf.php', 'new_pdf' );
}

function your_css_and_js() {
    wp_register_style('Litrature_Plugin', plugins_url('styles.css',__FILE__ ));
    wp_enqueue_style('Litrature_Plugin');
    wp_register_script( 'Litrature_Plugin', plugins_url('scripts.js',__FILE__ ));
    wp_enqueue_script('Litrature_Plugin');
}

//Page to show the 
function _page(){
    include('includes/litrature_page.php');
}
function unassigned(){
    include('includes/db_conn.php');
    include('includes/unassigned.php');
}
function new_lit(){
    include('includes/db_conn.php');
    include('includes/new_lit.php');
}
function new_table(){
    include('includes/db_conn.php');
    include('includes/new_table.php');
}
function update_pdf(){
    include('includes/db_conn.php');
    include('includes/update_pdf.php');
}
function edit_lit(){
    include('includes/db_conn.php');
    include('includes/edit_lit.php');
}
function new_pdf(){
    include('includes/db_conn.php');
    include('includes/new_pdf.php');
}

function myplugin_activate() {
    $upload = wp_upload_dir();
    $base_dir = $upload['basedir'];
    $upload_dir = $base_dir . '/pdf_drafts';
    if (! is_dir($upload_dir)) {
       mkdir( $upload_dir, 0700 );
    }
    $upload_dir = $base_dir . '/pdf_live';
    if (! is_dir($upload_dir)) {
       mkdir( $upload_dir, 0700 );
    }
    $upload_dir = $base_dir . '/pdf_unassigned';
    if (! is_dir($upload_dir)) {
       mkdir( $upload_dir, 0700 );
    }
}
 
register_activation_hook( __FILE__, 'myplugin_activate' );

function tables_install () {
   global $wpdb;
   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   
   // $sql = "CREATE TABLE IF NOT EXISTS lit_group ( 
   //          group_id int(11) NOT NULL AUTO_INCREMENT,
   //          group_name varchar(255) NOT NULL,
   //          caption varchar(255) NOT NULL,
   //          draft tinyint(1) NOT NULL,
   //          PRIMARY KEY (group_id)
   //      )";
   // dbDelta( $sql );

   $sql = "CREATE TABLE IF NOT EXISTS file (
            file_id int(11) NOT NULL AUTO_INCREMENT,
            pdf_name varchar(255) NOT NULL,
            caption varchar(255) NOT NULL,
            filename varchar(255) NOT NULL,
            last_updated date NOT NULL,
            draft tinyint(1) NOT NULL,
            PRIMARY KEY (file_id)
        )";
   dbDelta( $sql );

   $sql = "CREATE TABLE IF NOT EXISTS group_file_link (
            group_id int(11) NOT NULL,
            file_id int(11) NOT NULL,
            priority int(11) NOT NULL,
            PRIMARY KEY (file_id, group_id),
            FOREIGN KEY (group_id) REFERENCES lit_group(group_id),
            FOREIGN KEY (file_id) REFERENCES file(file_id)
            )";
   dbDelta( $sql );
}

register_activation_hook( __FILE__, 'tables_install' );

?>