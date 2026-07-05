<div align="center">

   <img src="./frontend/public/logo.png" alt="TÚ ASESORÍA Logo" width="600" />
   
  > 🚧 **Sitio actualmente en fase de desarrollo**
  
  **Conectando el Conocimiento Universitario.**

  <p align="center">
    <a href="#la-problemática">La Problemática</a> •
    <a href="#nuestra-solución">Nuestra Solución</a> •
    <a href="#tecnologías">Tecnologías</a> •
    <a href="#funcionalidades-principales">Funcionalidades</a> •
    <a href="#despliegue-local">Despliegue Local</a>
  </p>
</div>

---

## La Problemática

La vida universitaria es un desafío constante. A diario, miles de estudiantes se enfrentan a materias filtro, conceptos matemáticos complejos o lenguajes de programación abrumadores. ¿El mayor problema? **El aislamiento académico.**

Actualmente, cuando un estudiante se estanca, depende de la disponibilidad limitada de sus profesores oficiales o de buscar respuestas genéricas en internet. Por otro lado, existen estudiantes excepcionales (y profesores particulares) que dominan estas materias pero **no tienen una plataforma centralizada y confiable** para ofrecer su ayuda, compartir su conocimiento y generar ingresos extra o reputación académica.

El conocimiento está disperso, los horarios chocan y no existe una garantía de calidad.

## Nuestra Solución

**TÚ ASESORÍA** (anteriormente INTRU-HUB) es la respuesta de **DevStarLabs** a esta desconexión. 

Hemos construido un ecosistema web integral diseñado exclusivamente para **democratizar el acceso al conocimiento especializado** dentro y fuera de los campus universitarios.

En nuestra plataforma:
- **El que busca encuentra:** Un estudiante puede explorar un catálogo infinito de materias y filtrar a los mejores maestros o tutores basándose en reseñas reales, universidad de origen y disponibilidad.
- **El que sabe, enseña:** Cualquiera con el conocimiento necesario puede construir un perfil público, destacar sus logros y ofrecer tutorías (diferenciando a los Tutores de una sola materia, de los Maestros que dominan múltiples áreas).
- **Confianza Cero-Fricción:** Un sistema de reputación (reseñas y estrellas) garantiza que siempre encuentres la ayuda de mayor calidad posible.

---

## Tecnologías

Para resolver este problema a gran escala, **DevStarLabs** ha diseñado una arquitectura completamente desacoplada y moderna:

| Componente | Tecnología | Propósito |
|------------|------------|-----------|
| **Backend** | ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white) | API REST robusta, Eloquent ORM, Validaciones complejas y reglas de negocio. |
| **Frontend** | ![Vue.js](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D) | Single Page Application (SPA) reactiva con Composition API, TailwindCSS y Pinia. |
| **Base de Datos**| ![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white) | Alojada en Neon (Serverless), garantizando escalabilidad e integridad relacional. |
| **Orquestación**| ![Docker](https://img.shields.io/badge/Docker-2CA5E0?style=for-the-badge&logo=docker&logoColor=white) | Entornos homogéneos a través de Docker Compose para un desarrollo sin fricciones. |

---

## Funcionalidades Principales

### 1. El Explorador Definitivo
Un motor de búsqueda avanzado con interfaz de cuadrículas que categoriza dinámicamente la información en tres pilares:
- **Materias:** Navegación por áreas de conocimiento.
- **Maestros:** Profesores especializados que imparten más de 1 materia.
- **Tutores:** Estudiantes universitarios enfocados en dominar 1 sola materia.

### 2. Perfiles Públicos Dinámicos
Cada asesor cuenta con una tarjeta de presentación digital que incluye:
- Biografía y áreas de experiencia.
- Distintivo visual (Maestro vs Tutor).
- Historial de reseñas públicas (100% reales) y calificación promedio calculada matemáticamente en el backend.
- Botón de acción rápida para iniciar un chat.

### 3. Optimización en Memoria (Caché)
Para garantizar una experiencia veloz e ininterrumpida, implementamos sistemas de caché del lado del cliente usando Pinia, reduciendo la carga del servidor en consultas frecuentes (como los tutores destacados del Home).

---

## Equipo de Desarrollo

| Nombre | Rol | Contribución |
| :--- | :--- | :--- |
| **Gabriel Chacón Arellano** | Scrum Master y Desarrollador Full Stack | Responsable de la gestión del proyecto bajo la metodología Scrum, el diseño y modelado de la base de datos, así como del desarrollo del backend y frontend. |
| **Israel Ramírez Morales** | Desarrollador Full Stack | Colaboró en el diseño de la base de datos y en el desarrollo del backend y frontend de la aplicación. |


---

<div align="center">
  <i>Diseñando el futuro del aprendizaje colaborativo.</i><br>
</div>
