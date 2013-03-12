Illustrated Recent Posts Widget
===============================

Plugin Name: Illustrated Recent Posts Widget  
Plugin URI: http://github.com/elfsternberg/illustrated-recent-posts-wp/  
Description: This plugin creates a fairly limited widget, but the magic in the CSS creates a lovely background for individual posts.  
Version: 0.1.0  
Author: Elf Sternberg  
Author URI: http://www.elfsternberg.com/  
Copyright: 2013 Omaha Sternberg (http://igameradio.com)  
License: GPLv3  
License URI: http://www.gnu.org/copyleft/gpl.html  
Tags: recent posts, images, category, categories, widget, post list, exclude, include  
Tested up to: 3.5

Description
-----------

This is a fairly ordinary extension of the Recent Posts widget.
What's useful about it is that scans each recent post for an image
and, if one is found, adds it to a div at the end of the returned HTML
object.  With a little CSS magic (see the included CSS file), the
image is faded out slightly, like a background, and the text
superimposed above it.  This can be really attractive for gaming and
movie sites.  This effect, or something like it, can be clearly seen
at theverge.com's home page, and I've implemented it for
iGameRadio.com.  

You can limit them by category (for example, "podcasts" or "news").

I wrote this for my wife's website.  Ah, the things we do for love.

To Install
----------

Extract the contents of the zip file to your wp-content/plugins
folder, then activate the plugin through the "Plugins" menu in your
admin center.  You will have to drag-and-drop the widget to a sidebar
using the Widgets admin pages.

Recommendation
--------------

The plugin doesn't work well at all without the included css (see
irpw.css included in this folder).  This will position the content and
the images relative to each other, and that's the effect you're
looking for.  

TODO
----

Implement and understand the caching feature as seen in
default-widgets.php.

Implement a per-post styling filter that allows us to colorize each
image as seen at theverge.com, as well as alter the opacity, depending
upon the user's wishes.

Implement variable display fields (author, date, comment count) on
each article.


