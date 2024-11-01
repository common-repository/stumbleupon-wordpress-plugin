=== StumbleUpon WordPress plugin ===
Contributors: edward de Leau
Donate link: http://edward.de.leau
Tags: stumbleupon, stumble
Requires at least: 2.0
Tested up to: 3.0
Stable tag: 2.1

Wonderful plugin which shows the popularity of ALL your links and gives
you can active StumbleUpon bookmark button.

== Description ==

Homepage: http://edward.de.leau.net/stumble-info-icon-plugin

This plugin:

 - show an icon behind each link on your blog indicating if you link only stuff that the rest of world also posts or if you truly link some original stuff.
 - it also has a function to produce a "bookmark icon" for stumble-upon. Not a "static" one, but one that truly lights up if it is in the kingdom of stumbleupon.
 - it also has a widget for producing the bookmark icon on your permalinks

In the administration panel you will find the instructions on:

 - showing the icon or text
 - different icon sets
 - different texts
 - option to only use the bookmark button icon
 - option to use your own stylesheet and buttons

Use :
stumbleInfoButton(get_permalink(),get_the_title(),'submit') to get the
specific bookmark button in your template when the widget is not
"enough"

Version History:

  -	v.0.1 primary version
  -	v.0.2 skipped .zip, .pdf
  -	v.0.3 added colors to the icon: grey if not in su, colored if it is
  -	v.0.4 changed to use local rss reader and cache instead of my server!
  -	v.0.5 bugfixed
  -	v.0.6 distinction between 0 and -1.
  -	v.0.7 several fixes
  -	v.0.8 added seperate function to use in your websites
  -	v.0.9 added text, icon, admin panel changes, new supericon
  -	v.1.0 single button, dark buttons, own stylesheet
  -	v.1.1 changed directory structure for auto update purposes
  -	v.1.2 super icon not shown, super is now 8 reviews
  - v.1.3 garbled by subversion somehow it thought this was 0.9
  - v.1.4 doc changes
  - v.1.5 updated to use register settings
  - v.1.6 after svn problems
  - v.1.7 added amount of Reviews also
  - v.1.8 build display icon fix
  - v.1.9 code now oo, temporarily removed widget
  - v.2.0 fixes missing callback quick bugfix
  - v.2.1 bugfix
  
Issues:

  - Can be submitted here: http://code.google.com/p/wp-su-plugin/issues/list


== Installation ==

1. download the plugin
1. copy the complete directory in your plugin dir
1. activate the plugin in WordPress
1. go to the admin panel and tick “show icon”, this will make the background icon visible. (else you will just see a number behind your urls) and just read what is written on there
1. if you want to place a single icon + submit function e.g. sidebar for permalinks or under blogspostings use the function stumbleInfoButton. All the info is in the admin panel.
1. go to you design panel to add the widget if you want it

== Screenshots ==

1. The 3 possible items which will appear behind your links

== Contact Info ==

contact info:
http://edward.de.leau.net/contact


