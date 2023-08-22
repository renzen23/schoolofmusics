#!/bin/bash

# Paths relative to root.

# WordPress.
vendor/bin/wp core install --path=$WP_WEBROOT --url=localhost:8000 \
  --title=wordhat --skip-email \
  --admin_email=wordpress@example.com \
  --admin_user=admin --admin_password=password

# Sane defaults.
vendor/bin/wp theme activate --path=$WP_WEBROOT twentyseventeen
vendor/bin/wp rewrite structure --path=$WP_WEBROOT '/%year%/%monthnum%/%postname%/'
vendor/bin/wp plugin install disable-gutenberg --path=$WP_WEBROOT --activate

# The default widgets often repeat post titles and confuse Behat.
for sidebar in $(vendor/bin/wp sidebar list --path=$WP_WEBROOT --format=ids); do
  vendor/bin/wp widget list $sidebar --path=$WP_WEBROOT --format=ids

  for widget in $(vendor/bin/wp widget list $sidebar --path=$WP_WEBROOT --format=ids); do
    vendor/bin/wp widget delete --path=$WP_WEBROOT $widget
  done;
done;
