#!/bin/bash

# This is to be run via SSH on the web server

# Intermediate version which only deploys a test version of the API

# Set source directory to use (without trailing slash)
source_dir="svenusling-main/api/test"

# Check if the domain argument is provided
if [ -z "$1" ]; then
  echo "Usage: $0 <domain>"
  exit 1
fi

# Assign the first argument to the domain variable
domain=$1

set -euo pipefail

# Navigate to the domain directory
cd ~/domains/"$domain" || { echo "Error: The domain '$domain' does not exist."; exit 1; }

# Download the zip file
wget https://github.com/wargen41/svenusling/archive/main.zip

# Unzip files in the site directory
unzip main.zip "$source_dir/*"

# Move files to their respective homes
rsync -av "$source_dir/public/" public_html/
rsync -av "$source_dir/src" ./
mv "$source_dir/composer.json" ./

# Remove temporary directories and files
rm main.zip
rm -r svenusling-main

# Kör composer update ifall något har ändrats i composer.json
../../composer.phar update

echo "Klart!"
echo "Glöm inte att config.php och make_admin.php måste uppdateras manuellt vid behov"
