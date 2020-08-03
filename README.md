# List Github Repositories
Contributors: [vikings412](https://profiles.wordpress.org/vikings412/) <br>
Donate Link: https://paypal.me/michaelw13 <br>
Tags: github-api, github-list, github-table, wordpress-table  <br>
Requires at least: 5.0 <br>
Tested up to: 5.5 <br>
Stable tag: 0.0.1 <br>
Requires PHP: 7.0.0 <br>
License: GPLv2 or later <br>
License URI: https://www.gnu.org/licenses/gpl-2.0.html <br>

A custom WordPress plugin that uses a shortcode to display an HTML table with all of the public repositories of a specific Github user.

## Description
This plugin uses a custom shortcode `[gitlist git-user=""]` to display an HTML table with all of the public repositories of a specified Github user. The repository name (with a link to the repository) and the description of the repository is displayed.

## Installation

### From the Github Repository
Go to the [releases](https://github.com/mike-weiner/list-github-repositories/releases) section of the repository and download the `mw-list-github-repositories.[version].zip` from the most recent release.

Once you have downloaded the `mw-list-github-repositories.[version].zip` from the releases section of this repo sign into the backend of your WordPress website. From your WordPress administration panel, go to `Plugins > Add New` and click the gray `Upload Plugin` button at the top of the page. Select the `mw-list-github-repositories.[version].zip` file to upload from your machine when prompted.

WordPress will install the plugin. Once the installation is complete, you will be able to activate the plugin and begin using it! Enjoy! 

If you have any questions or issues please open a [new issue on the Github repository](https://github.com/mike-weiner/list-github-repositories/issues).

## Frequently Asked Questions

### How do I display a table of repositories for a specific user?

First, make sure that the plugin `List Github Repositories` is installed and activated. To check this, click on the `Plugins` option from the left-hand administration sidebar in WordPress. Once the page loads, make sure `List Github Repositories` is activated. 

Then navigate to the page you would like to display the table on. Begin editing the page and paste the `[gitlist git-user=""]` shortcode onto the page. Within the square brackets specify the Github user name that you would like to generate the table for in between the double-quotes after `git-user=`. 

You can find your Github username within a Github URL or by selecting your profile picture in the upper right-hand corner of Github's website and copy the name listed under: "Signed in as". ***Do not include any symbols with the Github user name.*** 

### Why are the repositories being displayed not from the correct user?

Navigate to the page where the table is being displayed. Begin editing the page and examine the `[gitlist git-user=""]` shortcode on the page. Double check that a Github username is entered within the double quotes after `git-user=` in the shortcode. Please check that this is the correct user name. 

***If you do not include any Github user name within the double quotes OR if you remove `git-user=""` from the shortcode entirely then the repositories from Github user `mike-weiner` (me) will be displayed.*** 

## Screenshots

## Changelog

### 0.0.1
* Released on July 31, 2020
* Initial release

## Upgrade Notice

### 0.0.1
Initial release!

## Arbitrary section

### Github Repository
The Github Repository can be found here: https://github.com/mike-weiner/list-github-repositories/.
