<?php
namespace App\Controllers;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Models\TextractModel;
use App\Models\EmpaModel;
use PDO;



class EmpaController
{
    private $db;

    public function __construct(PDO $conexion)
    {
        $this->db = $conexion;
    }

    public function procesarFormulario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $modelo = new EmpaModel($this->db);
            $rutUsuario = $_SESSION['rut'] ?? null;

            if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] === UPLOAD_ERR_OK) {
                $rutaPDF = __DIR__ . '/../../uploads/' . basename($_FILES['archivo_pdf']['name']);
                move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], $rutaPDF);
                $this->procesarPDFconBloques($rutaPDF, $modelo, $rutUsuario);

            } else {
                if (!isset($_FILES['imagen1']) || $_FILES['imagen1']['error'] !== UPLOAD_ERR_OK) {
                    die('Error al subir imagen 1.');
                }
                if (!isset($_FILES['imagen2']) || $_FILES['imagen2']['error'] !== UPLOAD_ERR_OK) {
                    die('Error al subir imagen 2.');
                }
                $rutaImagen1 = __DIR__ . '/../../uploads/' . basename($_FILES['imagen1']['name']);
                $rutaImagen2 = __DIR__ . '/../../uploads/' . basename($_FILES['imagen2']['name']);
                move_uploaded_file($_FILES['imagen1']['tmp_name'], $rutaImagen1);
                move_uploaded_file($_FILES['imagen2']['tmp_name'], $rutaImagen2);

                $textract = new TextractModel();
                $bloques1 = $textract->obtenerBloquesImagen($rutaImagen1);
                $bloques2 = $textract->obtenerBloquesImagen($rutaImagen2);
                $this->procesarBloques($bloques1, $bloques2, $modelo, $rutUsuario);
                
            }
        }
    }

   private function procesarPDFconBloques($rutaPDF, $modelo, $rutUsuario)
    {
        $imagenes = $this->convertirPDFaImagenes($rutaPDF);
        if (count($imagenes) < 2) die('PDF inválido.');

        $datosProcesados = [];

        for ($i = 0; $i < count($imagenes); $i += 2) {
            $bloques1 = (new TextractModel())->obtenerBloquesImagen($imagenes[$i]);
            $bloques2 = isset($imagenes[$i + 1]) ? (new TextractModel())->obtenerBloquesImagen($imagenes[$i + 1]) : [];
            $datos = $this->procesarBloquesPDF($bloques1, $bloques2, $modelo, $rutUsuario);
            $datosProcesados[] = $datos;
            $listaEmpas[] = $datos;
        }

        $datosProcesados = $listaEmpas; // acumulaste los EMPA en $listaEmpas[]
        $totalProcesados = count($listaEmpas);
        include __DIR__ . '/../views/resumen_pdf.php';
        exit;
    }


    private function procesarBloques($bloques1, $bloques2, $modelo, $rutUsuario)
    {
        $rut = $this->formatearRut($this->extraerTextoCercaDe($bloques1, 'R.U.T'));
        $edad = $this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'Edad'));
        $sexo = $this->detectarSexoAvanzado($bloques1);

        $peso = $this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'Peso'));
        $talla = $this->normalizarTalla($this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'Talla')));
        $cintura = $this->normalizarCircunferencia($this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'Cintura')));
        $imc = $this->corregirIMC($this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'IMC')));
        $estado = $this->estadoNutricional($imc);

        $pas = $this->corregirPAS($this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'PAS')));
        $pad = $this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'PAD'));
        $glicemia = $this->extraerTextoCercaDe($bloques1, 'Glicemia ayunas');

        $evaluacionPap = $this->evaluarPapanicolau($bloques2);
        $ultimoPap = $evaluacionPap['año'];
        $estadoPap = $evaluacionPap['estado'];
        
        $colesterol = $this->corregirNumeroLeido($this->extraerNumeroDesdeTexto($this->extraerTextoCercaDe($bloques2, 'Colesterol')));
        $mamografia = $this->detectarCasillasPorTexto($bloques2, 'Mamografía') === 'SI' ? 'SI' : 'NO';

        $bebe = $this->detectarCasillasPorTexto($bloques1, '¿Consume bebidas alcohólicas?');
        $fuma = $this->detectarCasillasPorTexto($bloques1, '¿Usted fuma?');

        $modelo->verificarOCrearPaciente($rut, 'Paciente', 'Detectado', $sexo);

        $modelo->insertarEmpa([
            'edad' => $edad,
            'sexo' => $sexo,
            'bebe' => $bebe,
            'fuma' => $fuma,
            'peso' => $peso,
            'talla' => $talla,
            'circunferencia' => $cintura,
            'IMC' => $imc,
            'sobrepeso' => $estado,
            'PAS' => $pas,
            'PAD' => $pad,
            'glicemia' => $glicemia,
            'papanicolau' => $estadoPap,
            'ult_pap' => $ultimoPap,
            'colesterol' => is_numeric($colesterol) ? $colesterol : '0',
            'mamografia' => $mamografia,
        ]);

        $modelo->insertarAtencion($rutUsuario, $rut, $this->db->lastInsertId());
        include __DIR__ . '/../views/resultado.php';
    }

    private function procesarBloquesPDF($bloques1, $bloques2, $modelo, $rutUsuario)
    {
        $rut = $this->formatearRut($this->extraerTextoCercaDe($bloques1, 'R.U.T'));
        $edad = $this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'Edad'));
        $sexo = $this->detectarSexoAvanzado($bloques1);

        $peso = $this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'Peso'));
        $talla = $this->normalizarTalla($this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'Talla')));
        $cintura = $this->normalizarCircunferencia($this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'Cintura')));
        $imc = $this->corregirIMC($this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'IMC')));
        $estado = $this->estadoNutricional($imc);

        $pas = $this->corregirPAS($this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'PAS')));
        $pad = $this->corregirNumeroLeido($this->extraerTextoCercaDe($bloques1, 'PAD'));
        $glicemia = $this->extraerTextoCercaDe($bloques1, 'Glicemia ayunas');

        $evaluacionPap = $this->evaluarPapanicolau($bloques2);
        $ultimoPap = $evaluacionPap['año'];
        $estadoPap = $evaluacionPap['estado'];
        
        $colesterol = $this->corregirNumeroLeido($this->extraerNumeroDesdeTexto($this->extraerTextoCercaDe($bloques2, 'Colesterol')));
        $mamografia = $this->detectarCasillasPorTexto($bloques2, 'Mamografía') === 'SI' ? 'SI' : 'NO';

        $bebe = $this->detectarCasillasPorTexto($bloques1, '¿Consume bebidas alcohólicas?');
        $fuma = $this->detectarCasillasPorTexto($bloques1, '¿Usted fuma?');

        $modelo->verificarOCrearPaciente($rut, 'Paciente', 'Detectado', $sexo);

        $modelo->insertarEmpa([
            'edad' => $edad,
            'sexo' => $sexo,
            'bebe' => $bebe,
            'fuma' => $fuma,
            'peso' => $peso,
            'talla' => $talla,
            'circunferencia' => $cintura,
            'IMC' => $imc,
            'sobrepeso' => $estado,
            'PAS' => $pas,
            'PAD' => $pad,
            'glicemia' => $glicemia,
            'papanicolau' => $estadoPap,
            'ult_pap' => $ultimoPap,
            'colesterol' => is_numeric($colesterol) ? $colesterol : '0',
            'mamografia' => $mamografia,
        ]);
        $modelo->insertarAtencion($rutUsuario, $rut, $this->db->lastInsertId());
        return [
            'rut' => $rut,
            'edad' => $edad,
            'sexo' => $sexo,
            'peso' => $peso,
            'talla' => $talla,
            'circunferencia' => $cintura,
            'imc' => $imc,
            'estado' => $estado,
            'pas' => $pas,
            'pad' => $pad,
            'glicemia' => $glicemia,
            'colesterol' => is_numeric($colesterol) ? $colesterol : '0',
            'papanicolau' => $estadoPap,
            'ult_pap' => $ultimoPap,
            'mamografia' => $mamografia,
        ];
        
    }

    private function convertirPDFaImagenes($rutaPDF)
    {
        $outputDir = __DIR__ . '/../../uploads/temp_pdf/';
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }
        $base = $outputDir . 'pagina';
        $cmd = '"C:\\poppler\\Library\\bin\\pdftoppm.exe" -jpeg -r 300 ' . escapeshellarg($rutaPDF) . ' ' . escapeshellarg($base);
        shell_exec($cmd);
        $imagenes = glob($outputDir . 'pagina*.jpg');
        sort($imagenes, SORT_NATURAL);
        return $imagenes;
    }


    // === DETECTAR CASILLAS MARCADAS CERCA DE UN TEXTO ===
    private function detectarCasillasPorTexto(array $bloques, string $clave)
    {
        $textoTop = null;

        foreach ($bloques as $block) {
            if ($block['BlockType'] === 'LINE' && stripos($block['Text'], $clave) !== false) {
                $textoTop = $block['Geometry']['BoundingBox']['Top'];
                break;
            }
        }

        if ($textoTop === null) return 'NO';

        $casillas = [];

        foreach ($bloques as $block) {
            if ($block['BlockType'] === 'SELECTION_ELEMENT' && $block['SelectionStatus'] === 'SELECTED') {
                $top = $block['Geometry']['BoundingBox']['Top'];
                $left = $block['Geometry']['BoundingBox']['Left'];

                if (abs($top - $textoTop) < 0.03) {
                    $casillas[] = ['top' => $top, 'left' => $left];
                }
            }
        }

        if (empty($casillas)) return 'NO';

        usort($casillas, fn($a, $b) => $a['left'] <=> $b['left']);
        $casillaSI = end($casillas);

        return ($casillaSI['left'] > 0.52) ? 'SI' : 'NO';
    }

    // === DETECTAR SEXO POR CASILLA MARCADA ===
    private function detectarSexoAvanzado(array $bloques): string
    {
        $casillaM = null;
        $casillaF = null;
        $sexoLineTop = null;
        foreach ($bloques as $block) {
            if ($block['BlockType'] === 'LINE' && stripos($block['Text'], 'SEXO') !== false) {
                $sexoLineTop = $block['Geometry']['BoundingBox']['Top'];
                break;
            }
        }

        if ($sexoLineTop === null) return 'N/A';
        $candidatas = [];
        foreach ($bloques as $block) {
            if (
                $block['BlockType'] === 'SELECTION_ELEMENT' &&
                $block['SelectionStatus'] === 'SELECTED'
            ) {
                $top = $block['Geometry']['BoundingBox']['Top'];
                $left = $block['Geometry']['BoundingBox']['Left'];

                if (abs($top - $sexoLineTop) < 0.04) {
                    $candidatas[] = ['top' => $top, 'left' => $left];
                }
            }
        }

        if (empty($candidatas)) return 'N/A';
        foreach ($bloques as $block) {
            if ($block['BlockType'] === 'WORD') {
                $text = strtoupper(trim($block['Text']));
                if ($text === 'M' || $text === 'F') {
                    $top = $block['Geometry']['BoundingBox']['Top'];
                    $left = $block['Geometry']['BoundingBox']['Left'];

                    foreach ($candidatas as $casilla) {
                        if (abs($casilla['top'] - $top) < 0.02 && abs($casilla['left'] - $left) < 0.06) {
                            if ($text === 'M') $casillaM = $casilla;
                            if ($text === 'F') $casillaF = $casilla;
                        }
                    }
                }
            }
        }
        if ($casillaM !== null) return 'M';
        if ($casillaF !== null) return 'F';

        return 'N/A';
    }

    // === EXTRAER TEXTO CERCANO A UNA CLAVE (MEJORADO SOLO HORIZONTAL) ===
    private function extraerTextoCercaDe(array $bloques, string $clave): string
    {
        $referencia = null;
        foreach ($bloques as $block) {
            if ($block['BlockType'] === 'LINE' && stripos($block['Text'], $clave) !== false) {
                $referencia = $block;
                break;
            }
        }

        if (!$referencia) return 'N/A';

        $refTop = $referencia['Geometry']['BoundingBox']['Top'];
        $refLeft = $referencia['Geometry']['BoundingBox']['Left'];

        $candidatos = [];

        foreach ($bloques as $block) {
            if (
                $block['BlockType'] === 'LINE' &&
                $block !== $referencia
            ) {
                $top = $block['Geometry']['BoundingBox']['Top'];
                $left = $block['Geometry']['BoundingBox']['Left'];
                if ($left > $refLeft && abs($top - $refTop) < 0.015) {
                    $candidatos[] = ['left' => $left, 'text' => $block['Text']];
                }
            }
        }

        if (empty($candidatos)) return 'N/A';

        usort($candidatos, fn($a, $b) => $a['left'] <=> $b['left']);
        return $candidatos[0]['text'];
    }

    //Corregir digitos producot de una lectura mala
    private function corregirNumeroLeido($texto)
    {
        $texto = strtolower($texto); 

        $correcciones = [
            'b' => '6',
            'l' => '1',
            'o' => '0',
            's' => '5',
            'z' => '2',
            'g' => '9',
            'q' => '0',
            'i' => '1',
            't' => '7',
        ];

        $textoCorregido = '';
        for ($i = 0; $i < strlen($texto); $i++) {
            $caracter = $texto[$i];
            $textoCorregido .= $correcciones[$caracter] ?? $caracter;
        }
        return preg_replace('/\D/', '', $textoCorregido);
    }


    // ===Si no encuentra nada N/A =====
    private function extraerNumeroDesdeTexto(string $texto): string {
        preg_match('/\d+/', $texto, $match);
        return $match[0] ?? 'N/A';
    }

    private function formatearRut($rutRaw)
    {
        $rutLimpio = strtoupper(preg_replace('/[^0-9kK]/', '', $rutRaw));
        $numero = substr($rutLimpio, 0, -1);
        $dv = substr($rutLimpio, -1);
        if (strlen($numero) < 7 || !ctype_digit($numero)) {
            return 'RUT_INVALIDO';
        }
        $rutFormateado = number_format($numero, 0, '', '.') . '-' . $dv;

        return $rutFormateado;
    }

    //Normalizamos talla
    private function normalizarTalla($valor)
    {
        $valor = trim(str_replace(',', '.', $valor));
        if (is_numeric($valor)) {
            $valorNum = floatval($valor);
            if ($valorNum > 1000) {
                $valorNum = $valorNum / 10;
            }
            return intval(round($valorNum));
        }

        return 'N/A';
    }

    //Normalizar cintura
    private function normalizarCircunferencia($valor)
    {
        $valor = trim($valor);

        if ($valor === '00' || $valor === '0') {
            return 100;
        }
        $valor = str_replace([',', '.'], '', $valor);

        return is_numeric($valor) ? intval($valor) : 'N/A';
    }

    //corregimos IMC
    private function corregirIMC($valor)
    {
        $valor = str_replace(',', '.', $valor); 
        $num = floatval($valor);
        if ($num > 50) {
            $num = $num / 10;
        }
        return round($num, 1);
    }

    //Ver el estado Nutricional
    private function estadoNutricional($imc)
    {
        if (!is_numeric($imc)) {
            return 'N/A';
        }

        $imc = floatval($imc);

        if ($imc < 18.5) {
            return 'Bajo peso';
        } elseif ($imc < 25.0) {
            return 'Normal';
        } elseif ($imc < 30.0) {
            return 'Sobrepeso';
        } else {
            return 'Obesidad';
        }
    }

    //Corregimos la presion arterial sistolica si da un numero muy alto
    private function corregirPAS($valor)
    {
        $valorOriginal = $valor;
        $valor = strtoupper(trim($valor));
        $correcciones = [
            'O' => '0', 'Q' => '0', 'D' => '0',
            'I' => '1', 'L' => '1',
            'Z' => '2',
            'S' => '5',
            'B' => '8',
            'G' => '6'
        ];
        $valor = strtr($valor, $correcciones);
        preg_match('/\d+/', $valor, $matches);
        if (!isset($matches[0])) return 'N/A';

        $num = intval($matches[0]);
        if ($num >= 70 && $num <= 300) {
            return $num;
        }
        if ($num > 300 && strlen($matches[0]) === 3) {
            $normalizado = $this->normalizarLectura($num);
            return $normalizado;
        }
        return 'N/A';
    }

    private function normalizarLectura($valor_crudo, $min_crudo = 290, $max_crudo = 850, $min_deseado = 70, $max_deseado = 300) {
        if ($max_crudo == $min_crudo) return $min_deseado;

        $normalizado = ($valor_crudo - $min_crudo) / ($max_crudo - $min_crudo);
        $valor = $min_deseado + $normalizado * ($max_deseado - $min_deseado);

        return round(max($min_deseado, min($max_deseado, $valor)));
    }

    private function evaluarPapanicolau(array $bloques): array
    {
        $resultado = [
            'año' => 'N/A',
            'estado' => 'N/A'  
        ];
        $añoDetectado = null;
        $referenciaTop = null;

        foreach ($bloques as $block) {
            if ($block['BlockType'] === 'LINE' && stripos($block['Text'], 'último Papanicolau') !== false) {
                $referenciaTop = $block['Geometry']['BoundingBox']['Top'];
            }

            if ($block['BlockType'] === 'WORD' && preg_match('/\b(19|20)\d{2}\b/', $block['Text'], $match)) {
                $añoDetectado = $match[0];
            }
        }

        if (!$añoDetectado || !$referenciaTop) {
            return $resultado;
        }

        $resultado['año'] = $añoDetectado;
        foreach ($bloques as $block) {
            if (
                $block['BlockType'] === 'SELECTION_ELEMENT' &&
                $block['SelectionStatus'] === 'SELECTED'
            ) {
                $top = $block['Geometry']['BoundingBox']['Top'];
                $left = $block['Geometry']['BoundingBox']['Left'];
                if (abs($top - $referenciaTop) < 0.06) {
                    if ($left < 0.8) {
                        $resultado['estado'] = 'NORMAL';
                    } else {
                        $resultado['estado'] = 'ALTERADO';
                    }
                }
            }
        }

        return $resultado;
    }

}  