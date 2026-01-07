#!/usr/bin/env bash

set -e

echo "Jekyll cleanup..."
rm -rf _site

echo "Jekyll build..."
bundle exec jekyll build

echo "Starting PHP server at http://localhost:8000"
php -S localhost:8000 -t _site/ router.php