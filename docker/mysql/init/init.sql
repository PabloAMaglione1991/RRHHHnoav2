-- Create databases
CREATE DATABASE IF NOT EXISTS factu30_prod_test;

-- Create user and grant permissions
CREATE USER IF NOT EXISTS 'portal_user'@'%' IDENTIFIED BY 'portal_pass';
GRANT ALL PRIVILEGES ON factu30_prod_test.* TO 'portal_user'@'%';
FLUSH PRIVILEGES;

-- Use the test database
USE factu30_prod_test;

-- Create tables in BOTH databases to be sure, as the app seems to mix them
-- We will loop or just repeat for simplicity in this script

-- Functionality for factu30_prod_test
USE factu30_prod_test;

CREATE TABLE IF NOT EXISTS t_tdepen (tdep_id INT PRIMARY KEY, tdep_nom VARCHAR(100));
CREATE TABLE IF NOT EXISTS t_roles (rol_id INT PRIMARY KEY, rol_nombre VARCHAR(50));
CREATE TABLE IF NOT EXISTS t_horarios_definiciones (id INT PRIMARY KEY AUTO_INCREMENT, nombre_horario VARCHAR(100), hora_entrada VARCHAR(8), hora_salida VARCHAR(8), horas_totales_dia DECIMAL(5,2), dias_semana VARCHAR(50), activo TINYINT DEFAULT 1);
CREATE TABLE IF NOT EXISTS t_agente (age_id INT PRIMARY KEY, horario_definicion_id INT, age_nombre VARCHAR(100), age_apell1 VARCHAR(100), age_numdoc VARCHAR(20), age_activo TINYINT DEFAULT 1, tdep_id INT, jefe_age_id INT, muros VARCHAR(20) DEFAULT 'INTRA', no_registra_horario TINYINT DEFAULT 0, age_observ TEXT);
CREATE TABLE IF NOT EXISTS t_licencias_tipos (lt_id INT PRIMARY KEY AUTO_INCREMENT, nombre VARCHAR(100), codigo_legacy VARCHAR(20), regla_calculo_facturacion VARCHAR(50), grupo_descuento VARCHAR(50));
CREATE TABLE IF NOT EXISTS t_artic (tart_id INT PRIMARY KEY, tart_cod VARCHAR(20));
CREATE TABLE IF NOT EXISTS t_solicitudes_licencias (id INT PRIMARY KEY AUTO_INCREMENT, age_id INT, lt_id INT, fecha_solicitud DATE, fecha_inicio DATE, fecha_fin DATE, motivo TEXT, estado VARCHAR(50) DEFAULT 'PENDIENTE', motivo_rechazo TEXT, archivo_adjunto VARCHAR(255));
CREATE TABLE IF NOT EXISTS t_age_tarj (agetarj_id INT PRIMARY KEY, age_id INT, tarj_nro INT, agetarj_activa TINYINT);
CREATE TABLE IF NOT EXISTS t_fichadas (fich_id INT PRIMARY KEY AUTO_INCREMENT, tarj_nro INT, fich_fecha DATE, fich_hora VARCHAR(8));
CREATE TABLE IF NOT EXISTS t_inasist (inas_id INT PRIMARY KEY AUTO_INCREMENT, agetarj_id INT, tart_id INT, inas_fecha DATE);
CREATE TABLE IF NOT EXISTS t_agente_roles (agente_id INT, rol_id INT, PRIMARY KEY (agente_id, rol_id));
CREATE TABLE IF NOT EXISTS t_contrasenias_web (cw_tar_id int(10) NOT NULL, cw_pass varchar(255) NOT NULL, cw_fec_pass timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, cw_dias_caduc_pass int(10) NOT NULL, PRIMARY KEY (cw_tar_id));
CREATE TABLE IF NOT EXISTS t_documentos_agente (doc_id INT PRIMARY KEY AUTO_INCREMENT, age_id INT NOT NULL, tipo_documento VARCHAR(50) NOT NULL, nombre_archivo VARCHAR(255) NOT NULL, ruta_archivo VARCHAR(500) NOT NULL, fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP, subido_por INT, observaciones TEXT);

-- SEED DATA
INSERT IGNORE INTO t_tdepen (tdep_id, tdep_nom) VALUES (1, 'Administración'), (2, 'Guardia'), (3, 'Laboratorio');
INSERT IGNORE INTO t_roles (rol_id, rol_nombre) VALUES (1, 'admin'), (2, 'rrhh'), (3, 'jefe');
INSERT IGNORE INTO t_horarios_definiciones (id, nombre_horario, hora_entrada, hora_salida, horas_totales_dia, dias_semana, activo) 
VALUES (1, 'Administrativo Estandard', '09:00:00', '18:00:00', 9.00, '1,2,3,4,5', 1);

-- Agentes de prueba
INSERT IGNORE INTO t_agente (age_id, horario_definicion_id, age_nombre, age_apell1, age_numdoc, age_activo, tdep_id, jefe_age_id) 
VALUES (9524, 1, 'Pablo', 'Tester', '12345678', 1, 1, NULL);
INSERT IGNORE INTO t_agente (age_id, horario_definicion_id, age_nombre, age_apell1, age_numdoc, age_activo, tdep_id, jefe_age_id)
VALUES (1001, 1, 'Juan', 'Empleado', '87654321', 1, 2, 9524);

INSERT IGNORE INTO t_agente_roles (agente_id, rol_id) VALUES (9524, 1), (9524, 2);
INSERT IGNORE INTO t_licencias_tipos (lt_id, nombre, codigo_legacy, regla_calculo_facturacion, grupo_descuento) VALUES
(1, 'Vacaciones', 'VAC', 'lao', 'vacaciones'),
(2, 'Enfermedad', 'ENF', 'total', 'enfermedad'),
(3, 'Estudio', 'EST', 'total', 'ninguno');
INSERT IGNORE INTO t_artic (tart_id, tart_cod) VALUES (500, 'VAC'), (501, 'ENF'), (502, 'EST');
INSERT IGNORE INTO t_age_tarj (agetarj_id, age_id, tarj_nro, agetarj_activa) VALUES (100, 9524, 12345, 1);

-- Contraseña para entrar (DNI: 12345678, Tarjeta: 12345, Pass: 1234)
INSERT IGNORE INTO t_contrasenias_web (cw_tar_id, cw_pass, cw_dias_caduc_pass) VALUES (12345, '1234', 90);
