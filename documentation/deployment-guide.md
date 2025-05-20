# Guía de Despliegue - Sistema de Gestión Hotelera Decameron

Esta guía explica paso a paso cómo desplegar la aplicación en un entorno de producción.

## Requisitos previos

- PHP 8.1 o superior
- Composer
- Node.js y npm
- PostgreSQL
- Servidor web (Apache o Nginx)
- Git

## Paso 1: Clonar los repositorios

```bash
# Clonar el repositorio del backend
git clone https://github.com/tu-usuario/decameron-backend.git

# Clonar el repositorio del frontend
git clone https://github.com/tu-usuario/decameron-frontend.git


Paso 2: Configurar el backend (Laravel)
bash# Navegar al directorio del backend
cd decameron-backend

# Instalar dependencias
composer install --optimize-autoloader --no-dev

# Copiar archivo de entorno
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
Edita el archivo .env con la configuración correcta de base de datos:
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=decameron
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
Continúa con la configuración:
bash# Ejecutar migraciones y seeders
php artisan migrate --seed

# Optimizar la aplicación
php artisan config:cache
php artisan route:cache
php artisan view:cache
Paso 3: Configurar el frontend (React)
bash# Navegar al directorio del frontend
cd ../decameron-frontend

# Instalar dependencias
npm install

# Crear archivo .env para producción
echo "REACT_APP_API_URL=https://api.tu-dominio.com/api" > .env.production

# Construir la aplicación
npm run build
Paso 4: Configuración del servidor web
Para el backend (Laravel)
Con Nginx:
nginxserver {
    listen 80;
    server_name api.tu-dominio.com;
    root /ruta/a/decameron-backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
Para el frontend (React)
nginxserver {
    listen 80;
    server_name tu-dominio.com;
    root /ruta/a/decameron-frontend/build;

    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }

    location ~* \.(?:css|js|jpg|jpeg|gif|png|ico|svg)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }
}
Paso 5: Configurar HTTPS con Let's Encrypt (Recomendado)
bash# Instalar Certbot
sudo apt install certbot python3-certbot-nginx

# Obtener certificados
sudo certbot --nginx -d api.tu-dominio.com
sudo certbot --nginx -d tu-dominio.com
Paso 6: Restaurar la base de datos desde un dump (opcional)
Si prefieres usar el dump en lugar de migraciones:
bash# Restaurar dump
psql -U tu_usuario -d decameron < /ruta/al/dump.sql

### 3. Dump de la base de datos

Para generar el dump de la base de datos:

```bash
# Reemplaza "postgres" y "decameron_db" con tu usuario y nombre de base de datos
pg_dump -U postgres -d decameron_db > decameron_dump.sql