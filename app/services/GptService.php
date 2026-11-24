<?php
namespace App\Services;

class GptService
{
    private $apiKey;

    public function __construct()
    {
        // La API key se lee desde la variable de entorno OPENAI_API_KEY
        $this->apiKey = getenv('OPENAI_API_KEY');
    }

    public function analizarTexto($prompt)
    {
        $url = 'https://api.openai.com/v1/chat/completions';

        $data = [
            'model' => 'gpt-4', // Puedes cambiar a 'gpt-3.5-turbo' si quieres ahorrar
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.2, // Temperatura baja para respuestas más serias y constantes
            'max_tokens' => 1500, // Limitar la cantidad de tokens de respuesta
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . 'Bearer ' . $this->apiKey,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('Error en la llamada a OpenAI: ' . curl_error($ch));
        }

        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        // Devolver el contenido del mensaje si existe
        return $decodedResponse['choices'][0]['message']['content'] ?? 'Sin respuesta de OpenAI.';
    }
}
