# Nginx container to serve public assets
FROM nginx:alpine

# Copy production-ready Nginx config
COPY ./docker/prod/nginx/conf.d/nginx.conf /etc/nginx/conf.d/default.conf

# Copy built Laravel public files from your PHP-FPM container or local build
COPY ./public /var/www/html/public

# Set working directory
WORKDIR /var/www/html/public

# Expose port
EXPOSE 80

# Start Nginx in foreground
CMD ["nginx", "-g", "daemon off;"]

# Stage 2: Serve with Nginx
FROM nginx:alpine

COPY ./docker/prod/nginx/conf.d/nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=builder /var/www/html/public /var/www/html/public

WORKDIR /var/www/html/public
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]


# Stage 1: Build frontend assets
# When i remove filamentphp, it works
#FROM node:20-bullseye as builder
#
#WORKDIR /var/www/html
#
#COPY package.json package-lock.json ./
#RUN npm ci
#

#
#
#COPY . .
#
#RUN npm run build
