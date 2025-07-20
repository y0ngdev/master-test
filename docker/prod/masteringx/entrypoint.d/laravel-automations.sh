#!/bin/sh
script_name="laravel-automations"

APP_BASE_DIR=/var/www/html




        # Check to see if an Artisan file exists and assume it means Laravel is configured.
        if [ -f "$APP_BASE_DIR/artisan" ]; then
        echo "Checking for Laravel automations..."
        ############################################################################
        # artisan migrate
        ############################################################################
        if [ "${AUTORUN_LARAVEL_MIGRATION:=true}" = "true" ]; then

            echo  "⚡ Running DB readiness check..."

            echo ""

            if ! /usr/local/bin/test-db.sh; then
                echo "❌ Database connection failed..."
                exit 1
            fi

            echo "🚀 Running migrations..."
            if [ "${AUTORUN_LARAVEL_MIGRATION_ISOLATION:=false}" = "true" ]; then
                php "$APP_BASE_DIR/artisan" migrate --force --isolated
            else
                php "$APP_BASE_DIR/artisan" migrate --force
            fi
        fi

        ############################################################################
        # artisan storage:link
        ############################################################################
        if [ "${AUTORUN_LARAVEL_STORAGE_LINK:=true}" = "true" ]; then
            if [ -d "$APP_BASE_DIR/public/storage" ]; then
                echo "✅ Storage already linked..."
            else
                echo "🔐 Linking the storage..."
                php "$APP_BASE_DIR/artisan" storage:link
            fi
        fi

        ############################################################################
        # artisan db:seed --force
        ############################################################################
        if [ "${AUTORUN_LARAVEL_VIEW_CACHE:=true}" = "true" ]; then
            echo "🚀 Clearing Laravel cache before seeding..."
             php "$APP_BASE_DIR/artisan" cache:clear

            echo "🚀 seeding database..."
            php "$APP_BASE_DIR/artisan"  db:seed --force
        fi

        ############################################################################
        # artisan config:cache
        ############################################################################
        if [ "${AUTORUN_LARAVEL_CONFIG_CACHE:=true}" = "true" ]; then
            echo "🚀 Caching Laravel config..."
            php "$APP_BASE_DIR/artisan" config:cache
        fi

        ############################################################################
        # artisan route:cache
        ############################################################################
        if [ "${AUTORUN_LARAVEL_ROUTE_CACHE:=true}" = "true" ]; then
            echo "🚀 Caching Laravel routes..."
            php "$APP_BASE_DIR/artisan" route:cache
        fi

        ############################################################################
        # artisan view:cache
        ############################################################################
        if [ "${AUTORUN_LARAVEL_VIEW_CACHE:=true}" = "true" ]; then
            echo "🚀 Caching Laravel views..."
            php "$APP_BASE_DIR/artisan" view:cache
        fi
        ############################################################################
        # artisan filament:optimize
        ############################################################################
        if [ "${AUTORUN_LARAVEL_VIEW_CACHE:=true}" = "true" ]; then
            echo "🚀 Optimizing fiamentphp..."
            php "$APP_BASE_DIR/artisan"  filament:optimize
        fi


#        ############################################################################
#        # artisan event:cache
#        ############################################################################
#        if [ "${AUTORUN_LARAVEL_EVENT_CACHE:=true}" = "true" ]; then
#            echo "🚀 Caching Laravel events..."
#            php "$APP_BASE_DIR/artisan" event:cache
#        fi
    fi
