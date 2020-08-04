<?php
/**
 * Plugin Name: List Github Repositories
 * Plugin URI: https://michaelweiner.org/
 * Description: Display a table with information about all of the public repositories for a specific Github user.
 * Author: Michael Weiner
 * Author URI: https://michaelweiner.org/
 * Version: 0.0.4
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

# Create function to handle calls for Github API transient 
# Used by mw_git_display_function($attr)
function mw_github_api_call_transient($mw_transient_id, $mw_api_url) {
	# establish a new transient and assign it to the return value of trying to get it
	$mw_transient = get_transient($mw_transient_id);
  
	if (!empty($mw_transient)) { # if the transient exists, we just need to return it
        return $mw_transient; 
	} else {
        # go to Github, grab json data from Github, and decode it
        $mw_github_data = wp_remote_get(esc_html($mw_api_url));

        # break early if JSON request ends in error
        if (is_wp_error($mw_github_data)) {
            return ("error");
        }

        # read decoded JSON data into an array
        $mw_github_data_arr = json_decode(wp_remote_retrieve_body($mw_github_data), true);

		# save API response for 15 minutes
        set_transient($mw_transient_id, $mw_github_data_arr, 15 * MINUTE_IN_SECONDS);
        
        return($mw_github_data_arr);
	}
}

# Create function to be called upon [gitlist] shortcode
function mw_git_display_function($attr) {
    // define function prefix
    $mw_prefix = "mw-";

    # array to store valid parameter values for 'order' attribute of Github API
    $mw_github_direction_params = array("asc", "desc");

    # array to store valid parameter values for 'sort' attribute of Github API
    $mw_github_sort_params = array("created", "updated", "pushed", "full_name");

    # array to store shortcode parameters
    $mw_git_display_function = shortcode_atts( array(
        'excl' => '',
        'num' => '30',
        'order' => '',
        'sort' => '',
        'user' => 'mike-weiner',
    ), $attr );

     # strip out ALL spaces as the API URL cannot have any breaks
    $mw_git_display_function['user'] = preg_replace("/\s+/", "", $mw_git_display_function['user']);

    # create array of repos to be ignored from the 'excl' shortcode attribute
    $mw_git_display_function['excl'] = preg_replace("/\s+/", "", $mw_git_display_function['excl']); # strip out ALL spaces from the comma separated list of repos to exclude from the display
    $mw_repos_to_ignore_arr = explode (",", $mw_git_display_function['excl']); # store slugs of repos to exclude in their own array

    # check that user attribute is NOT empty -> if so, set the default to my github username
    if ($mw_git_display_function['user'] == '') {
        $mw_git_display_function['user'] = 'mike-weiner';
    } 

    # get Github username from shortcode attribute
    $mw_github_user_name = $mw_git_display_function['user']; # Github User Name

    # establish JSON request URL
    $mw_github_api_url = "https://api.github.com/users/" . $mw_github_user_name . "/repos";

    # check for valid sort parameter
    if (in_array(strtolower($mw_git_display_function['sort']), $mw_github_sort_params)) {
        $mw_github_api_url = $mw_github_api_url . "?sort=" . $mw_git_display_function['sort']; 

        $mw_url_sort_placeholder = $mw_git_display_function['sort']; # establish placeholder for sort param for use in transient id

    } else {
        $mw_url_sort_placeholder = "full_name"; # establish placeholder for sort param for use in transient id
    }

    # check for valid order parameter
    if (in_array(strtolower($mw_git_display_function['order']), $mw_github_direction_params)) {
        $mw_github_api_url = $mw_github_api_url . "?direction=" . $mw_git_display_function['order'];

        $mw_url_order_placeholder = $mw_git_display_function['order']; # establish placeholder for order param for use in transient id

    } else {
        # set placeholder for order param based on default values specified by Github API
        # see: https://developer.github.com/v3/repos/#parameters
        if ($mw_url_sort_placeholder == "full_name") {
            $mw_url_order_placeholder = "asc";
        } else {
            $mw_url_order_placeholder = "desc";
        }
    }

    # check for valid per_page parameter
    if (is_numeric($mw_git_display_function['num'])) {
        $mw_github_api_url = $mw_github_api_url . "?per_page=" . abs(intval($mw_git_display_function['num'])); 
    }
    
    # create transient id for this particular shortcode call
    $mw_github_transient_id = $mw_prefix . "git-list-" . esc_html($mw_git_display_function['user']) . "-" . esc_html($mw_url_sort_placeholder) . "-" . esc_html($mw_url_order_placeholder) . "-" . esc_html(abs(intval($mw_git_display_function['num'])));

    # grab/create transient
    $mw_github_transient = mw_github_api_call_transient($mw_github_transient_id, esc_html($mw_github_api_url));

    # check if transient is empty -> if so, return early as the user has no public facing repos
    if (empty($mw_github_transient)) {
        return "<html><div class='mw-github-container'>We could not find any public repositories for this user!</div></html>";
    } 

    # check if transient is a String (JSON error ocurred) -> if so, return early as there was an error with the JSON request
    if ($mw_github_transient == "error") {
        return ("<html><div class='mw-github-container'>We're sorry. There appeared to be an error. Please re-evaluate your shortcode and try refreshing the page in 15 minutes.</div></<html>");
    }

    # begin to generate HTML
    $mw_html_output = "<html><div class='mw-github-container'><table>";

    # columns for HTML table
    # this array's keys are the names of the JSON parameters we want to pull out of the array we get back from Github
    # this array's values are the table header row values so we can be consistent with capitalization or re-word the column to be different than the JSON parameter name
    $mw_html_th_cols = array(
        "name" => "Repository",  
        "description" => "Description"
    );

    # generate HTML for header row in table
    $mw_html_table_header_row = "<tr class='mw-github-header-row'>";
    foreach ($mw_html_th_cols as $mw_json_param => $mw_label) {
        $mw_html_table_header_row = $mw_html_table_header_row . "<th class='mw-github-header' class='mw-" . esc_attr($mw_json_param) . "-data'>" . esc_html($mw_label) . "</th>";
    }
    $mw_html_table_header_row = $mw_html_table_header_row . "</tr>";

    # append table header row HTML to output
    $mw_html_output = $mw_html_output . $mw_html_table_header_row;

    # initialize a count variable for the num attribute of the shortcode
    $mw_repo_count = 0;

    # add row to HTML table data for every repo in github_data_dict
    foreach ($mw_github_transient as $mw_repo){

        # check that we have not gone over the number of repos the user wants to list
        if (!($mw_repo_count < abs(intval($mw_git_display_function['num'])))) {
            break;
        }

        # check that the current repo was not specified by the user to be skipped using the 'excl' shortcode attribute -> if so, skip to next repo
        if (!empty($mw_repos_to_ignore_arr) && in_array($mw_repo['name'], $mw_repos_to_ignore_arr)) {
            continue;
        }

        $mw_html_table_row = "<tr class='mw-github-data-row'>"; # open HTML table row tag

        foreach ($mw_html_th_cols as $mw_json_param => $mw_label) { # add every column being requested in html_th_cols
            if ($mw_json_param == "name") {
                $mw_html_table_row = $mw_html_table_row . "<td class='mw-github-table-data mw-" . esc_attr($mw_json_param) . "-data'><a class='mw-github-link' href=\"" . esc_url($mw_repo["html_url"]) . "\" target='_blank'>" . esc_html($mw_repo[$mw_json_param]) . "</td>";
            } else {
                $mw_html_table_row = $mw_html_table_row . "<td class='mw-github-table-data mw-" . esc_attr($mw_json_param) . "-data'>" . esc_html($mw_repo[$mw_json_param]) . "</td>";
            }
        }

        $mw_html_output = $mw_html_output . $mw_html_table_row . "</tr>"; # close HTML table row tag

        $mw_repo_count += 1; # increment repo count
    }

    # close remaining HTML tags
    $mw_html_output = $mw_html_output . "</table></div><html>";

    return $mw_html_output;
}
add_shortcode('gitlist', 'mw_git_display_function'); // Add hook to for custom [gitlist] shortcode
