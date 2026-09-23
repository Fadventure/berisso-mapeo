#  Berisso Market

Directorio digital de negocios locales y servicios de Berisso. Una plataforma donde los comerciantes pueden registrar sus negocios y los vecinos pueden encontrarlos fácilmente con ubicación en el mapa, horarios, contacto y redes sociales.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Leaflet](https://img.shields.io/badge/Leaflet-199900?style=for-the-badge&logo=leaflet&logoColor=white)

---

##  Descripción

**Berisso Market** es un directorio web de negocios locales de la ciudad de Berisso, Buenos Aires. Su objetivo es conectar a los comerciantes con los vecinos, permitiéndoles publicar sus negocios con toda la información relevante y brindando a los usuarios una forma sencilla de encontrarlos.

### Características principales

-  **Publicación de negocios** por parte de los comerciantes
-  **Ubicación interactiva** con mapa (Leaflet.js + OpenStreetMap)
-  **Horarios de atención** configurables por día
-  **Galería de imágenes** por negocio
-  **Búsqueda y filtrado** por categoría, nombre o dirección
-  **Panel de usuario** para gestionar los propios negocios
-  **Panel de administración** para aprobar/rechazar negocios
-  **Redes sociales** y datos de contacto de cada negocio
-  **Botones directos** a Google Maps y OpenStreetMap

---

##  Demo

🔗 **Aplicación en vivo**:  [https://berisso-market.com](https://berisso-market.com) _(si está disponible)_

**Credenciales de prueba:**

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | admin@berisso.com | admin123 |
| Usuario de prueba | test@example.com | password |

---

##  Tecnologías utilizadas

### Backend
- **Laravel 12** - Framework PHP
- **PHP 8.2**
- **MySQL** - Base de datos

### Frontend
- **Blade** - Motor de plantillas de Laravel
- **Tailwind CSS** - Framework de estilos
- **Vite** - Compilación de assets
- **JavaScript vanilla** - Interactividad

### Mapas
- **Leaflet.js** - Mapas interactivos
- **OpenStreetMap** - Datos de mapas
- **Nominatim** - Geocodificación de direcciones

---

## Instalación

### Requisitos previos

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL

### Pasos

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/Fadventure/berisso-mapeo.git
   cd berisso-mapeo