# ROCKHARBOR Theme Base

This WordPress theme contains all of the necessary components for creating a
subsite. Subsites require a few special files for themselves to customize the
colors.

## Usage

### Directory structure

At the very least, the following files should be created.

    /<theme_name>
        /img
            logo.png
            twitter-icon.png
            facebook-icon.png
            favicon.ico
            appicon-114.png
            appicon-144.png
        /libs
            child_theme.php
        functions.php
        style.css

### Required files

#### CSS

The child theme CSS should only contain styles for that child theme that need to
be overridden. This usually just includes colors, but can include styles that
utilize images.

There is a stylesheet called `_style.css` that serves as a template for child
theme css sheets.

#### Including the theme class

Also required is a class within `/libs` called `ChildTheme()`. It must
extend `RockharborThemeBase`. All that needs to be defined are the `$themeOptions`
as explained in the base class.

Next, create a `functions.php` file

    function _include_theme() {
        require_once 'libs/child_theme.php';
        global $theme;
        $theme = new ChildTheme();
        $theme->init();
    }
    add_action('after_setup_theme', '_include_theme');

This is required because WordPress doesn't use OOP and includes files in the
opposite order one would suspect. It is needed to overwrite the global `$theme`.

#### A bit more

There are some images required by the subsites, indicated in the directory
structure.

### Required configuration

Below is a list of the configuration required in the WordPress backend once the
site is created:

- Menus needs to be created and assigned to the Main and Footer navigation areas
- Theme options need to be defined, as they do not fall back on the parent theme
- Site title in Settings > General needs to be defined
- Under Settings > Reading, change the front page to display a static page and
choose your homepage
- Go to Network Admin (top right), then to Settings and make sure subsites are
allowed to upload content

In order to pull Twitter feeds, a Twitter application must be made and the
consumer credentials stored in `wp-config.php`:

    define('TWITTER_CONSUMER_KEY', 'my consumer key');
    define('TWITTER_CONSUMER_SECRET', 'my secret key');

This allows themes to authenticate Twitter users (usually the site's Twitter
account) against the application. The application only needs read permission
as all it does it use Twitter's `/search/tweets` API endpoint.

### Front page

The featured stories area above the fold on the front page is controlled by a
menu area called "featured." To add a feature, add a menu item to the "featured"
area's menu.

## Features

Since each site is a child of the main site and can have any feature it has, you
must dictate what features you would like the subsite to use using the `supports`
key in the `$themeOptions` var. Turning a feature on or off does not affect the
database.

If you pass `$archive == true` in the post type's option var, a shortcode will
automatically be created for that post type, e.g. [staff]. This will embed
the archives for that type using the template `content-$post_type.php`. For single
views, it will use the `single-$post_type.php` template.

The following features are available:

### staff
It also creates various conveniences, including a taxonomy for staff called
"department" and meta boxes to organize the information.

To pull from a specific campus, include the `campus` attribute along with the
WordPress blog id (see a list of the sites to get the id). For example, to pull
all of the staff from the second blog:

    [staff campus="2"]

### message
The message post type contains two taxonomies, "series" and "teachers." Series are
like categories for the messages, and teachers are the speakers who taught the
message. Unlike teachers, only one series can be defined per message.

Aside from the archive-generating `[message]` shortcode, the message post type
comes with a `[single-message]` shortcode that can be used to show a single
message from any campus.

## Specialness

_YAWPH_ (yah-ff)
Yet Another WordPress Hack - A hack to make WordPress work in the way you might
imagine it to, or to work in a way common programming practices would dictate.
While much of the site is made up of YAWPHs, I've only begun to document them
as of this commit.