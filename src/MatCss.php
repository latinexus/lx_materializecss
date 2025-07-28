<?php
namespace Latinexus\Materialize;

use Latinexus\Html\HtmlTag;

class MatCss
{
    /**
     * Constructor de la clase
     */
    public function __construct()
    {
        // inicializaciones necesarias
    }

    /**
         * @param $contenido
         * @param string $envoltura
         * @return string
         */
    public function mat_form($contenido, $envoltura="")
    {
        $b = new HtmlTag();
        $retorno = "";

        if (empty($envoltura)) {
            $retorno = $b->blk($contenido,["class"=>"input-field col s12"]);
        } else {
            // si $envoltura es -1, entonces no pongo envoltura
            if ($envoltura != -1) {
                $valorEnvoltura = "input-field ".$envoltura;
                $retorno = $b->blk($contenido,["class"=>$valorEnvoltura]);
            }
            else
            {
                $retorno = $contenido;
            }
        }

        return $retorno;
    }

    /**
     * MATERIALIZECSS ( INPUT )
     * Devuelve un <select></select> con formato para materializecss
     *
     * @param $label
     * @param $name
     * @param string $type
     * @param string $envoltura
     * @param string $value
     * @param string $id
     * @return string
     */
    public function mat_input($label, $name, $datos = ["a"])
    {
        $b = new HtmlTag();
        $retorno = "";
        $opcionales = ["required", "readonly", "disabled"];
        $arregloOpciones = [];


        if(count($datos) > 0)
        {
            $type = isset($datos["type"]) ?  $datos["type"] : "text";
            if(isset($datos["env"]))
            {
                $envoltura = $datos["env"];
            }
            else
            {
                $envoltura = isset($datos["envoltura"]) ? $datos["envoltura"] : "";
            }
            $value = isset($datos["value"]) ? $datos["value"] : "";
            $id = isset($datos["id"]) ? $datos["id"] : $name;
            $clase = isset($datos["class"]) ? $datos["class"] : "";
            $list = isset($datos["list"]) ? $datos["list"] : ""; // $lista será el ID del <datalist> a cargar
            $min = isset($datos["min"]) ? $datos["min"] : "";
            $max = isset($datos["max"]) ? $datos["max"] : "";
            $maxlength = isset($datos["maxlength"]) ? $datos["maxlength"] : "";
            $minlength = isset($datos["minlength"]) ? $datos["minlength"] : "";
            $classRequired = isset($datos["classR"]) ? $datos["classR"] : "tColor2";
            $style = isset($datos["style"]) ? $datos["style"] : "";

            foreach ($opcionales as $opt)
            {
                if(isset($datos[$opt]))
                {
                    if($opt == "required")
                    {
                        $label .= ' <span class="' . $classRequired . '">*</span>';
                    }
                    $arregloOpciones[$opt] = "1";
                }
            }
        }

        switch($clase)
        {
            case "datepicker":
                $clsCss = "datepicker";
                break;
            case "timepicker":
                $clsCss = "timepicker";
                break;
            case "-1":
                $clsCss = "";
                break;
            default:
                $clsCss = "validate ".$clase;
        }

        $arregloOpciones["id"] = $id;
        $arregloOpciones["name"] = $name;
        $arregloOpciones["class"] = $clsCss;
        $arregloOpciones["value"] = $value;
        $arregloOpciones["type"] = $type;
        $arregloOpciones["style"] = $style;

        if(!empty($minlength))
        {
            $arregloOpciones["minlength"] = $minlength;
        }

        if(!empty($maxlength))
        {
            $arregloOpciones["maxlength"] = $maxlength;
        }

        if($arregloOpciones["type"] == "number")
        {
            if(!empty($min))
            {
                $arregloOpciones["min"] = $min;
            }
            if(!empty($max))
            {
                $arregloOpciones["max"] = $max;
            }
        }

        if(!empty($list)){$arregloOpciones["list"] = $list;}

        $retorno .= $b->noBlk($arregloOpciones).PHP_EOL;
        $retorno .= $b->blk($label,["for"=>$id], "label").PHP_EOL;

        return $this->mat_form($retorno, $envoltura);
    }


    /**
     * MATERIALIZECSS ( SELECT )
     * Devuelve un <select></select> con formato para materializecss
     *
     * @param $label
     * @param $name
     * @param array $contenido
     * @param string $envoltura
     * @param string $default
     * @param string $id
     * @return string
     */
    public function mat_select($label, $name, $contenido, $envoltura = "", $selected="", $default="", $id="", $extra="")
    {
        $b = new HtmlTag();
        $retorno = "";

        if (!empty($id)) {
            $idShow = $id;
        } else {
            $idShow = $name;
        }

        if(empty($default))
        {
            $retorno .= '<select name="' . $name . '" id="' . $idShow . '" ' . $extra . '>'.PHP_EOL;
            $label = '<label for="' . $idShow . '">' . $label . '</label>'.PHP_EOL;
        }
        else
        {
            $retorno .= '<div><small>'.$label.':</small></div>';
            $retorno .= '<select name="' . $name . '" id="' . $idShow . '" class="browser-default" ' . $extra . '>'.PHP_EOL;
            $label = "";
        }

        if (is_array($contenido))
        {

            if(empty($selected))
            {
                $retorno .= '<option value="" disabled selected>Seleccionar</option>'.PHP_EOL;
            }

            foreach ($contenido as $id_val => $val)
            {
                if($selected == $id_val)
                {
                    $retorno .= '<option value="' . $id_val . '" selected="">' . $val . '</option>'.PHP_EOL;
                }
                else
                {
                    $retorno .= '<option value="' . $id_val . '">' . $val . '</option>'.PHP_EOL;
                }

            }
        }
        else
        {
            $retorno .= '<option value="" disabled selected>Sin elementos</option>'.PHP_EOL;
        }

        $retorno .= '</select>'.PHP_EOL;
        $retorno .= $label;

        return $this->mat_form($retorno, $envoltura);
    }

    /**
     * MATERIALIZECSS ( TEXTAREA )
     * Devuelve un <textarea></textarea> con formato para materializecss
     *
     * Requiere "$('#textarea1').trigger('autoresize');" dentro del script de la vista si el valor es llenado dinámicamente
     *
     * @param $label
     * @param $name
     * @param string $type
     * @param string $envoltura
     * @param string $value
     * @param string $id
     * @param string $clase
     * @param int $largo
     * @return string
     */
    public function mat_textarea($label, $name, $envoltura = "", $value ="", $id="", $clase="materialize-textarea", $largo="")
    {
        $b = new HtmlTag();
        $retorno = "";

        if (!empty($id)) {
            $idShow = $id;
        } else {
            $idShow = $name;
        }

        switch($clase)
        {
            case "materialize-textarea":
                $clsCss = "materialize-textarea";
                break;
            default:
                $clsCss = "materialize-textarea ".$clase;
        }


        if(empty($largo))
        {
            $retorno .= $b->blk($value, ["id"=>$idShow, "name"=>$name, "class"=>$clsCss], "textarea");
        }
        else
        {
            $retorno .= $b->blk($value, ["id"=>$idShow, "name"=>$name, "class"=>"counter " . $clsCss, "data-length"=>$largo], "textarea");
        }


        $retorno .= $b->blk($label, ["for"=>$idShow], "label");

        return $this->mat_form($retorno, $envoltura);
    }

    /**
     * MATERIALIZECSS ( CHECKBOX ) NUEVA VERSIÓN DE MATERIALIZE
     * Devuelve un "checkbox" con formato para materializecss
     *
     * @param $label
     * @param $name
     * @param string $value
     * @param string $id
     * @param string $clase
     * @param string $checked
     * @return string
     */
    public function mat_check($label, $name, $value ="", $id="", $clase="", $checked="")
    {
        $b = new HtmlTag();
        $valueShow = !empty($value) ? $value : 1;
        $idShow = !empty($id) ? $id : $name;
        $classShow = !empty($clase) ? $clase : "filled-in";

        if(empty($checked))
        {
            $params = ["name"=>$name, "id"=>$idShow, "type"=>"checkbox", "value"=>$valueShow, "class"=>$classShow];
        }
        else
        {
            $params = ["name"=>$name, "id"=>$idShow, "type"=>"checkbox", "value"=>$valueShow, "class"=>$classShow, "checked"=>"checked"];
        }

        // input
        $inp = $b->noBlk($params);

        // label en este caso se usa <SPAN>
        $spa = $b->blk($label, [], "span");

        //Agrupamiento con el <LABEL>
        $lab = $b->blk($inp.$spa, ["for"=>$idShow], "label");


        $retorno = $b->blk($lab, [], "p");

        return $this->mat_form($retorno, -1);
    }

    /**
     * CREAR UNA "CARD"
     * @param $titulo
     * @param $contenido
     * @return string
     */
    public function mat_card($titulo, $contenido)
    {
        $b = new HtmlTag();
        $title = $b->blk($titulo, ["class"=>"card-title", "span"]);
        $cont =  $b->blk($title . $contenido, ["class"=>"card-content"]);
        return $b->blk($cont,["class"=>"card"]);
    }

    /** CARD REVEAL */
    public function mat_card_reveal($arr)
    {
        $b = new HtmlTag();
        $cont = "";

    //    $arr['titulo']
    //    $arr['texto']
    //    $arr['img']
    //    $arr['alt']
    //    $arr['link']
    //    $arr['textoLink']

        $i = '<img src="'.$arr['img'].'" class="activator" '.altImg($arr['alt']).' />';
        $cont .= $b->blk($i,["class"=>"card-image waves-effect waves-block waves-light"]);

        $t = '<span class="card-title activator grey-text text-darken-4">'.$arr['titulo'].'<i class="material-icons right">more_vert</i></span>';
        $t .= (isset($arr['link']) && !empty($arr['link'])) ? '<p><a href="'.$arr['link'].'">'.$arr['textoLink'].'</a></p>' : '';
        $cont .= $b->blk($t,["class"=>"card-content"]);

        $r = '<span class="card-title grey-text text-darken-4">'.$arr['titulo'].'<i class="material-icons right">close</i></span>';
        $r .= !empty($arr['texto']) ? $arr['texto'] : "";
        $cont .= $b->blk($r,["class"=>"card-reveal letra4"]);


        return $b->blk($cont,["class"=>"card"]);
    }

    /** HORIZONTAL CARD */
    public function mat_card_horizontal($arr)
    {
        /**
         *  $arr['titulo'] --> Titulo de la tarjeta
         *  $arr['texto'] --> Texto de la tarjeta
         *  $arr['img'] --> Imagen de la tarjeta
         *  $arr['link'] --> Enlace de la tarjeta
         *  $arr['env'] --> Etiqueta de envoltura a la tarjeta horizontal
         */
        $b = new HtmlTag();

        $imagen = '<img src="' . E_URL . $arr["img"] . '" '.altImg($arr["titulo"]).' />';
        $img = $b->blk($imagen, ["class"=>"card-image"]);

        $cont = '<h3>'.$arr["titulo"].'</h3>';
        $cont .= $arr["texto"];
        $contenido = $b->blk($cont, ["class"=>"card-content"]);

        if(isset($arr["link"]) && !empty($arr["link"]))
        {
            $enlace = $b->blk('<a href="'.$arr["link"].'"><i class="material-icons">add_circle</i></a>', ["class"=>"card-action"]);
        }
        else
        {
            $enlace = "";
        }

        $stack = $b->blk($contenido.$enlace, ["class"=>"card-stacked"]);

        $car = $img . $stack;

        $retorno = $b->blk($car,["class"=>"card horizontal"]);

        if(isset($arr["env"]))
        {
            return $b->blk($retorno,["class"=>$arr["env"]]);
        }
        else
        {
            return $retorno;
        }

    }

    /**
     *  MATERIALIZECSS ( FILE )
     * @param $titulo
     * @param $name
     * @return string
     */
    public function mat_file($titulo, $name, $envoltura="col s12 l6", $icon="perm_media" )
    {
        $b = new HtmlTag();

        $div1 = '<div class="btn"><span><i class="large material-icons">'.$icon.'</i></span><input id="'.$name.'" name="'.$name.'" type="file"></div>';
        $div2 = '<div class="file-path-wrapper"><input id="file-wrapper" class="file-path validate" type="text" placeholder="'.$titulo.'"></div>';

        return $this->mat_form($div1.$div2, 'file-field '.$envoltura);
    }

    public function mat_file_ob($titulo, $name, $obj=[])
    {
        $b = new HtmlTag();

        $env = isset($obj["env"]) ? $obj["env"] : "col s12 l6";
        $ico = isset($obj["ico"]) ? $obj["ico"] : "perm_media";
        $fun = isset($obj["funChg"]) ? ' onchange="' . $obj["funChg"] . '"' : "";

        $div1 = '<div class="btn"><span><i class="large material-icons">'.$ico.'</i></span><input id="'.$name.'" name="'.$name.'" type="file"></div>';
        $div2 = '<div class="file-path-wrapper"><input id="file-wrapper" class="file-path validate" type="text" placeholder="'.$titulo.'" '.$fun.' ></div>';

        return $this->mat_form($div1.$div2, 'file-field '.$env);
    }

    /** RADIO BUTTON */
    public function mat_radio($label, $name, $value ="1", $id="", $clase="", $checked="")
    {

        $b = new HtmlTag();

        if (!empty($id)) {
            $idShow = $id;
        } else {
            $idShow = $name . uniqid();
        }

        $claseUsar = empty($clase) ? "with-gap" : $clase;

        if(empty($checked))
        {
            $params = ["name"=>$name, "id"=>$idShow, "type"=>"radio", "value"=>$value, "class"=>$claseUsar];
        }
        else
        {
            $params = ["name"=>$name, "id"=>$idShow, "type"=>"radio", "value"=>$value, "class"=>$claseUsar, "checked"=>"checked"];
        }

        $inp = $b->noBlk($params);

        $spa = $b->blk($label, [], "span");

        $lab = $b->blk($inp.$spa, ["for"=>$idShow], "label");

        $retorno = $b->blk($lab, [], "p");

        return $this->mat_form($retorno, -1);
    }

    /**
     *		IMPLEMENTACIÓN
    <script type="text/javascript">

    var dateSpanish = {
    clear:'Borrar',
    today:'Hoy',
    done:'Ok',
    previousMonth:'‹',
    nextMonth:'›',
    months:[
    'Enero',
    'Febrero',
    'Marzo',
    'Abril',
    'Mayo',
    'Junio',
    'Julio',
    'Agosto',
    'Septiembre',
    'Octubre',
    'Noviembre',
    'Diciembre'
    ],
    monthsShort:[
    'Ene',
    'Feb',
    'Mar',
    'Abr',
    'May',
    'Jun',
    'Jul',
    'Ago',
    'Sep',
    'Oct',
    'Nov',
    'Dic'
    ],
    weekdays:[
    'Domingo',
    'Lunes',
    'Martes',
    'Miércoles',
    'Jueves',
    'Viernes',
    'Sábado'
    ],
    weekdaysShort:[
    'Dom',
    'Lun',
    'Mar',
    'Mie',
    'Jue',
    'Vie',
    'Sab'
    ],
    weekdaysAbbrev:[
    'D','L','M','M','J','V','S'
    ]
    };
    var timeSpanish = {
    clear:'Limpiar',
    cancel:'Cancelar',
    done:'Ok'
    };
    var dateIngles = {
    clear:'Clear',
    today:'Today',
    done:'Ok',
    previousMonth:'‹',
    nextMonth:'›',
    months:[
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
    ],
    monthsShort:[
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'May',
    'Jun',
    'Jul',
    'Aug',
    'Sep',
    'Oct',
    'Nov',
    'Dec'
    ],
    weekdays:[
    'Sunday',
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday'
    ],
    weekdaysShort:[
    'Sun',
    'Mon',
    'Tue',
    'Wed',
    'Thu',
    'Fri',
    'Sat'
    ],
    weekdaysAbbrev:[
    'S','M','T','W','T','F','S'
    ]
    };
    var timeIngles = {
    clear:'Clear',
    cancel:'Cancel',
    done:'Ok'
    };


    document.addEventListener('DOMContentLoaded', function() {
    var elemsFecha = document.querySelectorAll('.datepicker');
    var instanciaFecha = M.Datepicker.init(elemsFecha, {
    autoClose:true,
    firstDay:1,
    format:'dd mmm, yyyy',
    setDefaultDate:true,
    i18n:dateSpanish,
    onClose:function()
    {
    var actDT = this.date;
    var fechaIso = JSON.stringify([actDT.getFullYear(), actDT.getMonth()+1, actDT.getDate()]);
    $('#datepickerTrue').val(fechaIso);

    }
    });
    });


    </script>
     *
     */
    public function mat_picker($label, $name, $datos = ["a"])
    {
        /**
         *      echo mat_picker("Primer cobro", "primerCobro", ["otherTrueId"=>"primerCobro"]);
         */

        if(count($datos) > 0)
        {
            if(isset($datos["tipo"]))
            {
                $tipo = ($datos["tipo"] == "time") ? "timepicker" : "datepicker";
            }
            else
            {
                $tipo = "datepicker";
            }

            $envoltura = isset($datos["envoltura"]) ? $datos["envoltura"] : "";
            $value = isset($datos["value"]) ? $datos["value"] : "";
            $id = isset($datos["id"]) ? $datos["id"] : $name;
            $class = isset($datos["class"]) ? $tipo." ".$datos["class"] : $tipo;
            $true = isset($datos["true"]) ? $datos["true"] : "";
            $otherTrueId = isset($datos["otherTrueId"]) ? $datos["otherTrueId"] : "";

        }

        $contenido = '<input type="text" value="'.$value.'" name="'.$name.'" id="'.$id.'" class="'.$class.'">';
        $contenido .= '<label for="'.$id.'">'.$label.'</label>';
        $contenido .= '<input type="hidden" name="'.$tipo.'True'.$otherTrueId.'" id="'.$tipo.'True'.$otherTrueId.'" value="'.$true.'" />';

        return $this->mat_form($contenido, $envoltura);
    }

    /**
    $n = $datos["nombre"]
    $i = $datos["id"]
    $c = $datos["class"]
    $l = datos["link"]
    $d = $datos["delete"]
    $v = $datos["vista"]
     */
    public function mat_colection($c, $datos = ["a"], $p="")
    {
        if($p==="")
        {
            $obj = $c::all();
        }
        else
        {
            $obj = $c;
        }

        return $this->mat_filas($obj, $datos);
    }

    /** ***** VERSIÓN ACTUALIZADA POR VENPAR PARA ADMITIR ICONO DE BORRADO *****
     * @param $obj
     * @param array $datos
     * @return mixed
     */
    public function mat_filas($obj, $datos = [])
    {
        $b = new HtmlTag();
        $lista = "";

        if(count($obj) > 0)
        {
            $n = array_key_exists("nombre", $datos) ? $datos["nombre"] : "nombre";
            $n2 = array_key_exists("nombre2", $datos) ? $datos["nombre2"] : "";
            $i = array_key_exists("id", $datos) ? $datos["id"] : "id";
            $c = array_key_exists("class", $datos) ? $datos["class"] : "oscuro";
            $l = array_key_exists("link", $datos) ? $datos["link"] : TRUE;
            $d = in_array("delete", $datos);
            $e = in_array("edit", $datos);
            $o = in_array("otro", $datos); // Para añadir un ícono adicional
            $ol = array_key_exists("ol", $datos) ? $datos["ol"] : "select";
            $od = array_key_exists("od", $datos) ? $datos["od"] : "delete";
            $v = array_key_exists("vista", $datos) ? E_URL . $datos["vista"] : E_URL . E_VIEW;
            $m = array_key_exists("msg", $datos) ? $datos["msg"] : "De verdad lo quieres borrar, esta acción no podrá deshacerse";
            if(in_array("inv", $datos))
            {
                $obj = $obj->sortDesc();
            }


            foreach($obj as $elem)
            {
                $lista .= '<li class="collection-item">';

                if($l)
                {
                    /**
                     * Lista y botones de viñetas
                     */
                    if(is_bool($l))
                    {
                        $lista .= '<a class="'.$c.'" href="'.$v.'?a=select&id='.$elem->$i.'">' . $elem->$n . " - " . $elem->$n2 . '</a>';

                        if($d)
                        {
                            $lista .= '<a href="'.$v.'?a=delete&id='.$elem->$i.'" class="secondary-content '.$c.' mIzq10" onclick="if(confirma(\''.$m.'\')){return true;}else{return false;}"><i class="material-icons red-text">delete</i></a>';
                        }

                        if($e)
                        {
                            $lista .= '<a href="'.$v.'?a=select&id='.$elem->$i.'" class="secondary-content '.$c.'"><i class="material-icons green-text">edit</i></a>';
                        }

                        if($o)
                        {
                            switch ($elem->estado)
                            {
                                case "P":
                                    $shc = "deep-purple-text text-lighten-4";
                                    break;
                                case "A":
                                    $shc = "green-text text-accent-4";
                                    break;
                                case "T":
                                    $shc = "light-blue-text text-lighten-2";
                                    break;
                                case "I":
                                    $shc = "yellow-text text-darken-3";
                                    break;
                                case "D":
                                    $shc = "blue-grey-text text-lighten-3";
                                    break;
                                case "M":
                                    $shc = "deep-orange-text text-darken-3";
                                    break;
                                default:
                                    $shc = "blue-grey-text text-lighten-5";
                            }
                            $lista .= '<a href="'.$v.'?a=' . $ol . '&id='.$elem->$i.'" class="secondary-content '.$c.' mDer10"><i class="material-icons '.$shc.'">label</i></a>';
                        }
                    }
                    else
                    {
                        $lista .= '<a class="'.$c.'" href="'.$v.'?a=select&a1=' . $ol . '&id='.$elem->$i.'">'.$elem->$n . " - " . $elem->$n2 . '</a>';

                        if($d)
                        {
                            $lista .= '<a href="'.$v.'?a=select&a1=' . $od . '&id='.$elem->$i.'" class="secondary-content '.$c.' mIzq10" onclick="if(confirma(\''.$m.'\')){return true;}else{return false;}"><i class="material-icons red-text">delete</i></a>';
                        }

                        if($e)
                        {
                            $lista .= '<a href="'.$v.'?a=select&a1=' . $ol . '&id='.$elem->$i.'" class="secondary-content '.$c.'"><i class="material-icons green-text">edit</i></a>';
                        }

                        if($o)
                        {
                            switch ($elem->estado)
                            {
                                case "P":
                                    $shc = "deep-purple-text text-lighten-4";
                                    break;
                                case "A":
                                    $shc = "green-text text-accent-4";
                                    break;
                                case "T":
                                    $shc = "light-blue-text text-lighten-2";
                                    break;
                                case "I":
                                    $shc = "yellow-text text-darken-3";
                                    break;
                                case "D":
                                    $shc = "blue-grey-text text-lighten-3";
                                    break;
                                case "M":
                                    $shc = "deep-orange-text text-darken-3";
                                    break;
                                default:
                                    $shc = "blue-grey-text text-lighten-5";
                            }
                            $lista .= '<a href="'.$v.'?a='.$l.'&a1=' . $ol . '&id='.$elem->$i.'" class="secondary-content '.$c.' mDer10"><i class="material-icons '.$shc.'">label</i></a>';
                        }
                    }

                }
                else
                {
                    /**
                     * Lista sencilla
                     */
                    $lista .= $elem->$n;
                }

                $lista .= '</li>';
            }
        }
        else
        {
            $lista = '<li class="collection-item">No se encontraron elementos</li>';
        }

        return $b->blk($lista, ["class"=>"collection"], "ul");
    }

    /**
     * @param $c
     * @param string $i
     * @param string $n
     *
     *      $orden -----> ["Nombre del campo", "Dirección (ASC / DESC)"]
     *      todo $omite no está bien definino y no está claro su uso
     */
    public function mat_select_list($c, $i="id", $n="nombre", $order=[], $omite=[])
    {

        $retorno = [];

        if(!is_array($c))
        {
            if(empty($order))
            {
                $all = $c::all()->sortBy($n);
            }
            else
            {
                if(isset($order[1]) && $order[1] === "DESC")
                {
                    $all = $c::all()->sortByDesc($order[0]);
                }
                else
                {
                    $all = $c::all()->sortBy($order[0]);
                }
            }

            foreach($all as $ea)
            {
                if(!empty($omite))
                {
                    if(!in_array($ea->$i, $omite))
                    {
                        $retorno[$ea->$i] = $this->mat_selectNombreArray($n, $ea);
                    }
                }
                else
                {
                    $retorno[$ea->$i] = $this->mat_selectNombreArray($n, $ea);
                }
            }
        }
        else
        {
            foreach($c as $id=>$val)
            {
                if(is_array($val))
                {
                    $retorno[$val[$i]] = $val[$n];
                }
            }
        }
        return $retorno;
    }

    public function mat_selectNombreArray($n, $ea)
    {
        if(is_array($n))
        {
            $nTmp = "";
            foreach($n as $m)
            {
                $nTmp .= $ea->$m . " ";
            }
            $retorno = $nTmp;
        }
        else
        {
            $retorno = $ea->$n;
        }

        return $retorno;
    }

    public function mat_slist($c, $a = [])
    {
        $i = isset($a["i"]) ? $a["i"] : "id";
        $n = isset($a["n"]) ? $a["n"] : "nombre";
        $o = isset($a["o"]) ? $a["o"] : ""; // Orden
        $d = isset($a["d"]) ? $a["d"] : ""; // Dirección
        $w = isset($a["w"]) ? $a["w"] : ""; // where

        if(empty($w))
        {
            if(empty($o))
            {
                if(empty($d))
                {
                    $all = $c::all()->sortBy($n);
                }
                else
                {
                    $all = $c::all()->sortByDesc($n);
                }
            }
            else
            {
                if(empty($d))
                {
                    $all = $c::all()->sortBy($o);
                }
                else
                {
                    $all = $c::all()->sortByDesc($o);
                }
            }
        }
        else
        {
            if(empty($o))
            {
                if(empty($d))
                {
                    $all = $c::where($w)->get();
                }
                else
                {
                    $all = $c::where($w)->orderBy($n,$d)->get();
                }
            }
            else
            {
                if(empty($d))
                {
                    $all = $c::where($w)->orderBy($o)->get();
                }
                else
                {
                    $all = $c::where($w)->orderBy($o,$d)->get();
                }
            }
        }


        $retorno = [];

        foreach($all as $ea)
        {
            if(is_array($n))
            {
                $nTmp = "";
                foreach($n as $m)
                {
                    $nTmp .= $ea->$m . " ";
                }
                $retorno[$ea->$i] = $nTmp;
            }
            else
            {
                $retorno[$ea->$i] = $ea->$n;
            }
        }

        return $retorno;
    }

    public function mat_switch($label, $name, $on="On",$off="Off",$dis="")
    {
        $disabed = ($dis != "") ? "disabled" : "";
        $retorno = '<div class="row r">';
        $retorno .= '<div class="col s6 der">' . $label . '</div>';
        $retorno .= '<div class="col s6"><div class="switch"><label style="color: #424242;">' . $off . '<input name="' . $name . '" ' . $disabed . ' type="checkbox" value="1" /><span class="lever"></span>' . $on . '</label></div></div>';
        $retorno .= '</div>';

        return $retorno;
    }

    public function camposModelo($modelo)
    {
        $camp = json_decode($modelo, true);

        $retorno = [];

        foreach($camp as $cid=>$c)
        {
            $retorno[$cid] = gettype($c);
        }

        return $retorno;
    }

    public function camposClase($clase)
    {
        $campos = $clase::all();


        return camposModelo($campos[0]);
    }

    /**
     * @return string
     *
     * Devuelve los elementos de formularios según los campos de un modelo de eloquent
     */
    public function camposMaterialize()
    {

        $lista = camposModelo();

        $retorno = "";
        foreach($lista as $li)
        {
            if(substr($li,-3) == "_id")
            {
                // asumimos que es un campo FK (foreing Key)
                // lo montamos en un select, de lo contrario usaremos un input
                $retorno .= mat_select(ucfirst(substr($li,0, -3)), $li, [], "col s12 l6").PHP_EOL;

            }
            else
            {
                if($li != "id")
                {
                    $retorno .= mat_input(ucfirst($li), $li, ["envoltura" => "col s12 l6"]) . PHP_EOL;
                }
            }
        }

        return $retorno;

    }

    public function collapsible($lista, $datos = [])
    {
        /**
         * [
         *  ["titulo","contenido","icono"],
         *  ["titulo","contenido","icono"],
         *  ["titulo","contenido","icono"]
         * ]
         */
        $b = new HtmlTag();
        $retorno = "";
        foreach ($lista as $li)
        {
            $cssHeader = isset($datos["cssH"]) ? " " . $datos["cssH"] : "";
            $cssBody = isset($datos["cssB"]) ? " " . $datos["cssB"] : "";

            $retorno .= '<li>';
            $retorno .= isset($li[2]) ? '<div class="collapsible-header'.$cssHeader.'"><i class="material-icons">'.$li[2].'</i>'.$li[0].'</div>' :
                '<div class="collapsible-header'.$cssHeader.'">'.$li[0].'</div>';
            $retorno .= '<div class="collapsible-body'.$cssBody.'"><span>'.$li[1].'</span></div>';
            $retorno .= '</li>';
        }
        return '<ul class="collapsible">'.$retorno.'</ul>';
    }

    public function clasificar($c=0, $a="")
    {
        $cl = empty($c) ? 1 : $c;
        $cantidad = 5;
        $simbolo = '<i class="material-icons yellow-text text-darken-3">favorite</i>';
        $inicio = 0;
        if(is_array($a))
        {
            $cantidad = isset($a["cantidad"]) ? $a["cantidad"] : $cantidad;
            $simbolo = isset($a["simbolo"]) ? $a["simbolo"] : $simbolo;
            $inicio = isset($a["inicio"]) ? $a["inicio"] : $inicio;
        }
        $allStar = array_fill($inicio, $cantidad, $simbolo);
        $retorno = "";
        for($clasifica = 5; $clasifica >= 1; $clasifica--)
        {
            $clasi = $cl == $clasifica ? 1 : 0;
            $retorno .= mat_radio(implode("", $allStar), "empresaCalifica",  $clasifica, uniqid(), "with-gap", $clasi);
            array_shift($allStar);
        }
        return $retorno;
    }
}











