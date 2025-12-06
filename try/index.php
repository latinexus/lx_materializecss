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

use Latinexus\Materialize\MatCss;

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

$mat = new MatCss();

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
$selectOptionsFromArray = $mat->mat_select_list($sampleSelectArray, 'id', 'nombre');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MatCss - Ejemplos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <style>
        body{ padding:20px; }
        .demo-section{ margin-bottom:30px; }
    </style>
</head>
<body>

<div class="container">
    <h4>Demostración de MatCss</h4>

    <div class="demo-section">
        <h5>Inputs básicos</h5>
        <form method="post" id="form1" enctype="multipart/form-data">
            <div class="row">
                <?php
                echo $mat->mat_input("Nombre","nombre", ["env"=>"col s12 l4"]);
                echo $mat->mat_input("Apellido", "apellido", ["env"=>"col s12 l4"]);
                echo $mat->mat_input("Email", "email", ["env"=>"col s12 l4","type"=>"email"]);
                ?>
            </div>

            <div class="row">
                <?php
                // Select
                echo $mat->mat_select('Seleccione una opción', 'opciones', $options, 'col s12 l6');

                // Textarea
                echo $mat->mat_textarea('Comentario', 'comentario', 'col s12 l6', '', 'comentario', '', 240);
                ?>
            </div>

            <div class="row">
                <?php
                // Checkbox y Radio
                echo '<div class="col s12 l4">' . $mat->mat_check('Aceptar términos', 'acepto', '1', 'acepto', '', '') . '</div>';
                echo '<div class="col s12 l4">' . $mat->mat_radio('Opción A', 'radio_demo', 'A', 'rA', '', 'checked') . '</div>';
                echo '<div class="col s12 l4">' . $mat->mat_radio('Opción B', 'radio_demo', 'B', 'rB') . '</div>';
                ?>
            </div>

            <div class="row">
                <?php
                // File
                echo $mat->mat_file('Subir archivo', 'mi_archivo', 'col s12 l6', 'perm_media');

                // Datepicker
                echo $mat->mat_picker('Fecha de inicio', 'fecha_inicio', ['tipo'=>'date', 'id'=>'fecha_inicio', 'envoltura'=>'col s12 l6']);
                ?>
            </div>

            <div class="row">
                <div class="col s12">
                    <button class="btn waves-effect waves-light" type="submit" name="action">Enviar
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="demo-section">
        <h5>Cards de ejemplo</h5>
        <div class="row">
            <div class="col s12 m6">
                <?php echo $mat->mat_card('Título simple', '<p>Contenido simple dentro de la card.</p>'); ?>
            </div>
            <div class="col s12 m6">
                <?php echo $mat->mat_card_reveal($cardReveal); ?>
            </div>
        </div>

        <div class="row">
            <div class="col s12 m6">
                <?php echo $mat->mat_card_horizontal($cardHorizontal); ?>
            </div>
        </div>
    </div>

    <!-- Ejemplos avanzados añadidos -->
    <div class="demo-section">
        <h5>Ejemplos avanzados</h5>

        <div class="row">
            <div class="col s12 m6">
                <h6>Colección (mat_filas)</h6>
                <?php
                $filaOptions = ['nombre' => 'nombre', 'id' => 'id', 'link' => true, 'delete', 'edit', 'otro'];
                echo $mat->mat_filas($sampleCollection, $filaOptions);
                ?>
            </div>

            <div class="col s12 m6">
                <h6>Select generado desde array</h6>
                <?php echo $mat->mat_select('Selecciona desde array', 'from_array', $selectOptionsFromArray, 'col s12'); ?>
            </div>
        </div>

        <div class="row">
            <div class="col s12 m6">
                <h6>File con callback</h6>
                <?php echo $mat->mat_file_ob('Subir imagen', 'mi_archivo2', ['env' => 'col s12 l6', 'ico' => 'photo', 'funChg' => 'console.log("change")']); ?>
            </div>

            <div class="col s12 m6">
                <h6>Hora (timepicker)</h6>
                <?php echo $mat->mat_picker('Hora', 'hora', ['tipo' => 'time', 'id' => 'hora', 'envoltura' => 'col s12 l6']); ?>
            </div>
        </div>

        <div class="row">
            <div class="col s12 m6">
                <h6>Inputs especiales</h6>
                <?php
                echo $mat->mat_input('Usuario', 'usuario', ['env' => 'col s12 l6', 'required' => 1, 'value' => 'juan']);
                echo $mat->mat_input('Código', 'codigo', ['env' => 'col s12 l6', 'readonly' => 1, 'value' => 'ABC123']);
                ?>
            </div>
        </div>
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

</body>
</html>
