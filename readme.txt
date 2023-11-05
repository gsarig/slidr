=== Slidr ===
Contributors: gsarig
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RTB3APJDMT9UN
Tags: carousel, image carousel, thumbnail slider, slideshow, gallery, image gallery
Requires at least: 4.0
Tested up to: 6.4
Stable tag: 1.4.2
License: GPLv2 or later

A clean, simple, responsive and touch-friendly Carousel with no bells and whistles but plenty of flexibility.

== Description ==

Slidr is a clean, simple, responsive and touch-friendly multi-purpose Carousel. It takes a different approach than most other carousel plugins on that it displays posts or custom posts instead of just gallery photos (but it can show gallery photos as well if you like). Its goal is to be as flexible as possible, allowing you to fully customize its appearance via CSS and override the default HTML output. You can even use it as a "vessel" to display your custom loops which could, practically, contain any content you like, from YouTube videos and Google Maps to Instagram feeds and Tweets!

[View the demo](http://demos.gsarigiannidis.gr/slidr/ "Slidr Demo") | [Custom loop demos](http://demos.gsarigiannidis.gr/loop/ "Custom loop Slidr Demo")

= Features =
* Responsive.
* Touch friendly.
* Can accept variable width - fixed height images.
* You can call it via a shortcode on posts, pages and widgets.
* You can use it instead of the default WordPress gallery. No need to mess with shortcodes - just enable "Slidr for Gallery" and start creating galleries as usual, the WordPress way!
* Supports cycling items (when at the end, clicking "next" loads the first item and so on, simulating a circular move).
* Autoscroll support.
* Customizable queries with more than a dozen of parameters. You can adjust the post type, number of entries, thumbnail size, order and many more.
* You can have different carousels with different queries and different parameters on a single page.
* You can call it conditionally, to load scripts and styles only when needed.
* It can display entries without photos. For example, you can show the titles and excerpts of your recent posts (or custom posts) and style them however you like.
* Using a special function you can override the text output for each item. For example, instead of the item's title and excerpt you can show an icon, some static text or something else.
* You can add your custom class on each shortcode, to style each carousel differently.
* If you are a theme developer, you can pass your own default values through your theme's functions.php for "Slidr for Gallery" option.
* Works on every recent version of all popular browsers (Firefox, Chrome, Internet Explorer, Opera, Safari) and it even supports Internet Explorer 8.
* A custom loop function allows you to use the carousel with your custom loops. That way you can practically use it to display any content you like (videos, content from feeds etc). [View demos](http://demos.gsarigiannidis.gr/loop/ "Custom loop Slidr Demo")

== Installation ==

1. Upload Slidr plugin to your WordPress plugins directory and activate it.
2. Go to Tools > Slidr to customize its options and learn how to use the shortcode.

== Frequently Asked Questions ==

= How can I create a carousel? =

Just go to the page you want to show your carousel and add the [slidr] shortcode. This should create a carousel with the default settings, meaning that it will get all the recent posts and display them in descending order. You can customize the output by overriding the default settings with your own values like so: [slidr parameter="value"]. For a full list of the available parameters, see below.

= [slidr] shortcode options =
* <code>[slidr height="some_number"]</code> : Set the height for the specific carousel, overriding the defaults like so: <code>[slidr height="200"]</code>. That way you can have carousels of different sizes in different pages of your website.
* <code>[slidr loader="no"]</code> : Shows a "loading" animation until all items are loaded. By default it is enabled.
* <code>[slidr cycle="yes"]</code> : If enabled, when the carousel reaches its first or last item, instead of stopping it loads the last or first item respectivelly, simulating a circular move. With <code>[slidr cycle="auto"]</code> you can enable autoscoll, which animates the carousel automatically every 4 seconds.
* <code>[slidr speed="4000"]</code> : Set the autoscroll speed in miliseconds. Default value is 4000ms (4 seconds). This option works only if "cycle" parameter, mentioned above, is set to "auto".
* <code>[slidr nav="hide"]</code> : Completely hides the navigation buttons.
* <code>[slidr gallery="yes"]</code> : Instead of posts, the Carousel can be used in "Gallery mode" displaying the images attached to the post in which you call it. By default it is disabled. You can use gallery mode with specific images, by providing the IDs of those images like so: <code>[slidr gallery="1,2,3"]</code>. If gallery mode is enabled, then other conflicting parameters such as post type or hide thumbnail will be ignored.
* <code>[slidr gallery_link="attachment"]</code> : Whether each item's link should lead to the attachment page or the actual media file if gallery mode is enabled. Default is the Media file. If gallery mode is set to "no" (disabled), then this setting is ignored.
* <code>[slidr type="post_type"]</code> : The post type whose items will be displayed in the carousel (e.g. <code>[slidr type="post"]</code> for posts, <code>[slidr type="page"]</code> for pages etc.). Default value is "post".
* <code>[slidr number=some_number]</code> : The number of items to be displayed. Pay attention to the lack of quotation marks (e.g. <code>[slidr number=15]</code>). Default value is 10.
* <code>[slidr category="category_id"]</code> : The category from which you want to get your items. You should use the categorie's ID like <code>[slidr category="2"]</code>. By default this option is disabled, to get all categories.
* <code>[slidr parent="parent_page_id"]</code> : Display the children of a page. You should use the page's ID like <code>[slidr parent="2"]</code>. By default this option is disabled.
* <code>[slidr sticky="yes"]</code> : By default the plugin doesn't care about whether a post is sticky. To only show sticky posts, though, use <code>[slidr sticky="yes"]</code>).
* <code>[slidr orderby="date"]</code> : The items' order. Default order is by date.
* <code>[slidr order="ASC"]</code> : Whether the order will be ascending (ASC) or descentind (DESC). Default is "DESC" (descending). If the gallery mode is enabled and is set to get specific images, then this order option gets ignored and the images are ordered based on how the user added the image IDs in his/her shortcode.
* <code>[slidr size="thumbnail"]</code> : The thumbnail size, based on the registered sizes of your theme. Default value is "thumbnail". Other options usually include "medium", "large" and "original".
* <code>[slidr thumb="no"]</code> : If you need the carousel to display posts without thumbnails, you can completely disable images. Default value is enabled, of course.
* <code>[slidr info_box="no"]</code> : By default each item shows a box with the title and excerpt on mouseover or tap. With this option you can disable it.
* <code>[slidr excerpt="no"]</code> : Hide the excerpt from the info box.
* <code>[slidr img_link="no"]</code> : Remove the link from each image.
* <code>[slidr class="yourclass"]</code> : If you have carousels in many different pages, there is a chance that you want to style them separately. With this option you can add a custom class at the carousel's outer container and customize it using CSS.
* <code>[slidr link="none"]</code> : Removes the link from the title (in case you want to show the details of an image but not link to its attachment page or media file).
* <code>[slidr template="no"]</code> : Removes the default template for the specific carousel. That way you can keep the default template for all other carousel instances but remove it for those you wish to style differently.

= Replace the default WordPress gallery =
By enabling <strong>"Use Slidr for Gallery"</strong> under the <strong>"WordPress Gallery"</strong> tab, you can replace the default WordPress gallery ([gallery] shortcode) with Slidr. Doing so you will be able to use the default gallery management mechanism of WordPress as usual and the output will be displayed in a Slidr carousel. In a typical use case, you wouldn't need to mess with shortcodes - just create a gallery via the WordPress editor as you would anyway and see the results. For example, you can order set the order via the Gallery Management screen, set the titles and captions of each image and define whether clicking on images should get to the media file, attachment page or do nothing. Keep in mind that in those cases the outputted shortcode is not [slidr] but <strong>[gallery]</strong> and that many of the aforementioned parameters for the [slidr] shortcode don't make sense in a gallery context and won't work there. Those which do work are the following:

* <code>[gallery height="some_number"]</code> : Set the height of the gallery.
* <code>[gallery loader="no"]</code> : Shows a "loading" animation until all items are loaded.
* <code>[gallery cycle="yes"]</code> : If enabled, when the carousel reaches its first or last item, instead of stopping it loads the last or first item respectivelly, simulating a circular move. With <code>[gallery cycle="auto"]</code> you can enable autoscoll, which animates the carousel automatically every 4 seconds.
* <code>[gallery speed="4000"]</code> : Set the autoscroll speed in miliseconds. Default value is 4000ms (4 seconds). This option works only if "cycle" parameter, mentioned above, is set to "auto".
* <code>[gallery nav="hide"]</code> : Completely hides the navigation buttons.
* <code>[gallery orderby="menu_order ID"]</code> : The items' order. Default order is by the order passed at the media manager.
* <code>[gallery order="ASC"]</code> : Whether the order will be ascending (ASC) or descentind (DESC). Default is "ASC" (ascending).
* <code>[gallery size="thumbnail"]</code> : The thumbnail size, based on the registered sizes of your theme. Default value is "thumbnail". Other options usually include "medium", "large" and "original".
* <code>[gallery thumb="no"]</code> : If you need the carousel to display posts without thumbnails, you can completely disable images. Default value is enabled, of course.
* <code>[gallery info_box="no"]</code> : By default each item shows a box with the title and excerpt on mouseover or tap. With this option you can disable it.
* <code>[gallery excerpt="no"]</code> : Hide the excerpt from the info box.
* <code>[gallery img_link="no"]</code> : Remove the link from each image.
* <code>[gallery class="yourclass"]</code> : If you have carousels in many different pages, there is a chance that you want to style them separately. With this option you can add a custom class at the carousel's outer container and customize it using CSS.
* <code>[gallery link="none"]</code> : Removes the link from the title (in case you want to show the details of an image but not link to its attachment page or media file).
* <code>[gallery template="no"]</code> : Removes the default template for the specific carousel. That way you can keep the default template for all other carousel instances but remove it for those you wish to style differently.

= Combinations and alternatives =

You can combine almost all of the above parameters to customize your query. For example, <code>[slidr type="portfolio" height="200" number=5 category="5" size="medium" excerpt="no" class="myworks" cycle="auto" speed="2000"]</code> should create a carousel of 200 pixels height which would display the five most recent items from your "portfolio" custom post type, AND under a specific category with the id of "5". Items' thumbnails should use the "medium" size and no excerpts should be displayed. Finally, this carousel should autoscroll its items every 2000ms (2 seconds) and it should have a custom class "myworks".

If you want to add your carousel directly in your php code, you can use the <code>&lt;?php echo do_shortcode( '[slidr]' ); ?&gt;</code> function, setting parameters the same way as previously described.

= Overriding default values =
If you are a theme developer, you can pass your own default values for the [gallery] shortcode directly in your theme. That way you don't need to instruct your users to go and set the appropriate values at the Plugin's options or ask them to mess with the shortcode. You can use it like this:

<code>
&lt;?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'slidr/slidr.php' ) ) {
	function slidr_gallery_defaults() {
		$default['enable'] 		= 'yes'; 	// Enable or disable Slidr for Gallery option by default
		$default['height'] 		= '500'; 	// The gallery height
		$default['size'] 		= 'medium'; // Thumbnail size
		$default['speed'] 		= '4000'; 	// Carousel speed if "cycle" mode is set to "auto"
		$default['info_box'] 	= 'yes'; 	// Show or hide infobox
		$default['excerpt'] 	= 'no'; 	// Show or hide excerpt
		$default['loader'] 		= 'yes'; 	// Use loading animation
		$default['nav'] 		= 'show'; 	// Show or hide navigation buttons
		$default['cycle'] 		= 'no'; 	// Enable or disable "cycle" mode (options are "yes", "no" and "auto")
		$default['template'] 	= 'no'; 	// Disable the default template
		$default['class']		= 'myclass' // Pass your class to the container
		$default['link_class'] 	= 'myclass' // Pass class to the link
		$default['img_link'] 	= 'no' 		// Enable or disable image link

		return $default;
	}
} ?&gt;
</code>

= Can I have more than one carousels in my website? =

Yes. You can create carousels on any post or page you like. Each one of them can accept different parameters.

= Can I create two carousels on the same page? =

Yes. You can have multiple carousels on the same page.

= Instead of the title and excerpt I want to display my own content / metadata. Can I customize that output myself? =

Perhaps you might want to alter the default display of the title and excerpt in the infobox. In that case, you can override the output by adding the following function in your theme's functions.php:
<code>
<?php function slidr_custom_content( $link, $title, $excerpt, $a ) {
	echo 'Your custom output here';
} ?>
</code>
You can call each item's link, title and excerpt using the <code>$link</code>, <code>$title</code> and <code>$excerpt</code> variables respectively inside your function (in the above example you should use it inside your echo). If you have more than one slidr shortcodes with different attributes for each one, you can run tests using the <code>$a</code> variable. For example, to test if gallery mode is disabled:
<code>
<?php function slidr_custom_content( $link, $title, $excerpt, $a ) {
	if($a['gallery'] !== 'yes') {
		// Do something
	}
} ?>
</code>

= Customizing conditional loading =
To further customize conditional loading of the plugin's resources, put the following in your theme's <strong>functions.php</strong> file:
<code>
<?php function slidr_conditional() {
	if( YOUR_CONDITIONS ) {
		slidr_dequeue();
	}
}?>
</code>
For details on <strong>Conditional Tags</strong> check the [Codex](http://codex.wordpress.org/Conditional_Tags "Conditional Tags in WordPress").

= Slidr in Widgets =
If you want to add a carousel in your WordPress Sidebar Widgets, you need to enable shortcodes in your WordPress sidebar. To do so, paste <code>add_filter('widget_text', 'do_shortcode');</code> in your <strong>functions.php</strong> file. Then all you have to do is go to your <strong>Appearance » Widgets</strong> Screen and create a <strong>text widget</strong>. There you can paste <code>[slidr]</code> (or any shortcode that you have enabled on your site for that matter), and it will function properly.

= Use it with custom php loops =
The plugin contains the <code>slidr_loop()</code> function, which allows you to use your own custom loop instead of the plugin's parameters. That means that you can keep all the existing functionality of the carousel, but the content with which you will fill it is entirely up to you. For example, you could create a carousel displaying videos, instagram photos or any other content that you wish to format in a particular way ([View some demos](http://demos.gsarigiannidis.gr/loop/ "Custom loop Slidr Demo")). Your custom loop can be a typical foreach() php loop and the only thing you need to do is adding the <code>slidr-item</code> class to the container of each of your loop's items. Then, you call the function like this: <code><?php slidr_loop('your_loop_function'); ?></code>
If you want to customize the plugin's parameters, you have the following options available:
<code><?php slidr_loop($loop, $height, $class, $cycle, $loader, $nav, $larrow, $rarrow); ?></code>. Here's a detailed explanation of each parameter:

* <code>$loop</code>: Accepts a function (your custom loop). It is the only required parameter in order for you to have a functional carousel.
* <code>$height</code>: Accepts a string with a number. (e.g. '500'). It sets the carousel's height.
* <code>$class</code>: Aceepts a string with your custom classes (e.g. 'class-1 class-2'). If left empty, it defaults to 'default'.
* <code>$cycle</code>: Options include: true, false, 'auto', 'SPEED_NUMBER_IN_MS'. true enables cycling, 'auto' enables cycling AND auto cycles the slides, 'SPEED_NUMBER_IN_MS' (e.g. '2000') enables cycling AND ayto cycles the slides AND sets your custom cycling speed in ms.
* <code>$loader</code>: Options include: true, false, 'Your custom text'. true enables the loader, false disables it and a custom string (e.g. 'Loading slider...') enables the loader AND sets a custom text message.
* <code>$nav</code>: If set to true or left empty it shows the navigation arrows. If set to false it hides them.
* <code>$larrow</code>: Accepts a string to change the default left arrow. You can add html, font icons, plain text or special characters.
* <code>$rarrow</code>: Accepts a string to change the default right arrow. You can add html, font icons, plain text or special characters.

A full example would be something like that:
<code>&lt;?php echo slidr_loop('my_loop', '500', 'myclass-1 myclass-2', '2000', 'Loading videos...', true, 'previous', 'next'); ?&gt;</code>

The above would set the container height to 500px, add two custom classes, set auto cycle with a speed of 2000ms, change the loader text and change the navigation arrows.

= Why is the design so minimal? =

Slidr's main goal is to be lightweight and flexible, with the minimum possible restrictions when it comes to its styling. It comes with a predefined template which can be decativated for those who want to use their custom design. Therefore, to modify its appearance you need to have at least some knowledge of CSS.


== Screenshots ==

1. The default style of the Carousel, used in the TwentyFifteen theme (that's what you get with no customization at all, just by adding [slidr] in a post/page)
2. The plugin's Settings main page
3. Managing the default settings
4. The documentation tab

== Changelog ==

= 1.4.1 =
* Fixes a PHP 8 error

= 1.4 =
* PHP 7 compatibility
* The category parameter now uses the 'cat' attribute instead of 'category__in', allowing more flexibility (you can get items from multiple categories or get posts from the parent category).
* Fixes a bug where the image URL didn't load the full image but it got the same image size as the thumbnail instead.
* Check WordPress version and set the h2 or h3 tag depending on whether it is v. 4.4 and above or earlier (fixes "Error: jQuery UI Tabs: Mismatching fragment identifier")

= 1.3 =
* NEW: Added the slidr_loop() function, which allows you to use Slidr with your custom loops.
* IMPROVEMENT: Better handling of scrolling distance when clicking on the previous/next arrows (now it scrolls based on the item's width instead of the carousel's height).
* FIX: Removed the deprecated since WordPress 4.3 function wp_htmledit_pre().

= 1.2.1 =
* Small bugfix (properly close a loop in the main shortcode)

= 1.2 =
* NEW: Now you can use Slidr instead of the default WordPress gallery. Just enable "Slidr for Gallery" and start creating galleries as usual, the WordPress way!
* More natural, seamless auto scroll.
* Smarter handling of titles and captions when in gallery mode (if title is empty or is the same as the filename, caption is used in its place).
* More customization options for developers who want to override plugin's functionality directly from their themes.
* Performance improvements and small bug fixes.

= 1.1.2 =
* FIX: When gallery mode is set to "yes" (which gets all of the post's attached media) omit featured image.

= 1.1.1 =
* FIX: On some occassions the carousel's width is one pixel short. When that happens, add the missing pixel.

= 1.1 =
* NEW: Added autoscroll support
* NEW: Added a "loading" spinner to be shown until all items are loaded
* FIX: Fixed a glitch when cycle mode is enabled and the user clicks on the "next" button for the fist time
* FIX: Sanitized number values to prevent breaking the slide if the user enters invalid data (e.g. speed="2000ms" now is the same as speed="2000"

= 1.0.1 =
* Use un-minified scripts, for better performance with 3rd-party minifiers.

= 1.0 =
* First release.

== Upgrade Notice ==

= 1.2 =
Now you can use Slidr instead of the default WordPress gallery. No need to mess with shortcodes. Just enable "Slidr for Gallery" and start creating galleries as usual, the WordPress way!

= 1.1 =
Added autoscroll support, loading animation and some minor fixes.

= 1.0.1 =
Minor fix: Use un-minified scripts, for better performance with 3rd-party minifiers.

= 1.0 =
Initial submittion to the WordPress.org repository
