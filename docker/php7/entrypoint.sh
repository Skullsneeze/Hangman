#!/bin/sh

## Make sure var log folder is created
mkdir -p /var/www/html/var/log

# Hand off to the CMD
exec "$@"