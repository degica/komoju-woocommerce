# Uploading new versions of the plugin to the WordPress store

Here is how you upload a new version.

1. Update the `Version: <version>` in `index.php`.
2. Add a changelog entry for the new version to `readme.txt`.
3. (optional) Update version numbers strewn about various php files (like `class-wc-gateway-komoju.php`).
4. Run `git tag <your new version>`.
5. Run `git push --tags`.
6. [GitHub Actions](https://github.com/degica/komoju-woocommerce/actions) should deploy for you.

One thing to note: as of writing, the deploy action does _not_ depend on the test action passing. You should make sure tests pass before running `git push --tags` - or maybe change the actions so that deployment depends on tests!
