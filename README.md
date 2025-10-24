# Sistema de Ejercicios PHP

Este proyecto contiene una serie de ejercicios implementados en PHP que incluyen:

## Funcionalidades

1. **Sistema de Login**
   - Autenticación básica con usuario y contraseña
   - Validación en PHP
   - Manejo de sesiones
   - Redirección después del login

2. **Calculadora de Áreas y Volúmenes**
   - Cálculo de área y volumen de cilindros
   - Cálculo de área y perímetro de rectángulos
   - Validación de entrada de datos
   - Visualización clara de resultados

3. **Identificador de Cuadrantes**
   - Sistema para identificar cuadrantes en el plano cartesiano
   - Validación de coordenadas X, Y
   - Visualización gráfica del plano cartesiano
   - Identificación de casos especiales (ejes, origen)

## Tecnologías Utilizadas
- PHP
- MySQL
- HTML5
- CSS3
- JavaScript

## Estructura del Proyecto
```
/
├── css/
│   └── style.css
├── includes/
│   └── db.php
├── calculos.php
├── cuadrantes.php
├── dashboard.php
├── database.sql
├── index.php
└── logout.php
```

## Configuración
1. Importar el archivo `database.sql` en tu servidor MySQL
2. Configurar las credenciales de la base de datos en `includes/db.php`
3. Colocar los archivos en tu servidor web
4. Acceder a través de `index.php`

## Credenciales de Prueba
- Usuario: admin
- Contraseña: admin123

## Control de Versiones
El proyecto utiliza Git para el control de versiones, con ramas separadas para cada funcionalidad:
- `main`: Rama principal
- `calculo-areas`: Funcionalidad de cálculos
- `cuadrantes`: Sistema de identificación de cuadrantes

## Autor
[Tu Nombre]

## Versión
v1.0.0