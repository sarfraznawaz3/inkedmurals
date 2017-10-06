# Webdam Asset Chooser WordPress plugin (v1.2.1)

The Webdam Asset Chooser for WordPress allows you to embed assets from your Webdam account directly into WordPress pages or posts.

## Install
1. Download the repository as a zip file (It's not yet on the WordPress plugins repo)
2. Within your WordPress admin, navigate to Plugins > Add New.
3. Click Upload Plugin.
4. Select the webdam.wordpress.assetchooser.zip file and then select Install Now.
5. Activate the plugin.
6. Visit Settings > Webdam
7. Enter your Webdam Domain
8. Optionally enable the Webdam API integration (see below for details)

### Enabling the Webdam API integration
Enabling the Webdam API integration allows your users to bypass the Asset Chooser login prompt, logging in all users with the same account. When enabled, the API also provides an option to store your chosen assets in your WordPress Media Library. To enable the API integration select 'Enable Webdam API Integration' in your WordPress Settings > Webdam section—then follow the prompts to enter your credentials and authenticate your website to use your Webdam account.

## Adding Assets from Webdam
When editing a post/page, you will see a Webdam icon in the editor toolbar.

1. To insert assets, click on the Webdam icon.
3. Select the asset(s) you would like to insert.
4. Choose which size thumbnail you would like to embed (1280, 550, 310, 220, or 100px).
5. Click Insert.

*Note: The following asset types are not embeddable through the plugin: .doc, .docx, .xls, .xlsx, .ppt, .pptx, .pdf, .indd., .swf, .ogg, .qxd, .qxp, .svg, .svgz, .otf, .sit, .sitx, .rar, .txt, .zip, .html, .htm.*

## Changelog

* 1.2.1
  * Allow selecting multiple assets by default
* 1.2.0
  * Optionally sideload selected asset into your WP media library
  * Initial Webdam API Integration
  * Fetch sideloaded asset metadata from the Webdam API
  * User interface improvements
* 1.0
  * Initial Release.

## LICENSE
Copyright 2016 Webdam

Licensed under the Wordpress License (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

https://wordpress.org/about/gpl/

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.