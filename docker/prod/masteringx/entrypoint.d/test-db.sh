#!/usr/bin/env bash

set -e
APP_BASE_DIR=/var/www/html

# Set default values for Laravel automations
: "${AUTORUN_LARAVEL_MIGRATION_TIMEOUT:=30}"

test_db_connection() {
    php -r "
        require '$APP_BASE_DIR/vendor/autoload.php';
        use Illuminate\Support\Facades\DB;

        \$app = require_once '$APP_BASE_DIR/bootstrap/app.php';
        \$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
        \$kernel->bootstrap();

        \$driver = DB::getDriverName();

            if( \$driver === 'sqlite' ){
                echo 'SQLite detected';
                exit(0); // Assume SQLite is always ready
            }

        try {
            DB::connection()->getPdo(); // Attempt to get PDO instance
            if (DB::connection()->getDatabaseName()) {
                exit(0); // Database exists and can be connected to, exit with status 0 (success)
            } else {
                echo 'Database name not found.';
                exit(1); // Database name not found, exit with status 1 (failure)
            }
        } catch (Exception \$e) {
            echo 'Database connection error: ' . \$e->getMessage();
            exit(1); // Connection error, exit with status 1 (failure)
        }
    "
}

            count=0
            timeout=$AUTORUN_LARAVEL_MIGRATION_TIMEOUT

            echo "🚀 Clearing Laravel cache before attempting migrations..."
            php "$APP_BASE_DIR/artisan" config:clear

            # Do not exit on error for this loop
            set +e
            echo "⚡️ Attempting database connection..."
            while [ $count -lt "$timeout" ]; do
                test_db_connection > /dev/null 2>&1
                status=$?
                if [ $status -eq 0 ]; then
                    echo "✅ Database connection successful."
                    exit 0

                else
                    echo "Waiting on database connection, retrying... $((timeout - count)) seconds left"
                    count=$((count + 1))
                    sleep 1
                fi
            done

            # Re-enable exit on error
            set -e

            if [ $count -eq "$timeout" ]; then
                echo "Database connection failed after multiple attempts."
                exit 1
            fi
            echo "✅ Database connection established, proceeding with migrations..."
