<?php
/**
 * Creator: Eric Larrea
 * E-mail: elapez@gmail.com
 * From: www.latinex.us
 * Date: 27/09/2023
 * Time: 07:01 p. m.
 * Proyecto: cp_materializecss
 */
require_once '../vendor/autoload.php'; // Carga el archivo autoload generado por Composer

use Latinexus\Materialize\TailCss;

// Constantes para la demo local (normalmente definidas en el bootstrap de la app)
if (!defined('E_URL')) {
    // construir E_URL dinámicamente para incluir puerto si está presente (útil con php -S)
    $scheme = 'http';
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $scheme = 'https';
    } elseif (!empty($_SERVER['REQUEST_SCHEME'])) {
        $scheme = $_SERVER['REQUEST_SCHEME'];
    }
    $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
    define('E_URL', rtrim($scheme . '://' . $host, '/') . '/');
}
if (!defined('E_VIEW')) {
    define('E_VIEW', 'inicio');
}


$tail = new TailCss();

// Datos de ejemplo
$options = [
    '1' => 'Opción 1',
    '2' => 'Opción 2',
    '3' => 'Opción 3'
];

$cardReveal = [
    'titulo' => 'Card con reveal',
    'texto' => '<p>Texto que se muestra en la parte oculta de la card.</p>',
    // ruta relativa desde la raíz del documento (try/) para que php -S pueda servirla
    'img' => 'img/maxima.jpg',
    'alt' => 'Imagen demo',
    'link' => 'https://example.com',
    'textoLink' => 'Ir al enlace'
];

$cardHorizontal = [
    'titulo' => 'Card horizontal',
    'texto' => '<p>Contenido de ejemplo en la card horizontal.</p>',
    'img' => 'img/maxima.jpg',
    'link' => 'https://example.com'
];

// Ejemplos adicionales para la demo: colección de objetos y array para mat_select_list
$sampleCollection = [];

for ($i = 1; $i <= 4; $i++) {
    $o = new \stdClass();
    $o->id = $i;
    $o->nombre = 'Item ' . $i;
    $o->nombre2 = 'Sub ' . $i;
    $o->estado = ($i % 2 === 0) ? 'A' : 'P';
    $sampleCollection[] = $o;
}

$sampleSelectArray = [
    ['id' => 10, 'nombre' => 'Alpha'],
    ['id' => 11, 'nombre' => 'Bravo'],
    ['id' => 12, 'nombre' => 'Charlie']
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TailCss - Ejemplos</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body{ padding:20px; }
        .demo-section{ margin-bottom:30px; }
    </style>
</head>
<body>

<div class="container">

    <!-- TailCss demo -->
    <div class="demo-section">
        <h5>TailCss - Componentes (Tailwind)</h5>
        <?php
        // demo de TailCss

        echo $tail->tail_input('Nombre (Tail)', 't_nombre', ['env'=>'mb-2', 'placeholder'=>'Nombre tail']);
        echo $tail->tail_select('Opciones (Tail)', 't_opc', ['a'=>'Alpha','b'=>'Beta'], 'mb-2');
        echo $tail->tail_textarea('Comentario (Tail)', 't_com', 'mb-2', '', 't_com', '', 240);
        echo '<div class="mb-2">' . $tail->tail_check('Acepto (Tail)', 't_acepto', '1', 't_acepto', '', '') . '</div>';
        echo '<div class="mb-2">' . $tail->tail_radio('Radio A (Tail)', 't_rad', 'A', 't_rA', '', '') . $tail->tail_radio('Radio B (Tail)', 't_rad', 'B', 't_rB', '', '') . '</div>';

        echo $tail->tail_filas((function(){ $arr=[]; for($i=1;$i<=3;$i++){ $o=new stdClass(); $o->id=$i; $o->nombre='Item '.$i; $arr[]=$o;} return $arr; })(), ['nombre'=>'nombre','id'=>'id','link'=>true]);

        echo $tail->collapsible([['P1','Contenido P1'],['P2','Contenido P2']]);
        ?>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar selects
        var elems = document.querySelectorAll('select');
        if(elems.length>0){
            M.FormSelect.init(elems);
        }

        // Inicializar datepickers y timepickers
        var elemsDate = document.querySelectorAll('.datepicker');
        if(elemsDate.length>0){
            M.Datepicker.init(elemsDate, {autoClose:true});
        }

        var elemsTime = document.querySelectorAll('.timepicker');
        if(elemsTime.length>0){
            M.Timepicker.init(elemsTime, {});
        }

        // Forzar actualización de labels en inputs con valor (si es necesario)
        M.updateTextFields();
    });
</script>

<!-- TailCli for TailCss behaviors -->
<script src="js/tailCli.js"></script>
</body>
</html>
