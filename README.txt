=== CookieFirst Cookie Consent Banner (GDPR/CCPA Compliant) ===
Contributors: cookiefirst
Donate link: https://cookiefirst.com
Tags: gdpr, cookie notice, cookie consent, cookie banner, eu cookie law
Requires at least: 5.0.0
Tested up to: 6.7
Stable tag: 4.3
Requires PHP: 5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin integrates the CookfieFirst cookie consent manager to your Wordpress website.

== Description ==

## CookieFirst Consent Manager 

Note: Simply installing this plugin does not make your website GDPR compliant. In order to use this plugin
you need an active subscription at [CookieFirst.com](https://cookiefirst.com).

## What is CookieFirst? 

CookieFirst.com offers GDPR and ePrivacy cookie management software. This plugin helps novice users to implement the cookie banner
and updated cookie declaration on their websites. Our solution offers periodic cookie scans and an automated cookie policy generator in 44+ languages. 

== Key Features == 

– **Cookie consent banner**: Add the CookieFirst banner to your website. You need an account on [CookieFirst](https://app.cookiefirst.com/register)
– **Customization**: design your cookie banner in our app to match your brand 
– **Automatic cookie scanning**: Scan your website monthly and receive a report for the cookies and trackers found
– **Support for Microsoft and Google Consent Mode**: CookieFirst is a Gold partner for Google Consent Mode.
– **Multilingual websites**: inherit the website language, the CookieFirst banner supports over 44 languages
– **Consent logging**: Your users' consents are safely stored in the European Union. 
– **Automated Cookie Policy**: Easily embed the cookie policy on your website with Gutenberg or a shortcode [cookiefirst_declaration]

 == The CookieFirst cookie banner plugin only uses the following cookies/localstorage  ==

– "cookiefirst-consent" – This cookie and local storage item stores the consent preference of the user
– "cookiefirst-id" – This local storage item stores the users unique ID which can be used to retrieve the consent from our database

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Login to your WP dashboard
2. Go to the **Plugins** section on the left menu bar
3. Click on the **Add New** button at the top of the page
4. Search for the **CookieFirst** plugin
5. Click on the **Install now** button and go to **Settings > CookieFirst**
6. Login to your [CookieFirst account](https://app.cookiefirst.com)
7. Copy your domain api key under "Your embed script" within your **domain settings**
8. Choose whether your website adheres to the "GDPR"
9. Save settings  

To embed the cookie declaration to your website, simply put this shortcode in your Wordpress editor [cookiefirst_declaration] or if you use the Gutenberg block

== Frequently Asked Questions ==

= Does this plugin make my site compliant? =

The short asnwer is No, not immediately. By default this plugin adds the CookieFirst cookie banner to your website. Also this integrates with the Wordpress Consent API and all plugins that use this api. See our [support area](https://support.cookiefirst.com) for more integration guides for Google Tag Manager.

= Does this plugin support Consent Mode =

Yes the CookieFirst platform supports Consent Mode.

= How much does CookieFirst cost? =

For the most recent pricing please refer to the [website](https://cookiefirst.com/#pricing).

= Are my consents stored in a database? =

Yes your website visitor consent data is stored safely in the CookieFirst databases in Europe.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory taxke precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Upgrade Notice ==

= 2.0.0 =
*Important:* This is the last update before we switch to our new version which can be downloaded over the Wordpress Plugin section.

== Admin Notice ==
*Important:* This is the last update before we switch to our new version which can be downloaded over the Wordpress Plugin section.

= 2.0.0 =

== Changelog ==

= 2.0.0 =

* Major update adding support for the Wordpress Consent Api . 
* Allow also for async or deferred loading of cookie banner.

= 1.0.3 =

Added ability to use shortcode [cookiefirst_declaration] to embed the cookie policy page.

= 1.0.2 =

Update readme file removed some unnecessary texts

= 1.0 =
* First version. Allows to add the cookie banner to your site by adding the domain api key.
