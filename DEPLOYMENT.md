# Guía de Despliegue en Producción - Portal RRHH

Este documento detalla los pasos necesarios para desplegar la aplicación en un entorno de producción (Virtual Machine).

## 1. Requisitos Previos
Asegúrate de que la VM tenga instalado:
- **PHP 8.x** (con extensiones: pdo_mysql, bcmath, gd, xml, curl, zip, mbstring).
- **Composer** (v2+).
- **Servidor Web**: Nginx (recomendado) o Apache.
- **Base de Datos**: MySQL o MariaDB.
- **Node.js y NPM** (solo si necesitas compilar assets en el servidor).

---

## 2. Preparación de Archivos
1. Sube el código a la VM (vía Git, SCP o FTP).
2. Dentro de la carpeta del proyecto, instala las dependencias de PHP sin herramientas de desarrollo:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

---

## 3. Configuración del Entorno (`.env`)
1. Copia el archivo de ejemplo: `cp .env.example .env` (o usa el que ya configuramos).
2. **IMPORTANTE**: Edita el archivo `.env` para producción:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://tu-dominio.com`
   - **Base de Datos**: Cambia `DB_DATABASE=factu30_prod_test` por la base de datos real de producción (ej: `factu30_prod`).
   - Verifica las credenciales de `DB_USERNAME` y `DB_PASSWORD`.
3. Genera la clave de la aplicación:
   ```bash
   php artisan key:generate
   ```

---

## 4. Permisos de Carpetas
Laravel requiere permisos de escritura en `storage` y `bootstrap/cache`:
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```
*(Nota: Reemplaza `www-data` por el usuario de tu servidor web si es diferente).*

---

## 5. Compilación de Assets (CSS/JS)
Si has realizado cambios en el frontend, compila para producción:
```bash
npm install
npm run prod
```

---

## 6. Configuración del Servidor Web (Nginx)
El "root" del sitio debe apuntar a la carpeta `/public` del proyecto.  
Ejemplo de configuración básica:
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /var/www/html/PortalRRHH/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.x-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## 7. Optimizaciones Finales
Para el mejor rendimiento en producción, ejecuta estos comandos:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 8. Consideraciones de Base de Datos
Si las tablas ya existen, no hace falta ejecutar `php artisan migrate`. Sin embargo, si has añadido nuevas funcionalidades que requieren tablas nuevas:
```bash
php artisan migrate --force
```
*(El flag `--force` es necesario en entorno de producción).*
