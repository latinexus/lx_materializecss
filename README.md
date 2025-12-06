# lx_materializecss

Componentes de MaterializeCSS encapsulados en etiquetas HTML para uso desde PHP.

Esta librería contiene una clase principal `MatCss` (en `src/MatCss.php`) destinada a facilitar la creación de componentes de interfaz realizados con MaterializeCSS mediante métodos PHP que generan las etiquetas HTML correspondientes.

Resumen rápido
- MatCss: clase que expone métodos para generar componentes de MaterializeCSS como botones, formularios, contenedores, etc.
- HtmlTag: clase auxiliar (copia temporal disponible en `vendor/latinexus/lx_htmltag/src/HtmlTag.php`) que normaliza la creación de etiquetas HTML; los cambios definitivos deben hacerse en su repositorio original.

Constantes importantes y reglas
- E_URL
  - Debe contener la URL base completa hasta el punto de entrada de la aplicación, incluyendo protocolo y la barra final (/).
  - Ejemplos válidos: `https://midominio.com/`, `https://sub.dominio.com/`, `http://localhost/proyecto/public_html/`
  - Esta constante se usa como prefijo para construir rutas absolutas que se envían al cliente.

- E_VIEW
  - Debe ser una cadena simple que identifica una vista o ruta relativa sin barras `/` ni espacios.
  - Solo se permiten caracteres alfanuméricos, guion medio `-` y subrayado `_`.
  - Ejemplos válidos: `inicio_admin`, `contacto`, `user-profile`

Cómo se combinan
- Cuando se necesita construir una URL de vista, la combinación esperada es `E_URL . E_VIEW` o `E_URL . $vista` donde `$vista` respeta las reglas de `E_VIEW`.
- Ejemplos resultantes: `https://midominio.com/inicio_admin`, `http://localhost/proyecto/public_html/contacto`

Nota sobre comportamiento interno
- En `MatCss` se ha sustituido la concatenación directa por un método dedicado: `$v = $this->resolveVistaUrl($datos);`
  - `resolveVistaUrl` debe encargarse de resolver la URL final respetando las reglas anteriores.
  - La clase no depende de archivos de entorno tipo `.env`. Si no existe `E_URL` se espera que el entorno de la aplicación lo haya resuelto antes de instanciar `MatCss`.

Buenas prácticas
- Define `E_URL` de forma centralizada en el bootstrap de la aplicación (antes de usar `MatCss`).
- Valida `E_VIEW` o utiliza `resolveVistaUrl` para asegurar que cumple las restricciones (solo `[A-Za-z0-9_-]`).
- Usa rutas absolutas para evitar ambigüedades cuando se sirve contenido desde subdirectorios.

Ejemplo mínimo de uso
```php
// En el bootstrap de la aplicación
define('E_URL', 'http://localhost/proyecto/public_html/');
define('E_VIEW', 'inicio');

// En el código que usa MatCss
$mat = new \MatCss();
// ... construir datos ...
$v = $mat->resolveVistaUrl(['vista' => 'contacto']); // -> 'http://localhost/proyecto/public_html/contacto'
```

Notas sobre `HtmlTag`
- `HtmlTag` está incluida aquí como copia para facilitar pruebas y desarrollo, pero su origen real está en otro repositorio (`latinexus/lx_htmltag`).
- Si detectas mejoras o correcciones en `HtmlTag`, hazlas en su repositorio upstream y sincroniza la dependencia.

Contribución y pruebas
- Antes de enviar cambios, comprueba que las constantes esperadas están definidas en el entorno de ejecución.
- Ejecuta cualquier linter o comprobación de PHP que uses en tu proyecto para validar que la API pública no cambió.

Licencia
- Revisa la licencia en los archivos `composer` o en la cabecera de las fuentes si necesitas redistribuir o modificar.

Contacto
- Si necesitas ayuda con la integración o tienes dudas sobre `resolveVistaUrl` o validaciones, añade un issue o comentario en el repositorio.
