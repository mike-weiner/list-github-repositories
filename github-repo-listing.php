<?php
/**
 * Plugin Name: List Github Repositories
 * Plugin URI: https://michaelweiner.org/
 * Description: Display a table with information about all of the public repositories for a specific Github user.
 * Author: Michael Weiner
 * Author URI: https://michaelweiner.org/
 * Version: 0.0.1
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// exit plugin if it is being accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Enqueue custom styles for Github listing display table
function mw_custom_github_listing_styles() {
    $mw_plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'mw-github-listing-styles', $mw_plugin_url . 'github-listing-styles.css' );
}
add_action( 'wp_enqueue_scripts', 'mw_custom_github_listing_styles' );

// Create function to be called upon [gitlist] shortcode
function git_display_function( $attr ) {
    # array to store shortcode parameters
    $args = shortcode_atts( array(
        'git-user' => 'mike-weiner',
    ), $attr );

    # instance variables
    $mw_github_user_name = $args['git-user']; # Github User Name
    $mw_github_api_url = "https://api.github.com/users/" . $mw_github_user_name . "/repos"; # Do not change

    # go to Github, grab json data from Github, and decode it
    $mw_github_data = $json = wp_remote_get($mw_github_api_url);

    // break early if JSON request ends in error
    if( is_wp_error( $mw_github_data ) ) {
        return "<div class='mw-github-container'>We're sorry. There appeared to be an error. Please re-evaluate your shortcode.</div>"; // Bail early
    }

    # read decoded JSON data into a dictionary
    $mw_github_data_dict = json_decode(wp_remote_retrieve_body($mw_github_data), true);

    # check if array is empty -> if so, return early
    if (empty($mw_github_data_dict)) {
        return "<div class='mw-github-container'>We could not find any public repositories for this user!</div>";
    }

    # generate HTML
    $mw_html_output = "<html><div class='mw-github-container'><table>";

    # columns for HTML table
    $mw_html_th_cols = array(
        "name" => "Repository",  
        "description" => "Description"
    );

    # generate HTML for header row in table
    $mw_html_table_header_row = "<tr class='mw-github-header-row'>";
    foreach ($mw_html_th_cols as $mw_json_param => $mw_label) {
        $mw_html_table_header_row = $mw_html_table_header_row . "<th class='mw-github-header' class='mw-" . $mw_json_param . "-data'>" . $mw_label . "</th>";
    }
    $mw_html_table_header_row = $mw_html_table_header_row . "</tr>";

    # append table header row HTML to output
    $mw_html_output = $mw_html_output . $mw_html_table_header_row;

    # add row to HTML table data for every repo in github_data_dict
    foreach ($mw_github_data_dict as $mw_repo){
        $mw_html_table_row = "<tr class='mw-github-data-row'>"; # open HTML table row tag

        foreach ($mw_html_th_cols as $mw_json_param => $mw_label) { # add every column being requested in html_th_cols
            if ($mw_json_param == "name") {
                $mw_html_table_row = $mw_html_table_row . "<td class='mw-github-table-data mw-" . $mw_json_param . "-data'><a class='mw-github-link' href=\"" . $mw_repo["html_url"] . "\" target='_blank'>" . $mw_repo[$mw_json_param] . "</td>";
            } else {
                $mw_html_table_row = $mw_html_table_row . "<td class='mw-github-table-data mw-" . $mw_json_param . "-data'>" . $mw_repo[$mw_json_param] . "</td>";
            }
        }

        $mw_html_output = $mw_html_output . $mw_html_table_row . "</tr>"; # close HTML table row tag
    }

    # close remaining HTML tags
    $mw_html_output = $mw_html_output . "</table></div><html>";

    return $mw_html_output;
}
add_shortcode('gitlist', 'git_display_function'); // Add hook to for custom [gitlist] shortcode
