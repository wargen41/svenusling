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

# Create backup copy of public_html
tar -czvf public_html-backup.tar.gz public_html

# Move files to public_html
# Save backups of the earlier version
rsync -av --backup --suffix=~ svenusling-main/site/ public_html/

# Remove temporary directories and files
rm main.zip
rm -r svenusling-main

echo "Done!"
