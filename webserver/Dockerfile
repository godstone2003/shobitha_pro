FROM nginx:alpine

# Install PHP-FPM and required extensions
RUN apk add --no-cache \
    php83-fpm \
    php83-mysqli \
    php83-pdo_mysql \
    php83-session

# Ensure nginx user exists
RUN addgroup -g 101 -S nginx 2>/dev/null || true && \
    adduser -S -D -H -u 101 -h /var/cache/nginx -s /sbin/nologin -G nginx -g nginx nginx 2>/dev/null || true

# Create necessary directories in the image (before tmpfs overlay)
RUN mkdir -p /var/lib/nginx /var/cache/nginx /tmp/nginx

# Copy configuration files from configfiles directory
COPY configfiles/nginx.conf /etc/nginx/nginx.conf
COPY configfiles/php-fpm.conf /etc/php83/php-fpm.conf
COPY configfiles/www.conf /etc/php83/php-fpm.d/www.conf
COPY configfiles/php.ini /etc/php83/php.ini

# Copy application files from webfiles directory
COPY --chown=nginx:nginx webfiles/ /var/www/html/

# Create startup script that switches to nginx user
RUN echo '#!/bin/sh' > /start.sh && \
    echo '' >> /start.sh && \
    echo '# Ensure tmpfs directories exist and are writable' >> /start.sh && \
    echo 'mkdir -p /tmp/client_body /tmp/proxy /tmp/fastcgi /tmp/uwsgi /tmp/scgi' >> /start.sh && \
    echo 'mkdir -p /var/lib/nginx /var/cache/nginx' >> /start.sh && \
    echo 'chown -R nginx:nginx /tmp /run 2>/dev/null || true' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Start PHP-FPM as nginx user' >> /start.sh && \
    echo 'su -s /bin/sh nginx -c "php-fpm83 -D"' >> /start.sh && \
    echo '' >> /start.sh && \
    echo '# Start nginx as nginx user (redirect startup errors to /tmp)' >> /start.sh && \
    echo 'su -s /bin/sh nginx -c "nginx -g \"daemon off;\" 2>/tmp/nginx-startup-error.log"' >> /start.sh && \
    chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]