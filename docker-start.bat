@echo off
echo Iniciando entorno Docker para Portal Hospital...
docker-compose up -d --build

echo Esperando a que la base de datos este lista...
timeout /t 10

echo Instalando dependencias (composer)...
docker exec hospital_app composer install

echo Generando llave de aplicacion...
docker exec hospital_app php artisan key:generate

echo Ejecutando migraciones...
docker exec hospital_app php artisan migrate --force

echo Entorno listo! 
echo Accede a: http://localhost:8080
pause
