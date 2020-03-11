<?php if (!isset($_SESSION)) session_start();
/**
* Copyright (C) '2015' QualtivaWebAPP <http://www.qualtivacr.com>
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
**/

/**
 |-------------------------------------------
 |	CONFIGURACION BASE DE DATOS
 |-------------------------------------------
 */
define('HOST',		'localhost');
define('USER',		'h1000117_stock');
define('PASSWORD',	'DApa47mifi');
define('PORT',		'3306');
define('DB',		'h1000117_stock');

/**
 |-------------------------------------------
 |	CONFIGURACION IDIOMA
 |-------------------------------------------
 */
define('LANGUAGE',	'es');

/**
 |-------------------------------------------
 |	Datos de la Aplicación
 |-------------------------------------------
 */
define('TITULO',	'StockApp');
 
/**
 |-------------------------------------------
 |	CONFIGURACION DIRECCIONES
 |-------------------------------------------
 */
define('URLBASE', '/StockAPP-master/');
define('URLNOTIFICARVENTA', '#');

/**
 |-------------------------------------------
 |	Estado Mantenimiento
 |-------------------------------------------
 */
 define('MANTENIMIENTO', false);

/**
 |-------------------------------------------
 | ESTABLECER LA ZONA HORARIA PREDETERMINADA
 |-------------------------------------------
 */
define('HORARIO', 'America/Costa_Rica');
define('GOOGLEANALYTICS',		'');

/**
 |--------------------------------------------
 | CARGA NUCLEO DE LA APLICACION
 |--------------------------------------------
 */
require_once ('Qualtiva.php');
