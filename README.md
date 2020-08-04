# List Github Repositories
Contributors: [vikings412](https://profiles.wordpress.org/vikings412/) <br>
Donate Link: https://paypal.me/michaelw13 <br>
Tags: github-api, github-list, github-table, wordpress-table  <br>
Requires at least: 5.0 <br>
Tested up to: 5.5 <br>
Stable tag: 0.0.4 <br>
Requires PHP: 7.0.0 <br>
License: GPLv2 or later <br>
License URI: https://www.gnu.org/licenses/gpl-2.0.html <br>

A custom WordPress plugin that uses a shortcode to display an HTML table with all of the public repositories of a specific Github user.

## Description
This plugin uses a custom shortcode `[gitlist]` to display an HTML table with all of the public facing repositories of a specified Github user (non-authenticated). The repository name (with a link to the repository) and the description of the repository is displayed. 

The display will default to show all of the publicly available repositories for the specified user in descending order by the full name of the repo. You can read more about how you can change what is displayed and how it is displayed using the shortcode attributes listed below.

### Shortcode Attributes
This shortcode comes with a number of different shortcode modifiers to give you greater control over what is displayed and how it is displayed. All of the possible shortcode modifiers are listed and described below. 
* `excl` - A comma separated string to specify slug names of repositories that you would like to exclude from displaying in your table. The default value of ` ` (blank) is used meaning all repositories will be displayed when the parameter is left blank or when it is not included at all. View ***[How do I exclude certain repositories from displaying in a table?](https://github.com/mike-weiner/list-github-repositories#how-do-i-exclude-certain-repositories-from-displaying-in-my-table)*** below if you are unable to find your repository slug name in the URL.
* `num` - A string that contains only an integer value to specify how many repositories you would like to display in the table. The default value of `30` is used when the parameter is left blank or when it is not included at all. This follows the default value set by the Github API. ***Note: The Github API currently has a maximum of 100 repositories per page, so even if you enter a number of 100 only the first 100 repositories will be displayed.***
* `order` - A string that allows you to choose in what order the repositories are displayed. The possible choices are `asc` and `desc`. The default value of `asc` is used when the `sort` parameter is `full_name` (see details on the `sort` parameter below for these scenarios) and `desc` is used for all other scenarios. This follows the default value set by the Github API.
* `sort` - A string allowing you to specify in what way the repositories being displayed are sorted. Options include `created`, `updated`, `pushed`, and `full_name`. The default value of `full_name` is used when the parameter is left blank or when it is not included at all. This follows the default value set by the Github API.
* `user` - A string that takes in the Github username of the user that you would like to display repositories for. The default value of `mike-weiner` is used when the parameter is left blank or when it is not included at all. 

You can find more about the Github API and its parameters here: [https://developer.github.com/v3/repos/#list-repositories-for-a-user](https://developer.github.com/v3/repos/#list-repositories-for-a-user)

## Installation

### From the Github Repository
Go to the [releases](https://github.com/mike-weiner/list-github-repositories/releases) section of the repository and download the `mw-list-github-repositories.[version].zip` from the most recent release.

Once you have downloaded the `mw-list-github-repositories.[version].zip` from the releases section of this repo sign into the backend of your WordPress website. From your WordPress administration panel, go to `Plugins > Add New` and click the gray `Upload Plugin` button at the top of the page. Select the `mw-list-github-repositories.[version].zip` file to upload from your machine when prompted.

WordPress will install the plugin. Once the installation is complete, you will be able to activate the plugin and begin using it! Enjoy! 

If you have any questions or issues please open a [new issue on the Github repository](https://github.com/mike-weiner/list-github-repositories/issues).

## Frequently Asked Questions

### How do I display a table of repositories for a specific user?

First, make sure that the plugin `List Github Repositories` is installed and activated. To check this, click on the `Plugins` option from the left-hand administration sidebar in WordPress. Once the page loads, make sure `List Github Repositories` is activated. 

Then navigate to the page you would like to display the table on. Begin editing the page and paste the `[gitlist user=""]` shortcode onto the page. Within the square brackets specify the Github user name that you would like to generate the table for in between the double-quotes after `user=`. 

You can find your Github username within a Github URL or by selecting your profile picture in the upper right-hand corner of Github's website and copy the name listed under: "Signed in as". ***Do not include any symbols with the Github user name.*** 

### Why is/are my table(s) not updating on every page refresh?

This plugin makes use of a transient, a form of cache. This means all of your Github repository tables only update every 15 minutes on a page refresh. This keep API calls down and decreases the time it takes for your page to load. 

Don't worry! Your table will update! Give it 15 minutes, come back, refresh the page, and you should see the any changes made!

### How do I exclude certain repositories from displaying in my table?

First you will need to find the slug name(s) for the repository(ies) that you would like to prevent from displaying in your table. To do this, go to Github and navigate to the repository you want to exclude. Examine the URL. Your URL should read something similar to: ***...github.com/[username]/[repository slug name]/...***

You want to copy what you see where `[repository slug name]` is into the `excl` shortcode attribute. For example, this repository's URL is: [https://github.com/mike-weiner/list-github-repositories/](https://github.com/mike-weiner/list-github-repositories/). If I wanted to exclude this repo, I would need to copy `list-github-repositories`. ***Only copy what is between the slashes, even if there is more after your repository slug name.***

Now, go to the page that you have/are going to place the shortcode. Within the brackets of your shortcode include the modifier `excl=""`. Between the double-quotes, paste the slug name of the repository that you want to exclude. So, if I wanted to exclude this repository in my table, my shortcode would look like: `[gitlist excl="list-github-repositories"]`. 

If you want to exclude more than one repository, just separate the repository slug names by a comma. For example, if I wanted to exclude more repositories from my table my shortcode would look like: `[gitlist excl="list-github-repositories, example-repo-slug-1, example-repo-slug-2"]`. There are no limits as to how many repositories you can exclude.

### Why are the repositories being displayed not from the correct user?

Navigate to the page where the table is being displayed. Begin editing the page and examine the `[gitlist user=""]` shortcode on the page. Double check that a Github username is entered within the double quotes after `user=` in the shortcode. Please check that this is the correct user name. 

***If you do not include any Github user name within the double quotes OR if you remove `user=""` from the shortcode entirely then the repositories from Github user `mike-weiner` (me) will be displayed.*** 

## Screenshots

## Changelog

### 0.0.4
* Released on August 4, 2020
* Added: Shortcode attribute `excl` allows the user to specify repository slug name(s) (separated by comma) to exclude from being displayed in a table
* Added: A new FAQ *`How do I exclude certain repositories from displaying in a table?`* question has been added covering the new `excl` shortcode attribute and how to use it
* Added: A new *Special Thanks* section to the README with a thank you to [@duplaja]((https://github.com/duplaja))
* Fixed: Restored functionality of the `num` parameter, the Github API is still limit is still 100 
* Updated: Updated README shortcode attribute descriptions
* Updated: Updated appropriate links to use markdown formatting instead of Github's automatic link detection in the README
* Edited: github-repo-listing.php
* Edited: README.md

### 0.0.3
* Released on August 3, 2020
* Added: Transient cache for all Github display tables you have across your site. Each table will update on the first page refresh every 15 minutes
* Added: A new FAQ question has been added covering the transient cache feature
* Updated: Updated README shortcode attribute descriptions
* Fixed: Fixed a typo in changelog for v0.0.2
* Edited: github-repo-listing.php
* Edited: README.md

### 0.0.2
* Released on August 3, 2020
* Added: Shortcode attribute `num` allows the user to specify how many repositories should be displayed. (Note: Current limit from Github API is 100)
* Added: Shortcode attribute `order` allows the user to specify if the repository table should be displayed in `asc` or `desc` order
* Added: Shortcode attribute `sort` allows the user to specify if repositories should be sorted by `created` (date), `updated` (date), `pushed` (date) or by `full_name` (repo name)
* Modified: Shortcode attribute `git-user` has been renamed to `user` for clarity
* Edited: github-repo-listing.php
* Edited: README.md

### 0.0.1
* Released on July 31, 2020
* Initial release

## Upgrade Notice

### 0.0.4
Today's update introduces a new shortcode attribute `excl` that allows you to specify certain repositories to exclude from being displayed in your table! There is also a new *`Special Thanks`* section of the README for those that have provided me with some great resources and help along the way. There are numerous other smaller changes like code optimization, greater commenting, and resource management going on behind the scenes. Enjoy! As always, if you experience any issues please open a new issue on the Github repository and I will be happy to assist. 

### 0.0.3
This update introduces a 15 minute cache for displaying updates on your Github display table. This means your Github table will update on the first page refresh after 15 minutes. This was accomplished through using WordPress transients and will help to keep your page load times down.

### 0.0.2
This release gives the user more control of how the repositories are displayed in the table and how they are sorted via new `order` and `sort` attributes for the `[gitlist]` shortcode. Other shortcodes such as `num` and `user` have been added or modified. Please see the README for information about all of the new options for these shortcode attributes available to you!

### 0.0.1
Initial release!

## Arbitrary section

### Github Repository
The Github Repository can be found here: [https://github.com/mike-weiner/list-github-repositories/](https://github.com/mike-weiner/list-github-repositories/).

### Special Thanks:
A special thank you to user [@duplaja](https://github.com/duplaja) on Github for several helpful repositories, links to the WordPress Codex, and insight that made completing certain functionality successful. 