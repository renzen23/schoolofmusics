#!/bin/bash
set -euo pipefail
IFS=$'\n\t'

# Paths relative to root.

# Database.
mysqladmin create wordpress_app -u root
