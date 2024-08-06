-- Base de datos: `db_dgice`
-- Estructura de tabla para la tabla `actividad`

CREATE TABLE `actividad` (
  `Id_actividad` int(11) NOT NULL,
  `Titulo` varchar(100) NOT NULL,
  `Descripcion` text NOT NULL,
  `Status` varchar(25) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Fecha_publicacion` datetime NOT NULL,
  `Fecha_entrega` datetime NOT NULL,
  `orden` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `asignacion_actividad`

CREATE TABLE `asignacion_actividad` (
  `Id_actividad` int(11) NOT NULL,
  `Id_bootcamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `asignacion_cuenta`

CREATE TABLE `asignacion_cuenta` (
  `Id_cuenta_bootcamp` int(11) NOT NULL,
  `Id_bootcamp` int(11) NOT NULL,
  `Id_cuenta` int(11) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `asignacion_encargado`

CREATE TABLE `asignacion_encargado` (
  `Id_cuenta` int(11) NOT NULL,
  `Id_bootcamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `asignacion_equipo`

CREATE TABLE `asignacion_equipo` (
  `Id_equipo` int(11) NOT NULL,
  `Id_cuenta_bootcamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `asignacion_material`

CREATE TABLE `asignacion_material` (
  `Id_material` int(11) NOT NULL,
  `Id_bootcamp` int(11) DEFAULT NULL,
  `Id_actividad` int(11) DEFAULT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `asignacion_reto`

CREATE TABLE `asignacion_reto` (
  `Id_reto` int(11) NOT NULL,
  `Id_equipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `bootcamp`

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

-- Estructura de tabla para la tabla `campus`

CREATE TABLE `campus` (
  `Id_campus` int(11) NOT NULL,
  `Campus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `carrera`

CREATE TABLE `carrera` (
  `Id_carrera` int(11) NOT NULL,
  `Id_facultad` int(11) NOT NULL,
  `Carrera` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `cuenta`

CREATE TABLE `cuenta` (
  `Id_cuenta` int(11) NOT NULL,
  `No_cuenta` int(8) NOT NULL,
  `Correo` varchar(320) NOT NULL,
  `Contrasena` varchar(100) NOT NULL,
  `Rol` varchar(15) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `entrega`

CREATE TABLE `entrega` (
  `Id_actividad` int(11) NOT NULL,
  `Id_equipo` int(11) NOT NULL,
  `Status` varchar(25) NOT NULL DEFAULT 'Bloqueado',
  `Fecha_entrega` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `equipo`

CREATE TABLE `equipo` (
  `Id_equipo` int(11) NOT NULL,
  `No_equipo` varchar(15) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `facultad`

CREATE TABLE `facultad` (
  `Id_facultad` int(11) NOT NULL,
  `Id_campus` int(11) NOT NULL,
  `Facultad` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `material`

CREATE TABLE `material` (
  `Id_material` int(11) NOT NULL,
  `Material` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`Material`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `nombre`

CREATE TABLE `nombre` (
  `Id_nombre` int(11) NOT NULL,
  `Nombres` varchar(30) NOT NULL,
  `Apellido_paterno` varchar(15) NOT NULL,
  `Apellido_materno` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Estructura de tabla para la tabla `reto`

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

-- Estructura de tabla para la tabla `usuario`

CREATE TABLE `usuario` (
  `No_cuenta` int(8) NOT NULL,
  `Id_nombre` int(11) NOT NULL,
  `Id_campus` int(3) DEFAULT NULL,
  `Id_facultad` int(4) DEFAULT NULL,
  `Id_carrera` int(4) DEFAULT NULL,
  `Semestre` int(2) DEFAULT NULL,
  `Grupo` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Indices de la tabla `actividad`
ALTER TABLE `actividad`
  ADD PRIMARY KEY (`Id_actividad`);

-- Indices de la tabla `asignacion_actividad`
ALTER TABLE `asignacion_actividad`
  ADD KEY `Actividad` (`Id_actividad`),
  ADD KEY `Bootcamp` (`Id_bootcamp`);

-- Indices de la tabla `asignacion_cuenta`
ALTER TABLE `asignacion_cuenta`
  ADD PRIMARY KEY (`Id_cuenta_bootcamp`),
  ADD KEY `Bootcamp` (`Id_bootcamp`),
  ADD KEY `Cuenta` (`Id_cuenta`);

-- Indices de la tabla `asignacion_encargado`
ALTER TABLE `asignacion_encargado`
  ADD KEY `Encargado` (`Id_cuenta`),
  ADD KEY `Bootcamp` (`Id_bootcamp`);

-- Indices de la tabla `asignacion_equipo`
ALTER TABLE `asignacion_equipo`
  ADD KEY `Equipo` (`Id_equipo`),
  ADD KEY `Relacion` (`Id_cuenta_bootcamp`);

-- Indices de la tabla `asignacion_material`
ALTER TABLE `asignacion_material`
  ADD KEY `Material` (`Id_material`),
  ADD KEY `Bootcamp` (`Id_bootcamp`),
  ADD KEY `Actividad` (`Id_actividad`);

-- Indices de la tabla `asignacion_reto`
ALTER TABLE `asignacion_reto`
  ADD UNIQUE KEY `Reto` (`Id_reto`) USING BTREE,
  ADD KEY `Equipo` (`Id_equipo`) USING BTREE;

-- Indices de la tabla `bootcamp`
ALTER TABLE `bootcamp`
  ADD PRIMARY KEY (`Id_bootcamp`),
  ADD UNIQUE KEY `Codigo bootcamp` (`Codigo`),
  ADD KEY `Campus` (`Id_campus`);

-- Indices de la tabla `campus`
ALTER TABLE `campus`
  ADD PRIMARY KEY (`Id_campus`);

-- Indices de la tabla `carrera`
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`Id_carrera`),
  ADD KEY `Id_facultad` (`Id_facultad`);

-- Indices de la tabla `cuenta`
ALTER TABLE `cuenta`
  ADD PRIMARY KEY (`Id_cuenta`),
  ADD UNIQUE KEY `Cuenta` (`No_cuenta`),
  ADD UNIQUE KEY `Correo` (`Correo`);

-- Indices de la tabla `entrega`
ALTER TABLE `entrega`
  ADD KEY `Actividad` (`Id_actividad`),
  ADD KEY `Equipo` (`Id_equipo`);

-- Indices de la tabla `equipo`
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`Id_equipo`);

-- Indices de la tabla `facultad`
ALTER TABLE `facultad`
  ADD PRIMARY KEY (`Id_facultad`),
  ADD KEY `Id_campus` (`Id_campus`);

-- Indices de la tabla `material`
ALTER TABLE `material`
  ADD PRIMARY KEY (`Id_material`);

-- Indices de la tabla `nombre`
ALTER TABLE `nombre`
  ADD PRIMARY KEY (`Id_nombre`);

-- Indices de la tabla `reto`
ALTER TABLE `reto`
  ADD PRIMARY KEY (`Id_reto`),
  ADD KEY `Bootcamp` (`Id_bootcamp`);

-- Indices de la tabla `usuario`
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`No_cuenta`),
  ADD UNIQUE KEY `Nombre` (`Id_nombre`) USING BTREE,
  ADD KEY `Campus` (`Id_campus`,`Id_facultad`,`Id_carrera`),
  ADD KEY `Id_facultad` (`Id_facultad`),
  ADD KEY `Id_carrera` (`Id_carrera`);

-- AUTO_INCREMENT de la tabla `actividad`
ALTER TABLE `actividad`
  MODIFY `Id_actividad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

-- AUTO_INCREMENT de la tabla `asignacion_cuenta`
ALTER TABLE `asignacion_cuenta`
  MODIFY `Id_cuenta_bootcamp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

-- AUTO_INCREMENT de la tabla `bootcamp`
ALTER TABLE `bootcamp`
  MODIFY `Id_bootcamp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- AUTO_INCREMENT de la tabla `campus`
ALTER TABLE `campus`
  MODIFY `Id_campus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

-- AUTO_INCREMENT de la tabla `carrera`
ALTER TABLE `carrera`
  MODIFY `Id_carrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

-- AUTO_INCREMENT de la tabla `cuenta`
ALTER TABLE `cuenta`
  MODIFY `Id_cuenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

-- AUTO_INCREMENT de la tabla `equipo`
ALTER TABLE `equipo`
  MODIFY `Id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

-- AUTO_INCREMENT de la tabla `facultad`
ALTER TABLE `facultad`
  MODIFY `Id_facultad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

-- AUTO_INCREMENT de la tabla `material`
ALTER TABLE `material`
  MODIFY `Id_material` int(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT de la tabla `nombre`
ALTER TABLE `nombre`
  MODIFY `Id_nombre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

-- AUTO_INCREMENT de la tabla `reto`
ALTER TABLE `reto`
  MODIFY `Id_reto` int(11) NOT NULL AUTO_INCREMENT;

-- Filtros para la tabla `asignacion_actividad`
ALTER TABLE `asignacion_actividad`
  ADD CONSTRAINT `asignacion_actividad_ibfk_1` FOREIGN KEY (`Id_bootcamp`) REFERENCES `bootcamp` (`Id_bootcamp`),
  ADD CONSTRAINT `asignacion_actividad_ibfk_2` FOREIGN KEY (`Id_actividad`) REFERENCES `actividad` (`Id_actividad`);

-- Filtros para la tabla `asignacion_cuenta`
ALTER TABLE `asignacion_cuenta`
  ADD CONSTRAINT `asignacion_cuenta_ibfk_1` FOREIGN KEY (`Id_bootcamp`) REFERENCES `bootcamp` (`Id_bootcamp`),
  ADD CONSTRAINT `asignacion_cuenta_ibfk_2` FOREIGN KEY (`Id_cuenta`) REFERENCES `cuenta` (`Id_cuenta`);

-- Filtros para la tabla `asignacion_encargado`
ALTER TABLE `asignacion_encargado`
  ADD CONSTRAINT `asignacion_encargado_ibfk_1` FOREIGN KEY (`Id_cuenta`) REFERENCES `cuenta` (`Id_cuenta`),
  ADD CONSTRAINT `asignacion_encargado_ibfk_2` FOREIGN KEY (`Id_bootcamp`) REFERENCES `bootcamp` (`Id_bootcamp`);

-- Filtros para la tabla `asignacion_equipo`
ALTER TABLE `asignacion_equipo`
  ADD CONSTRAINT `asignacion_equipo_ibfk_1` FOREIGN KEY (`Id_cuenta_bootcamp`) REFERENCES `asignacion_cuenta` (`Id_cuenta_bootcamp`),
  ADD CONSTRAINT `asignacion_equipo_ibfk_2` FOREIGN KEY (`Id_equipo`) REFERENCES `equipo` (`Id_equipo`);

-- Filtros para la tabla `asignacion_material`
ALTER TABLE `asignacion_material`
  ADD CONSTRAINT `asignacion_material_ibfk_1` FOREIGN KEY (`Id_bootcamp`) REFERENCES `bootcamp` (`Id_bootcamp`),
  ADD CONSTRAINT `asignacion_material_ibfk_2` FOREIGN KEY (`Id_material`) REFERENCES `material` (`Id_material`),
  ADD CONSTRAINT `asignacion_material_ibfk_3` FOREIGN KEY (`Id_actividad`) REFERENCES `actividad` (`Id_actividad`);

-- Filtros para la tabla `asignacion_reto`
ALTER TABLE `asignacion_reto`
  ADD CONSTRAINT `asignacion_reto_ibfk_1` FOREIGN KEY (`Id_reto`) REFERENCES `reto` (`Id_reto`),
  ADD CONSTRAINT `asignacion_reto_ibfk_2` FOREIGN KEY (`Id_equipo`) REFERENCES `equipo` (`Id_equipo`);

-- Filtros para la tabla `bootcamp`
ALTER TABLE `bootcamp`
  ADD CONSTRAINT `bootcamp_ibfk_1` FOREIGN KEY (`Id_campus`) REFERENCES `campus` (`Id_campus`);

-- Filtros para la tabla `carrera`
ALTER TABLE `carrera`
  ADD CONSTRAINT `carrera_ibfk_1` FOREIGN KEY (`Id_facultad`) REFERENCES `facultad` (`Id_facultad`);

-- Filtros para la tabla `cuenta`
ALTER TABLE `cuenta`
  ADD CONSTRAINT `cuenta_ibfk_1` FOREIGN KEY (`No_cuenta`) REFERENCES `usuario` (`No_cuenta`);

-- Filtros para la tabla `entrega`
ALTER TABLE `entrega`
  ADD CONSTRAINT `entrega_ibfk_1` FOREIGN KEY (`Id_actividad`) REFERENCES `actividad` (`Id_actividad`),
  ADD CONSTRAINT `entrega_ibfk_2` FOREIGN KEY (`Id_equipo`) REFERENCES `equipo` (`Id_equipo`);

-- Filtros para la tabla `facultad`
ALTER TABLE `facultad`
  ADD CONSTRAINT `facultad_ibfk_1` FOREIGN KEY (`Id_campus`) REFERENCES `campus` (`Id_campus`);

-- Filtros para la tabla `reto`
ALTER TABLE `reto`
  ADD CONSTRAINT `reto_ibfk_1` FOREIGN KEY (`Id_bootcamp`) REFERENCES `bootcamp` (`Id_bootcamp`);

-- Filtros para la tabla `usuario`
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`Id_nombre`) REFERENCES `nombre` (`Id_nombre`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`Id_campus`) REFERENCES `campus` (`Id_campus`),
  ADD CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`Id_facultad`) REFERENCES `facultad` (`Id_facultad`),
  ADD CONSTRAINT `usuario_ibfk_4` FOREIGN KEY (`Id_carrera`) REFERENCES `carrera` (`Id_carrera`);
COMMIT;