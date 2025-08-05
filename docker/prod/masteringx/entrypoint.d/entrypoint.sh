#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}



if [ "$role" = "app" ]; then

   /usr/local/bin/laravel-automations.sh

    echo "Starting PHP-FPM..."
    exec php-fpm

elif [ "$role" = "queue" ]; then
    /usr/local/bin/test-db.sh

    echo "Running the queue worker..."
    exec php /var/www/html/artisan queue:work --verbose --tries=3 --timeout=3600

#elif [ "$role" = "scheduler" ]; then
#    echo "Running Laravel scheduler..."
#    while true; do
#        php /var/www/html/artisan schedule:run --verbose --no-interaction
#        sleep 60
#    done

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
