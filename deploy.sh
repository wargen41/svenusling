#!/bin/bash

# Check if the domain argument is provided
if [ -z "$1" ]; then
  echo "Usage: $0 <domain>"
  exit 1
fi

# Assign the first argument to the domain variable
domain=$1

# Navigate to the domain directory
cd ~/domains/"$domain" || exit

# Download the zip file
wget https://github.com/wargen41/svenusling/archive/main.zip

# Unzip specific files
unzip main.zip "svenusling-main/site/*"

# Move files to public_html
mv -f svenusling-main/site/* public_html

# Remove unnecessary directories and files
rmdir svenusling-main/site
rmdir svenusling-main
rm main.zip