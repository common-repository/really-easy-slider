=== Really Easy Slider ===
Contributors: chrisguitarguy
Donate link: http://www.christopherguitar.net/
Tags: content slider, slider, jquery, easy slider
Requires at least: 3.1.3
Tested up to: 3.1.3
Stable tag: 0.1

Really Easy Slider quickly adds content sliders to posts and pages via a shortcode

== Description ==

Really Easy Slider is a simple, effective content slider that can be added to any post or page via a shortcode.  The plugin uses the [Easy Slider](http://cssglobe.com/post/5780/easy-slider-17-numeric-navigation-jquery-slider "Easy Slider") jQuery plugin and the TimThumb image resizer script to make everything work. 

The plugin includes an options page on the WordPress backend that lets you specify the pause between slides, the animation speed, numeric or prev/next controls, and whether or not the slider starts automatically or runs continuously.  Additionally, advanced users can disable the built in stylesheet completely or change some CSS selectors to customize their sliders as they like.

Really Easy Slider only loads the slider scripts and styles on pages where the [reslider] shortcode is present.

== Installation ==

1. Download and unzip the `really-easy-slider.zip` file
2. Upload the `really-easy-slider` folder to your plugins directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Add the `[reslider]` shortcode to any post or page!

== Frequently Asked Questions ==

= Why can't I have multiple sliders on each page? =

To keep things simple Really Easy Slider uses one `id` html attribute for all sliders.  Having more than one on each page causes unexpect results, especially with the slider controls.   

= My sliders aren't working on my blog's home page! Why!? =

RE slider doesn't load scripts or styles unless it's sure you need them.  This includes making sure that the post contains the `[reslider]` shortcode.  On the index page, with multiple posts, the plugin can't tell if a slider is needed.  Thus, it doesn't load anything.


== Screenshots ==

1. The general options panel
2. Advanced options
3. Restore the default settings
4. Contextual help
5. The Result!

== Changelog ==

= 0.1 =
* The first release!

== Upgrade Notice ==
n/a
