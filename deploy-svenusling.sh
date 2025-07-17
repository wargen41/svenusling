#!/bin/bash

# This is to be run via SSH on the web server

# Check if the domain argument is provided
if [ -z "$1" ]; then
  echo "Usage: $0 <domain>"
  exit 1
fi

# Assign the first argument to the domain variable
domain=$1

# Navigate to the domain directory
cd ~/domains/"$domain" || { echo "Error: The domain '$domain' does not exist."; exit 1; }

# Download the zip file
wget https://github.com/wargen41/svenusling/archive/main.zip

# Unzip files in the site directory
unzip main.zip "svenusling-main/site/*"

# Move files to public_html
# Save backups of the earlier version
mv -b svenusling-main/site/* public_html

# Remove unnecessary directories and files
rmdir svenusling-main/site
rmdir svenusling-main
rm main.zip
