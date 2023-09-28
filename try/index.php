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

$mat = new MatCss();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MatCss</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</head>
<body>
    <form method="post" id="form1" enctype="multipart/form-data">
        <div class="row">
            <?php
            echo $mat->mat_input("Nombre","nombre", ["env"=>"col s12 l3"]);
            echo $mat->mat_input("Apellido", "apellido", ["env"=>"col s12 l3"])
            ?>
        </div>
    </form>
</body>
</html>


