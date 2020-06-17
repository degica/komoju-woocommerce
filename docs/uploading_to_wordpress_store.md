# Uploading new versions to the WordPress store

This document details how to release a new version of the plugin on the WordPress store. This is intended for Komoju developers and requires access to the company's WordPress account.

## Prerequisites

Before you start, make sure to have the SVN cli tools installed. If you are on a Mac, then you can install them with Homebrew: `brew install svn`

Also make sure have checked out the version of the code you wish to release, and have increased the version in the [`index.php` file](../index.php#6) and have added a note in the [`changelog` section of the readme.txt](../readme.txt).

## Uploading

**Note:** Because we use this Github repository as the development source we aren't using the `tags/` directory in the SVN repository,  `trunk/` is considered the stable, most recent version, so only upload a new release that you intend for users to download.

1. Download the SVN repository from WordPress. I suggest downloading it next to this repo for convenience:
```bash
svn co https://plugins.svn.wordpress.org/komoju-japanese-payments
```
1. Copy the files to the `trunk/` directory:
```bash
$ cd komoju-woocommerce
$ cp -R MIT-LICENSE README.md readme.txt languages/* includes/* assets/* class-wc-gateway-komoju.php changelog.txt index.php uninstall.php ../komoju-japanese-payments/trunk
```
1. If this release includes any new files you will need to add them to SVN (this is similar to `git add`):
```bash
svn add trunk/*
```
1. Commit the changes to the SVN repository:
**Note** `$DEGICA_USERNAME` and `$DEGICA_PASSWORD` can be found in the companies 1Password vault. Make sure the commit message is meaningful as it will be displayed to our users.
```bash
svn ci -m "My commit message" --username $DEGICA_USERNAME --password $DEGICA_PASSWORD
```

## Updating the store assets

WordPress allows us to include assets with the SVN repository to display on the store front, such as icons and banners. To ensure that this Git repository remains the source of truth all these assets have been included in the `docs/store-assets` directory.

By WordPress' convention all the assets need to be in the `assets` directory of the SVN repository and follow specific naming and size conventions. [Read this document](https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/) to learn more.

If adding new assets make sure to correctly set the MIME type as shown [here](https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/#issues)

