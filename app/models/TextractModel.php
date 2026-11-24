<?php
namespace App\Models;

use Aws\Textract\TextractClient;
use Aws\Exception\AwsException;

class TextractModel
{
    private $client;

    public function __construct()
    {
        $this->client = new TextractClient([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'credentials' => [
                'key'    => getenv('AWS_ACCESS_KEY_ID'),
                'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);
    }

    public function procesarImagen($rutaImagen)
    {
        try {
            $bytes = file_get_contents($rutaImagen);

            $resultado = $this->client->analyzeDocument([
                'Document' => ['Bytes' => $bytes],
                'FeatureTypes' => ['FORMS'],
            ]);

            $textoCompleto = '';

            foreach ($resultado['Blocks'] as $block) {
                if ($block['BlockType'] === 'LINE') {
                    $textoCompleto .= $block['Text'] . "\n";
                }
            }

            return $textoCompleto;

        } catch (AwsException $e) {
            return 'Error al procesar imagen con Textract: ' . $e->getMessage();
        }
    }

    public function obtenerBloquesImagen($rutaImagen)
    {
        $bytes = file_get_contents($rutaImagen);

        $resultado = $this->client->analyzeDocument([
            'Document' => ['Bytes' => $bytes],
            'FeatureTypes' => ['FORMS'],
        ]);

        return $resultado['Blocks'];
    }
}
