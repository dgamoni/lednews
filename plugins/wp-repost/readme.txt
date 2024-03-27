=== WP Repost ===
Contributors: OneWebsite
Donate link: http://www.onewebsite.ca
Tags: revive, old posts, tweet, repost, lazy blogging, scheduled post
Requires at least: 3.4
Tested up to: 4.0
Stable tag: 0.1
License: GPLv2 or later

Keep your old posts alive by sending them to your front page, and promoting them through Twitter, Facebook and LinkedIn automatically.

== Description ==

= WP Repost =
WP Repost is a WordPress plugin that will keep your old posts alive by automatically promoting them back to your front page and RSS feeds, and sharing them through your social networks. The eligible posts, schedule and frequency of re-posting and sharing can be fully customized to your preference and needs. See complete lists of features below. 

= NOT COMPATIBLE WITH PERMALINK STRUCTURE THAT HAS DATES =

= Configurable Posts Eligibility for Re-Posting: =

* Repost old posts randomly.
* Limit posts for 're-posting' based on age. 
* Exclude posts categories from 're-posting'.
* Configure status of re-posted posts.
* Display the post's original publication date.

= Configurable Re-Post Schedule, Interval, and Frequency: =

* Set 're-posting' time interval.
* Set schedule for 're-posting'.
* Set specific posts for 're-posting'.
* Set 're-posting' schedule to repeat (Google Calendar like Interface).

= Social Network Promotion: =

* Optionally tweet promoted posts.
* Share new and old posts.
* Set time interval between 're-post' and sharing.
* Set number of posts to share.
* Use hashtags to focus on topics.
* Include links back to your site.
* Exclude posts to share by categories.
* Exclude specific posts to share.

= Upcoming Features =

* Edit Flow Integration – Calendar, Status Triggers
* Re-Post Conditionals - Repost when certain criteria were met
* Configurable Stream Visibility

== Installation ==

1. Upload 'wprepost.zip' to the '/wp-content/plugins/' directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Optionally adjust the options

== Frequently Asked Questions ==


= Will the re-posted post be considered as duplicate content? =

No since the publication timestamp will change.


= Since the timestamp will change, will that affect my permalinks if it include dates? =

Yes it will. The plugin will not work on permalink structures that includes dates.


= How do I remove dates from my url/permalinks? =

By going to  Settings -> Permalinks and selecting Post name.


= The plugin does not share posts on social networks on regular or set intervals? =

The plugin uses wp_cron function to automatically share your posts, and it only triggers when someone visits the site. If no one visits your site, the auto share will not trigger.


= I am getting a WP CRON error? =

The plugin uses WordPress' WP CRON for the automation features of the plugin. This error could be caused by several factors, but most of the time the issue is with your web server not working with the native WP CRON and not the plugin. 

Steps to troubleshoot:

* Is the 're-post' feature working? 
* Are you hosted on Heart Internet? wp_cron does not work on their servers.
* Try adding define('ALTERNATE_WP_CRON', true ); to your wp-config.php` and see if the plugin will work.
* Is your website working behind any (.htaccess) authentication or maintenance plugin? If so, please remove it and test if the plugin will work. 

If you have tried all the steps above but is still getting an error, please feel free to contact support.


= The plugin does not post any tweets? =

* Try setting maxtweetage to none and try again.
* Try removing all categories from excluded option.


= I sometimes get the error message  "code":226,"message":"This request looks like it might be automated. To protect our users from spam and other malicious activity, we can’t complete this action right now. Please try again later" =

This message comes from Twitter's spam protection feature that blocks and marks 'automatically written or generated' content as spam. This does not mean that your message is spam as it is not automatically generated, but automatically posted. 

* Try to change the format of your messages.
* Try using/changing URL shortener.

There are also reports that Twitter blocks hosting providers that sends them spam regularly.


== Screenshots ==

= Plugin Admin Settings Page =

1. Screenshot 1 WP Repost general options page
2. Screenshot 2 WP Repost integrations page
3. Screenshot 3 Wp Repost Basic configurable options for Tweet Old Post to function
4. Screenshot 4 Wp Repost Single post re-posting options (Metabox)

== Changelog ==

**v0.1**

* Enable NEW features, to set reposting on a PER POST basis
* Meta Box Data Views
* SVN Repo and WP Repo
* Admin Table Column ⁃ Repost Date
* Check for existing loaded xmlrpc.inc before loading

== Upgrade Notice ==

Upgrade Notice