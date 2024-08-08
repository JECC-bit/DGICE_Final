-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-08-2024 a las 02:50:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_dgice`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad`
--

CREATE TABLE `actividad` (
  `Id_actividad` int(11) NOT NULL,
  `Titulo` varchar(100) NOT NULL,
  `Descripcion` text NOT NULL,
  `Video` text NULL,
  `Status` varchar(25) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Fecha_publicacion` datetime NOT NULL,
  `Fecha_entrega` datetime NOT NULL,
  `orden` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_actividad`
--

CREATE TABLE `asignacion_actividad` (
  `Id_actividad` int(11) NOT NULL,
  `Id_bootcamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_cuenta`
--

CREATE TABLE `asignacion_cuenta` (
  `Id_cuenta_bootcamp` int(11) NOT NULL,
  `Id_bootcamp` int(11) NOT NULL,
  `Id_cuenta` int(11) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_encargado`
--

CREATE TABLE `asignacion_encargado` (
  `Id_cuenta` int(11) NOT NULL,
  `Id_bootcamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_equipo`
--

CREATE TABLE `asignacion_equipo` (
  `Id_equipo` int(11) NOT NULL,
  `Id_cuenta_bootcamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_material`
--

CREATE TABLE `asignacion_material` (
  `Id_material` int(11) NOT NULL,
  `Id_bootcamp` int(11) DEFAULT NULL,
  `Id_actividad` int(11) DEFAULT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_reto`
--

CREATE TABLE `asignacion_reto` (
  `Id_reto` int(11) NOT NULL,
  `Id_equipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bootcamp`
--

CREATE TABLE `bootcamp` (
  `Id_bootcamp` int(11) NOT NULL,
  `Codigo` varchar(10) NOT NULL,
  `Nombre_bootcamp` varchar(100) NOT NULL,
  `Fecha_inicio` date NOT NULL,
  `Fecha_cierre` date NOT NULL,
  `Descripcion` text NOT NULL,
  `Id_campus` int(11) NOT NULL,
  `Status` varchar(10) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campus`
--

CREATE TABLE `campus` (
  `Id_campus` int(11) NOT NULL,
  `Campus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `campus`
--

INSERT INTO `campus` (`Id_campus`, `Campus`) VALUES
(1, 'Manzanillo'),
(2, 'Tecomán'),
(3, 'Colima'),
(4, 'Coquimatlán'),
(5, 'Villa de Álvarez');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `Id_carrera` int(11) NOT NULL,
  `Id_facultad` int(11) NOT NULL,
  `Carrera` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `carrera`
--

INSERT INTO `carrera` (`Id_carrera`, `Id_facultad`, `Carrera`) VALUES
(1, 1, 'Licenciatura en Gastronomía'),
(2, 1, 'Licenciatura en Gestión Turística'),
(3, 1, 'Maestría en Emprendimiento e Innovación de Negocios Turísticos'),
(4, 2, 'Ingeniería Oceánica'),
(5, 2, 'Licenciatura en Gestión de Recursos Marinos y Portuarios'),
(6, 2, 'Licenciatura en Oceanología'),
(7, 3, 'Contador Público'),
(8, 3, 'Licenciatura en Administración'),
(9, 3, 'Licenciatura en Gestión de Negocios Digitales'),
(10, 3, 'Maestría en Alta Dirección '),
(11, 3, 'Maestría en Fiscal'),
(12, 4, 'Ingeniería de Software'),
(13, 4, 'Ingeniería en Mecatrónica'),
(14, 4, 'Ingeniería en Tecnologías Electrónicas'),
(15, 4, 'Ingeniero Mecánico Electricista '),
(16, 4, 'Maestría en Ingeniería Aplicada'),
(17, 5, 'Licenciado(a) en Aduanas'),
(18, 5, 'Licenciado(a) en Comercio Exterior'),
(19, 5, 'Maestría en Desarrollo Corporativo del Comercio Internacional'),
(20, 6, 'Contador Público'),
(21, 6, 'Licenciatura en Administración'),
(22, 6, 'Licenciatura en Gestión de Negocios Digitales'),
(23, 6, 'Maestría en Alta Dirección'),
(24, 6, 'Maestría en Fiscal'),
(25, 7, 'Ingeniero(a) Agrónomo '),
(26, 7, 'Licenciatura en Biología'),
(27, 7, 'Maestría en Agricultura Protegida'),
(28, 8, 'Médico Veterinario Zootecnista'),
(29, 8, 'Maestría en Producción Pecuaria'),
(30, 9, 'Licenciatura en Mercadotecnia'),
(31, 9, 'Licenciatura en publicidad y Relaciones Públicas'),
(32, 9, 'Maestría en Gestión de Marcas en Entornos Físicos y Digitales '),
(33, 10, 'Licenciatura en Física'),
(34, 10, 'Licenciatura en Gestión y Reducción del Riesgo de Desastres'),
(35, 10, 'Licenciatura en Matemáticas'),
(36, 11, 'Licenciatura en Educación Especial'),
(37, 11, 'Licenciatura en Educación Física y Deporte'),
(38, 11, 'Licenciatura en Enseñanza de las Matemáticas'),
(39, 11, 'Maestría en Intervención Educativa'),
(40, 12, 'Ingeniería de Software'),
(41, 12, 'Ingeniería en Tecnologías de Internet'),
(42, 12, 'Maestría en Tecnologías de Internet'),
(43, 12, 'Maestría en Transformación Digital'),
(44, 13, 'Licenciatura en Trabajo Social'),
(45, 13, 'Maestría en Gerontología'),
(46, 14, 'Licenciatura en Administración Pública y Ciencia Política'),
(47, 14, 'Licenciatura en Relaciones Internacionales'),
(48, 14, 'Doctorado en Ciencias Sociales'),
(49, 14, 'Doctorado en Estudios Sociopolíticos para el Desarrollo Glocal '),
(50, 15, 'Contador Público'),
(51, 15, 'Licenciatura en Administración'),
(52, 15, 'Maestría en Alta Dirección'),
(53, 15, 'Maestría en Fiscal'),
(54, 16, 'Licenciatura en Derecho'),
(55, 16, 'Maestría en Bioética'),
(56, 16, 'Maestría en Derecho'),
(57, 16, 'Doctorado en Interinstitucional en Derecho'),
(58, 17, 'Licenciado(a) en Enfermería'),
(59, 17, 'Especialidad en Enfermería Quirúrgica '),
(60, 18, 'Licenciatura en Psicología '),
(61, 18, 'Maestría en Psicología '),
(62, 18, 'Doctorado en Psicología'),
(63, 19, 'Licenciatura en Comunicación '),
(64, 19, 'Licenciatura en Letras Hispanoamericanas '),
(65, 19, 'Licenciatura en Lingüística'),
(66, 19, 'Licenciatura en Periodismo '),
(67, 19, 'Maestría en Estudios Literarios Mexicanos'),
(68, 19, 'Doctorado en Estudios Socioculturales sobre las Desigualdades'),
(69, 20, 'Licenciatura en Nutrición'),
(70, 20, 'Médico Cirujano y Partero'),
(71, 20, 'Maestría en Bioética'),
(72, 20, 'Maestría en Ciencias Fisiológicas'),
(73, 20, 'Maestría en Ciencias Médicas'),
(74, 20, 'Maestría en Nutrición Clínica'),
(75, 20, 'Doctorado en Ciencias Fisiológicas'),
(76, 20, 'Doctorado en Ciencias Médicas'),
(77, 20, 'Especialidad en Medicina del Trabajo y Ambiental'),
(78, 20, 'Alta Especialidad en Cirugía Bariátrica y Metabólica '),
(79, 20, 'Especialidad en Anestesiología'),
(80, 20, 'Especialidad en Cirugía General'),
(81, 20, 'Especialidad en Epidemiología'),
(82, 20, 'Especialidad en Geriatría '),
(83, 20, 'Especialidad en Ginecología y Obstetricia '),
(84, 20, 'Especialidad en Imagenología Diagnóstica y Terapéutica'),
(85, 20, 'Especialidad en Medicina Familiar'),
(86, 20, 'Especialidad en Medicina Interna'),
(87, 20, 'Especialidad en Pediatría '),
(88, 20, 'Especialidad en Traumatología y Ortopedia '),
(89, 20, 'Especialidad en Urgencias Médico Quirúrgicas '),
(90, 21, 'Bachillerato Técnico en Música'),
(91, 21, 'Licenciatura en Artes Visuales'),
(92, 21, 'Licenciatura en Danza Escénica'),
(93, 21, 'Licenciatura en música'),
(94, 22, 'Licenciatura en Arquitectura '),
(95, 22, 'Licenciatura en Diseño Gráfico'),
(96, 22, 'Licenciatura en Diseño Industrial'),
(97, 22, 'Maestría en Arquitectura'),
(98, 22, 'Maestría en Arquitectura Bioclimática'),
(99, 22, 'Doctorado en Arquitectura'),
(100, 23, 'Ingeniería Química Metalúrgica '),
(101, 23, 'Ingeniero(a) Químico(a) en Alimentos'),
(102, 23, 'Químico Farmacéutico Biólogo'),
(103, 23, 'Maestría en Ingeniería Química Ambiental'),
(104, 23, 'Doctorado en Ciencias Químicas'),
(105, 24, 'Ingeniería Civil'),
(106, 24, 'Ingeniero Topógrafo Geomático'),
(107, 24, 'Doctorado en Cambio Ambiental Global'),
(108, 25, 'Ingeniería en Computación Inteligente'),
(109, 25, 'Ingeniería en Mecatrónica'),
(110, 25, 'Ingeniería en Sistemas Electrónicos y Telecomunicaciones '),
(111, 25, 'Ingeniero Mecánico Electricista'),
(112, 25, 'Maestría en Ingeniería Aplicada'),
(113, 25, 'Maestría en Ingeniería de Procesos'),
(114, 26, 'Licenciatura en Filosofía'),
(115, 26, 'Maestría en Bioética'),
(116, 27, 'Licenciado(a) en Pedagogía'),
(117, 27, 'Maestría en innovación Educativa'),
(118, 28, 'Licenciatura en Economía'),
(119, 28, 'Licenciatura en Finanzas'),
(120, 28, 'Licenciatura en Negocios Internacionales'),
(121, 28, 'Maestría en Gestión del Desarrollo'),
(122, 28, 'Maestría en Negocios'),
(123, 28, 'Doctorado en Relaciones Transpacíficas(Directo)'),
(124, 28, 'Doctorado Interinstitucional en Economía Social Solidaria'),
(125, 29, 'Licenciatura en Gestión Turística'),
(126, 29, 'Maestría en Emprendimiento e Innovación de Negocios Turísticos'),
(127, 30, 'Licenciatura en Enseñanza de Lenguas'),
(128, 30, 'Maestría en Profesionalización de la Docencia de Lenguas Extranjeras');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta`
--

CREATE TABLE `cuenta` (
  `Id_cuenta` int(11) NOT NULL,
  `No_cuenta` int(8) NOT NULL,
  `Correo` varchar(320) NOT NULL,
  `Contrasena` varchar(100) NOT NULL,
  `Rol` varchar(15) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrega`
--

CREATE TABLE `entrega` (
  `Id_actividad` int(11) NOT NULL,
  `Id_equipo` int(11) NOT NULL,
  `Status` varchar(25) NOT NULL DEFAULT 'Bloqueado',
  `Fecha_entrega` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `Id_equipo` int(11) NOT NULL,
  `No_equipo` varchar(15) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facultad`
--

CREATE TABLE `facultad` (
  `Id_facultad` int(11) NOT NULL,
  `Id_campus` int(11) NOT NULL,
  `Facultad` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `facultad`
--

INSERT INTO `facultad` (`Id_facultad`, `Id_campus`, `Facultad`) VALUES
(1, 1, 'Facultad de Turismo y Gastronomía'),
(2, 1, 'Facultad de Ciencias Marinas'),
(3, 1, 'Facultad de Contabilidad y Administración de Manzanillo'),
(4, 1, 'Facultad de Ingeniería Electromecánica'),
(5, 1, 'Facultad de Comercio Exterior'),
(6, 2, 'Facultad de Contabilidad y Administración de Tecomán'),
(7, 2, 'Facultad de Ciencias Biológicas y Agropecuarias'),
(8, 2, 'Facultad de Medicina Veterinaria y Zootecnia'),
(9, 3, 'Escuela de Mercadotecnia'),
(10, 3, 'Facultad de Ciencias'),
(11, 3, 'Facultad de Ciencias de la Educación'),
(12, 3, 'Facultad de Telemática'),
(13, 3, 'Facultad de Trabajo Social'),
(14, 3, 'Facultad de Ciencias Políticas y Sociales'),
(15, 3, 'Facultad de Contabilidad y Administración de Colima'),
(16, 3, 'Facultad de Derecho'),
(17, 3, 'Facultad de Enfermería'),
(18, 3, 'Facultad de Psicología'),
(19, 3, 'Facultad de Letras y Comunicación'),
(20, 3, 'Facultad de Medicina'),
(21, 3, 'Instituto Universitario de Bellas Artes'),
(22, 4, 'Facultad de Arquitectura y Diseño'),
(23, 4, 'Facultad de Ciencias Químicas'),
(24, 4, 'Facultad de Ingeniería Civil'),
(25, 4, 'Facultad de Ingeniería Mecánica y Eléctrica'),
(26, 5, 'Escuela de Filosofía'),
(27, 5, 'Facultad de Pedagogía'),
(28, 5, 'Facultad de Economía'),
(29, 5, 'Facultad de Turismo'),
(30, 5, 'Facultad de Lenguas Extranjeras');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `material`
--

CREATE TABLE `material` (
  `Id_material` int(11) NOT NULL,
  `Material` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nombre`
--

CREATE TABLE `nombre` (
  `Id_nombre` int(11) NOT NULL,
  `Nombres` varchar(30) NOT NULL,
  `Apellido_paterno` varchar(15) NOT NULL,
  `Apellido_materno` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reto`
--

CREATE TABLE `reto` (
  `Id_reto` int(11) NOT NULL,
  `Id_bootcamp` int(11) NOT NULL,
  `Nombre_empresa` varchar(100) NOT NULL,
  `Giro_empresa` text NOT NULL,
  `Descripcion_empresa` text NOT NULL,
  `Problematica` text NOT NULL,
  `Limite_inscritos` int(2) NOT NULL,
  `Actuales_inscritos` int(2) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `No_cuenta` int(8) NOT NULL,
  `Id_nombre` int(11) NOT NULL,
  `Id_campus` int(3) DEFAULT NULL,
  `Id_facultad` int(4) DEFAULT NULL,
  `Id_carrera` int(4) DEFAULT NULL,
  `Semestre` int(2) DEFAULT NULL,
  `Grupo` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividad`
--
ALTER TABLE `actividad`
  ADD PRIMARY KEY (`Id_actividad`);

--
-- Indices de la tabla `asignacion_actividad`
--
ALTER TABLE `asignacion_actividad`
  ADD KEY `Actividad` (`Id_actividad`),
  ADD KEY `Bootcamp` (`Id_bootcamp`);

--
-- Indices de la tabla `asignacion_cuenta`
--
ALTER TABLE `asignacion_cuenta`
  ADD PRIMARY KEY (`Id_cuenta_bootcamp`),
  ADD KEY `Bootcamp` (`Id_bootcamp`),
  ADD KEY `Cuenta` (`Id_cuenta`);

--
-- Indices de la tabla `asignacion_encargado`
--
ALTER TABLE `asignacion_encargado`
  ADD KEY `Encargado` (`Id_cuenta`),
  ADD KEY `Bootcamp` (`Id_bootcamp`);

--
-- Indices de la tabla `asignacion_equipo`
--
ALTER TABLE `asignacion_equipo`
  ADD KEY `Equipo` (`Id_equipo`),
  ADD KEY `Relacion` (`Id_cuenta_bootcamp`);

--
-- Indices de la tabla `asignacion_material`
--
ALTER TABLE `asignacion_material`
  ADD KEY `Material` (`Id_material`),
  ADD KEY `Bootcamp` (`Id_bootcamp`),
  ADD KEY `Actividad` (`Id_actividad`);

--
-- Indices de la tabla `asignacion_reto`
--
ALTER TABLE `asignacion_reto`
  ADD UNIQUE KEY `Reto` (`Id_reto`) USING BTREE,
  ADD KEY `Equipo` (`Id_equipo`) USING BTREE;

--
-- Indices de la tabla `bootcamp`
--
ALTER TABLE `bootcamp`
  ADD PRIMARY KEY (`Id_bootcamp`),
  ADD UNIQUE KEY `Codigo bootcamp` (`Codigo`),
  ADD KEY `Campus` (`Id_campus`);

--
-- Indices de la tabla `campus`
--
ALTER TABLE `campus`
  ADD PRIMARY KEY (`Id_campus`);

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`Id_carrera`),
  ADD KEY `Id_facultad` (`Id_facultad`);

--
-- Indices de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD PRIMARY KEY (`Id_cuenta`),
  ADD UNIQUE KEY `Cuenta` (`No_cuenta`),
  ADD UNIQUE KEY `Correo` (`Correo`);

--
-- Indices de la tabla `entrega`
--
ALTER TABLE `entrega`
  ADD KEY `Actividad` (`Id_actividad`),
  ADD KEY `Equipo` (`Id_equipo`);

--
-- Indices de la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`Id_equipo`);

--
-- Indices de la tabla `facultad`
--
ALTER TABLE `facultad`
  ADD PRIMARY KEY (`Id_facultad`),
  ADD KEY `Id_campus` (`Id_campus`);

--
-- Indices de la tabla `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`Id_material`);

--
-- Indices de la tabla `nombre`
--
ALTER TABLE `nombre`
  ADD PRIMARY KEY (`Id_nombre`);

--
-- Indices de la tabla `reto`
--
ALTER TABLE `reto`
  ADD PRIMARY KEY (`Id_reto`),
  ADD KEY `Bootcamp` (`Id_bootcamp`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`No_cuenta`),
  ADD UNIQUE KEY `Nombre` (`Id_nombre`) USING BTREE,
  ADD KEY `Campus` (`Id_campus`,`Id_facultad`,`Id_carrera`),
  ADD KEY `Id_facultad` (`Id_facultad`),
  ADD KEY `Id_carrera` (`Id_carrera`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividad`
--
ALTER TABLE `actividad`
  MODIFY `Id_actividad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `asignacion_cuenta`
--
ALTER TABLE `asignacion_cuenta`
  MODIFY `Id_cuenta_bootcamp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `bootcamp`
--
ALTER TABLE `bootcamp`
  MODIFY `Id_bootcamp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `campus`
--
ALTER TABLE `campus`
  MODIFY `Id_campus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `carrera`
--
ALTER TABLE `carrera`
  MODIFY `Id_carrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  MODIFY `Id_cuenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `Id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `facultad`
--
ALTER TABLE `facultad`
  MODIFY `Id_facultad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `material`
--
ALTER TABLE `material`
  MODIFY `Id_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `nombre`
--
ALTER TABLE `nombre`
  MODIFY `Id_nombre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `reto`
--
ALTER TABLE `reto`
  MODIFY `Id_reto` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignacion_actividad`
--
ALTER TABLE `asignacion_actividad`
  ADD CONSTRAINT `asignacion_actividad_ibfk_1` FOREIGN KEY (`Id_bootcamp`) REFERENCES `bootcamp` (`Id_bootcamp`),
  ADD CONSTRAINT `asignacion_actividad_ibfk_2` FOREIGN KEY (`Id_actividad`) REFERENCES `actividad` (`Id_actividad`);

--
-- Filtros para la tabla `asignacion_cuenta`
--
ALTER TABLE `asignacion_cuenta`
  ADD CONSTRAINT `asignacion_cuenta_ibfk_1` FOREIGN KEY (`Id_bootcamp`) REFERENCES `bootcamp` (`Id_bootcamp`),
  ADD CONSTRAINT `asignacion_cuenta_ibfk_2` FOREIGN KEY (`Id_cuenta`) REFERENCES `cuenta` (`Id_cuenta`);

--
-- Filtros para la tabla `asignacion_encargado`
--
ALTER TABLE `asignacion_encargado`
  ADD CONSTRAINT `asignacion_encargado_ibfk_1` FOREIGN KEY (`Id_cuenta`) REFERENCES `cuenta` (`Id_cuenta`),
  ADD CONSTRAINT `asignacion_encargado_ibfk_2` FOREIGN KEY (`Id_bootcamp`) REFERENCES `bootcamp` (`Id_bootcamp`);

--
-- Filtros para la tabla `asignacion_equipo`
--
ALTER TABLE `asignacion_equipo`
  ADD CONSTRAINT `asignacion_equipo_ibfk_1` FOREIGN KEY (`Id_cuenta_bootcamp`) REFERENCES `asignacion_cuenta` (`Id_cuenta_bootcamp`),
  ADD CONSTRAINT `asignacion_equipo_ibfk_2` FOREIGN KEY (`Id_equipo`) REFERENCES `equipo` (`Id_equipo`);

--
-- Filtros para la tabla `asignacion_material`
--
ALTER TABLE `asignacion_material`
  ADD CONSTRAINT `asignacion_material_ibfk_1` FOREIGN KEY (`Id_bootcamp`) REFERENCES `bootcamp` (`Id_bootcamp`),
  ADD CONSTRAINT `asignacion_material_ibfk_2` FOREIGN KEY (`Id_material`) REFERENCES `material` (`Id_material`),
  ADD CONSTRAINT `asignacion_material_ibfk_3` FOREIGN KEY (`Id_actividad`) REFERENCES `actividad` (`Id_actividad`);

--
-- Filtros para la tabla `asignacion_reto`
--
ALTER TABLE `asignacion_reto`
  ADD CONSTRAINT `asignacion_reto_ibfk_1` FOREIGN KEY (`Id_reto`) REFERENCES `reto` (`Id_reto`),
  ADD CONSTRAINT `asignacion_reto_ibfk_2` FOREIGN KEY (`Id_equipo`) REFERENCES `equipo` (`Id_equipo`);

--
-- Filtros para la tabla `bootcamp`
--
ALTER TABLE `bootcamp`
  ADD CONSTRAINT `bootcamp_ibfk_1` FOREIGN KEY (`Id_campus`) REFERENCES `campus` (`Id_campus`);

--
-- Filtros para la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD CONSTRAINT `carrera_ibfk_1` FOREIGN KEY (`Id_facultad`) REFERENCES `facultad` (`Id_facultad`);

--
-- Filtros para la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD CONSTRAINT `cuenta_ibfk_1` FOREIGN KEY (`No_cuenta`) REFERENCES `usuario` (`No_cuenta`);

--
-- Filtros para la tabla `entrega`
--
ALTER TABLE `entrega`
  ADD CONSTRAINT `entrega_ibfk_1` FOREIGN KEY (`Id_actividad`) REFERENCES `actividad` (`Id_actividad`),
  ADD CONSTRAINT `entrega_ibfk_2` FOREIGN KEY (`Id_equipo`) REFERENCES `equipo` (`Id_equipo`);

--
-- Filtros para la tabla `facultad`
--
ALTER TABLE `facultad`
  ADD CONSTRAINT `facultad_ibfk_1` FOREIGN KEY (`Id_campus`) REFERENCES `campus` (`Id_campus`);

--
-- Filtros para la tabla `reto`
--
ALTER TABLE `reto`
  ADD CONSTRAINT `reto_ibfk_1` FOREIGN KEY (`Id_bootcamp`) REFERENCES `bootcamp` (`Id_bootcamp`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`Id_nombre`) REFERENCES `nombre` (`Id_nombre`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`Id_campus`) REFERENCES `campus` (`Id_campus`),
  ADD CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`Id_facultad`) REFERENCES `facultad` (`Id_facultad`),
  ADD CONSTRAINT `usuario_ibfk_4` FOREIGN KEY (`Id_carrera`) REFERENCES `carrera` (`Id_carrera`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
