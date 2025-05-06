#!/bin/bash

#
# This script needs to be run one time after you start up Docker Compose
# to set the correct permissions on the var and vendor volumes that are
# needed to boost performance, especially on Windows.
#
# Run this script from your bash shell **outside the container** before
# attempting to run composer install to set up Symfony.
#

docker exec -u root:root app_php chown webmxr:webmxr var
docker exec -u root:root app_php chown webmxr:webmxr vendor
