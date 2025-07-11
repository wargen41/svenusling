#!/bin/bash

# Script to sync the contents of the "site" folder to local web server directory /var/www/html/svenusling-site

# Exit on error
set -e

# Get the directory where this script is located
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"

SRC_DIR="$SCRIPT_DIR/site/"
DEST_DIR="/var/www/html/svenusling-site/"

# Run rsync with archive mode, verbose, and delete extraneous files from destination
rsync -av --delete "$SRC_DIR" "$DEST_DIR"

echo "Sync complete: '$SRC_DIR' -> '$DEST_DIR'"
