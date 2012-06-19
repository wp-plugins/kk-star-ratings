=== kk Star Ratings ===


Contributors: bhittani

Donate link: http://wakeusup.com/2011/05/kk-star-ratings/

Tags: star ratings, votings, rate posts, ajax ratings

Requires at least: 3.0

Tested up to: 3.3.2

Stable tag: 1.7.2


kk Star Ratings allows blog visitors to involve and interact more effectively with your website by rating posts.



== Description ==




As the name states, this plugin displays a 5 star ratings in your posts/pages.

It has quite cool hover fade effects and animations.

Version 1.3+ also inludes a widget so you can show top rated posts in your sidebar as well.

Now you can also filter the top rated posts widget by category

Custom template tag/function available

A settings page is also available where you can adjust the settings. You can:


1. Select where to show the ratings. It can be on homepage, archives, posts, pages or manually.

1. Restrict votings per unique ip

1. Choose the placement. Top left, top right, bottom left or bottom right.

1. Adjust the legend (description) of the ratings. 3 variables are avaialable which are [total] (total amount of casted votes), [per] (percentage of ratings) and [avg] (average ratings).

1. Adjust the initial message (when no ratings have occured).

1. Allow the ratings to be visible on its own line (paragraph)

1. Reset the ratings for individual posts or the entire site.




== Installation ==



1. Upload the folder 'kk-star-ratings' found inside the zip to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Adjust the settings under the settings->kk Star Ratings page in wp-admin.



== Frequently Asked Questions ==

= 

Whenever I click on a star, it states "An error occured".

=

This may have occured to some users with previous versions of the plugin. It has been fixed in version 1.5.



== Screenshots ==



1. The settings page


2. The Output


3. The Widget


== Changelog == 

= 1.1 =
* Fixed the [avg] error, so now it will display average ratings properly.

= 1.2 =
* Added possibility to show ratings of any post anywhere in your theme files.

= 1.3 =
* Added a widget. Now you can show top rated posts in your sidebar :).

= 1.3.1 =
* Fixed: flushing/removing of ratings for widget included. Thanks to feedback from glyn.

= 1.4 =
* Added: ability to retrieve top rated posts in the template/theme.

= 1.4.1 =
* Fixed: Settings are now able to be saved. Was not being saved in v1.4.

= 1.5 =
* Fixed: Some users complained about a fault: "An error occured" being displayed when someone rates a post. This was due to the charset of the returned response via ajax (Mentioned by jamk). Has been fixed as the ajax response is now retrieved as an xml dom instead of plain text.
* Fixed: Regardless of unique voting set or not, a user could click on a star multiple times simultaneously and the post would be rated that much time. Has been fixed.
* Added: Filter by category in the widget as well as the custom template tag/function.

= 1.6 =
* Added: Now you can see a column in the admin screen of posts and pages stating the ratings of each.

= 1.7 =
* Update: The top rated posts now considers the vote count as well. This is a recommended update for all users.

= 1.7.1 =
* Security Fix: Fixed a security issue in the ajax request for the file inclusion risk. This is a recommended update for all users.

= 1.7.2 =
* Fix: This is a fix for the previous version (1.7.1). The plugin was not tagged with the latest files. Now it is fixed.

== Upgrade Notice ==