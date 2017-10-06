=== Webdam Asset Chooser ===
Contributors: Webdam, amit, pmcdotcom, jamesmehorter
Plugin URI: https://github.com/shutterstock/Webdam-Wordpress-Asset-Chooser
Requires at least: 3.5
Tested up to: 4.1
Version: 1.2.1

== Description ==
The Webdam Asset Chooser for Wordpress allows you to embed assets from your Webdam account directly into Wordpress pages or posts.

== Installation ==
1. Install directly in your WordPress Plugins > Add New menu
2. Activate the plugin.
3. Visit Settings > Webdam
4. Enter your Webdam Domain
5. Optionally enable the Webdam API integration (see below for details)

= Enabling the Webdam API integration =
Enabling the Webdam API integration allows your users to bypass the Asset Chooser login prompt, logging in all users with the same account. When enabled, the API also provides an option to store your chosen assets in your WordPress Media Library. To enable the API integration select 'Enable Webdam API Integration' in your WordPress Settings > Webdam section—then follow the prompts to enter your credentials and authenticate your website to use your Webdam account.

= Adding Assets from Webdam in a Post =
When adding a new post or editing an existing post, you will see a Webdam icon in the toolbar that appears above the post:

1. To insert assets, click on the Webdam icon.
3. Select the asset(s) you would like to insert.
4. Choose which size thumbnail you would like to embed (1280, 550, 310, 220, or 100px).
5. Click Insert.

Note: The following asset types are not embeddable through the plugin: .doc, .docx, .xls, .xlsx, .ppt, .pptx, .pdf, .indd., .swf, .ogg, .qxd, .qxp, .svg, .svgz, .otf, .sit, .sitx, .rar, .txt, .zip, .html, .htm.

== Copyright ==
Copyright 2014 - 2016 Webdam

= LICENSE =

Licensed under the Wordpress License (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

https://wordpress.org/about/gpl/

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

== Changelog ==

= 1.2.1 =
* Allow selecting multiple assets by default

= 1.2.0 =
* Optionally sideload selected asset into your WP media library
* Initial Webdam API Integration
* Fetch sideloaded asset metadata from the Webdam API
* User interface improvements

= 1.0 =
* Initial Release.