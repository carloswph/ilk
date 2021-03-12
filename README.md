# Ilk

[![Codacy Security Scan](https://github.com/carloswph/ilk/actions/workflows/codacy-analysis.yml/badge.svg)](https://github.com/carloswph/ilk/actions/workflows/codacy-analysis.yml)

Custom post types and custom taxonomies - one of the must-haves for any WP developer or web designers. They allow Wordpress websites to be turned into platforms, services and entire systems. There are thousands of ways of creating post types and taxonomies, and also a number of helper classes and wrappers. So, why do we need yet another?

Most post type helpers have something in common: they are not maintained. With Ilk, we come with a different proposal - to keep things always up-to-date, considering WP core, and launch new features and tools frequently.

# Installation

Using Composer, of course: `carloswph/ilk`, which is the recommended way.

But you can also clone this repo and require the classes files, located in the `src/`folder, or even autoloading yourself, depending on the script you are using.

# Usage

Yeah, we really try to keep things neat and simple. Ilk uses an Illuminati class named Pluralize, which allows you to submit just the singular post type (or taxonomy) name, and the script finds the equivalent plural in English. Perhaps we add some other languages in the future.

Regarding the logic, it is again pretty simple. All you need to do to create a new post type is instancing the Builder class, with the singular post type name:

```php
$cpt = new Builder('Foot'); // The plural 'Feet' is automatically found and generated

$cpt->enableRest(); // If you want the post type to show in rest (default is false)
$cpt->setSupports('excerpt'); // If you want to add any new feature support
$cpt->setI18n('ilk-rulez'); // Sets a slug for translations, if necessary
```
Other class, TermBuilder, will allow you to create custom taxonomies for those post types you have just built. Logic, again, is quite simple: instatiating the class, using the singular name of the desired taxonomy as first argument and the custom post type class instance as second.

```php
$tax = new TermBuilder('Knife', $cpt); // Again, the plural 'Knives' will be automatically managed

$tax->enableRest(); // Same logic as CPTs
$tax->likeCategory(); // Taxonomies are by default non-hierarchical, but you can make them hierarchical using this method
```

## Other methods

Some other methods can be used. Commonly, we want the new post type menu to appear as submenu in a particular part of the backend. Sometimes, yet, we don't even want a menu to be shown. Both situations can be addressed with this method:

```php
$cpt->setMenu(false); // No post type in backend menu
$cpt->setMenu('options-general.php'); // Post type as submenu for Settings
```

Also, as feature supports can be added anytime, they could be also removed, by using the following method:

```php
$cpt->dropSupports('excerpt');
```
