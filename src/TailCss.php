<?php
namespace Latinexus\Materialize;

use Latinexus\Html\HtmlTag;

class TailCss
{
    public function __construct()
    {
        // inicializaciones si fueran necesarias
    }

    // Envoltura general para formularios: aplica clases Tailwind por defecto
    public function tail_form($contenido, $envoltura = "")
    {
        $b = new HtmlTag();
        if (empty($envoltura)) {
            return $b->blk($contenido, ["class" => "mb-4"]);
        }
        if ($envoltura == -1) {
            return $contenido;
        }
        return $b->blk($contenido, ["class" => $envoltura]);
    }

    // Input text, number, email, etc.
    public function tail_input($label, $name, $datos = ["a"])
    {
        $b = new HtmlTag();
        $type = isset($datos["type"]) ? $datos["type"] : 'text';
        $value = isset($datos["value"]) ? $datos["value"] : '';
        $id = isset($datos["id"]) ? $datos["id"] : $name;
        $env = isset($datos["env"]) ? $datos["env"] : 'mb-4';
        $required = isset($datos["required"]) ? 'required' : '';
        $readonly = isset($datos["readonly"]) ? 'readonly' : '';
        $disabled = isset($datos["disabled"]) ? 'disabled' : '';
        $placeholder = isset($datos["placeholder"]) ? $datos["placeholder"] : '';

        $inputAttrs = [
            "id" => $id,
            "name" => $name,
            "type" => $type,
            "value" => $value,
            "class" => "w-full border rounded px-3 py-2 focus:outline-none focus:ring",
        ];
        if ($required !== '') $inputAttrs['required'] = $required;
        if ($readonly !== '') $inputAttrs['readonly'] = $readonly;
        if ($disabled !== '') $inputAttrs['disabled'] = $disabled;
        if (!empty($placeholder)) $inputAttrs['placeholder'] = $placeholder;

        $html = $b->noBlk($inputAttrs) . PHP_EOL;
        $html .= $b->blk($label, ["for" => $id, "class" => "block text-sm font-medium text-gray-700 mt-1"], "label") . PHP_EOL;

        return $this->tail_form($html, $env);
    }

    // Select (usa select nativo para preservar semántica)
    public function tail_select($label, $name, $contenido, $envoltura = "", $selected = "", $default = "", $id = "", $extra = "")
    {
        $b = new HtmlTag();
        $idShow = !empty($id) ? $id : $name;
        $env = empty($envoltura) ? 'mb-4' : $envoltura;

        $selAttrs = [
            "name" => $name,
            "id" => $idShow,
            "class" => "w-full border rounded px-3 py-2 bg-white",
        ];
        if (!empty($extra)) {
            // extra puede contener atributos en cadena (limitado)
            // HtmlTag no admite parseo de extra, así que lo omitimos en atributos generados
        }

        $html = '<select name="' . $name . '" id="' . $idShow . '" class="w-full border rounded px-3 py-2">'.PHP_EOL;

        if (is_array($contenido)) {
            if (empty($selected)) {
                $html .= '<option value="" disabled selected>Seleccionar</option>'.PHP_EOL;
            }
            foreach ($contenido as $val => $lab) {
                $isSel = ($selected == $val) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($val, ENT_QUOTES) . '"' . $isSel . '>' . htmlspecialchars($lab, ENT_QUOTES) . '</option>' . PHP_EOL;
            }
        } else {
            $html .= '<option value="" disabled selected>Sin elementos</option>'.PHP_EOL;
        }

        $html .= '</select>'.PHP_EOL;
        $html .= $b->blk($label, ["for" => $idShow, "class" => "block text-sm text-gray-700 mt-1"], "label");

        return $this->tail_form($html, $env);
    }

    // Textarea
    public function tail_textarea($label, $name, $envoltura = "", $value = "", $id = "", $clase = "", $largo = "")
    {
        $b = new HtmlTag();
        $idShow = !empty($id) ? $id : $name;
        $env = empty($envoltura) ? 'mb-4' : $envoltura;

        $attrs = [
            "id" => $idShow,
            "name" => $name,
            "class" => "w-full border rounded px-3 py-2 " . $clase,
        ];
        if (!empty($largo)) $attrs['data-length'] = $largo;

        $html = $b->blk($value, $attrs, "textarea");
        $html .= $b->blk($label, ["for" => $idShow, "class" => "block text-sm text-gray-700 mt-1"], "label");

        return $this->tail_form($html, $env);
    }

    // Checkbox (Tailwind styles, semántica conservada)
    public function tail_check($label, $name, $value = "1", $id = "", $clase = "", $checked = "")
    {
        $b = new HtmlTag();
        $idShow = !empty($id) ? $id : $name . uniqid();
        $isChecked = !empty($checked) ? 'checked' : '';

        $inputAttrs = [
            "id" => $idShow,
            "name" => $name,
            "type" => "checkbox",
            "value" => $value,
            "class" => "h-4 w-4 text-blue-600 border-gray-300 rounded " . $clase,
        ];
        if ($isChecked) $inputAttrs['checked'] = $isChecked;

        $inp = $b->noBlk($inputAttrs);
        $labelHtml = $b->blk($inp . '<span class="ml-2">' . $label . '</span>', ["class" => "inline-flex items-center"], "label");

        return $this->tail_form($labelHtml, -1);
    }

    // Radio
    public function tail_radio($label, $name, $value = "1", $id = "", $clase = "", $checked = "")
    {
        $b = new HtmlTag();
        $idShow = !empty($id) ? $id : $name . uniqid();
        $isChecked = !empty($checked) ? 'checked' : '';

        $inputAttrs = [
            "id" => $idShow,
            "name" => $name,
            "type" => "radio",
            "value" => $value,
            "class" => "h-4 w-4 text-blue-600 border-gray-300 " . $clase,
        ];
        if ($isChecked) $inputAttrs['checked'] = $isChecked;

        $inp = $b->noBlk($inputAttrs);
        $labelHtml = $b->blk($inp . '<span class="ml-2">' . $label . '</span>', ["class" => "inline-flex items-center"], "label");

        return $this->tail_form($labelHtml, -1);
    }

    // Colección (mat_filas equivalente): genera una lista simple con opciones de link/acciones
    public function tail_filas($obj, $datos = [])
    {
        $b = new HtmlTag();
        $lista = "";

        if (count($obj) > 0) {
            $n = array_key_exists("nombre", $datos) ? $datos["nombre"] : "nombre";
            $n2 = array_key_exists("nombre2", $datos) ? $datos["nombre2"] : "";
            $i = array_key_exists("id", $datos) ? $datos["id"] : "id";
            $c = array_key_exists("class", $datos) ? $datos["class"] : "text-gray-700";
            $l = array_key_exists("link", $datos) ? $datos["link"] : TRUE;
            $d = in_array("delete", $datos);
            $e = in_array("edit", $datos);
            $v = $this->resolveBaseUrl($datos);

            foreach ($obj as $elem) {
                // construir displayName con seguridad
                $displayName = '';
                if (is_array($n)) {
                    $parts = [];
                    foreach ($n as $prop) {
                        if (isset($elem->{$prop}) && $elem->{$prop} !== '') $parts[] = $elem->{$prop};
                    }
                    $displayName = implode(' ', $parts);
                } else {
                    $displayName = isset($elem->{$n}) ? $elem->{$n} : '';
                }
                if (!empty($n2) && isset($elem->{$n2}) && $elem->{$n2} !== '') {
                    $displayName .= ' - ' . $elem->{$n2};
                }

                $item = '';
                if ($l) {
                    $item .= '<a href="' . htmlspecialchars($v . '?id=' . ($elem->{$i} ?? ''), ENT_QUOTES) . '" class="block px-4 py-2 hover:bg-gray-100">' . htmlspecialchars($displayName, ENT_QUOTES) . '</a>';
                } else {
                    $item .= '<div class="px-4 py-2">' . htmlspecialchars($displayName, ENT_QUOTES) . '</div>';
                }
                $lista .= $b->blk($item, ["class" => "border-b last:border-b-0 bg-white"], "li");
            }
        } else {
            $lista = '<li class="px-4 py-2">No se encontraron elementos</li>';
        }

        return $b->blk($lista, ["class" => "bg-white rounded shadow divide-y divide-gray-200"], "ul");
    }

    // Acordeón sencillo: recibe array de pares [titulo, contenido]
    public function collapsible($lista, $datos = [])
    {
        $b = new HtmlTag();
        $retorno = '';
        $index = 0;
        foreach ($lista as $li) {
            $index++;
            $titulo = $li[0] ?? '';
            $contenido = $li[1] ?? '';
            $id = 'tail-collapse-' . uniqid() . '-' . $index;

            $btn = '<button type="button" data-collapse-target="' . $id . '" aria-expanded="false" class="w-full text-left px-4 py-2 bg-gray-100 hover:bg-gray-200">' . htmlspecialchars($titulo, ENT_QUOTES) . '</button>';
            $body = '<div id="' . $id . '" class="hidden px-4 py-2" aria-hidden="true">' . $contenido . '</div>';

            $retorno .= $b->blk($btn . $body, ["class" => "border-b"], "div");
        }

        return $b->blk($retorno, ["class" => "divide-y divide-gray-200 bg-white rounded shadow"], "div");
    }

    // pequeño helper para resolver base URL como resolveVistaUrl
    private function resolveBaseUrl(array $datos = [])
    {
        if (defined('E_URL') && E_URL !== '') {
            $base = rtrim((string)E_URL, '/') . '/';
        } else {
            $scheme = 'http';
            if (!empty($_SERVER['REQUEST_SCHEME'])) $scheme = $_SERVER['REQUEST_SCHEME'];
            elseif (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') $scheme = 'https';
            $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
            $base = $scheme . '://' . $host . '/';
        }

        if (array_key_exists('vista', $datos) && !empty($datos['vista'])) {
            $vista = ltrim($datos['vista'], '/');
            return $base . $vista;
        }

        return rtrim($base, '/');
    }
}
