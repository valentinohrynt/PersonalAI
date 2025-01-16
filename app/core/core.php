<?php
require_once './app/config/env.php';
require_once './vendor/autoload.php';

use LucianoTonet\GroqPHP\Groq;

class Core {
    private $api_key;

    public function __construct() {
        $this->api_key = $_ENV['GROQ_API_KEY'];
        $client = new Groq($this->api_key);
        $this->client = $client;
    }

    function getAnswer($data) {
        $lang = $data['lang'];
        $prompt = $data['prompt'];
        $conversationHistory = isset($_SESSION['conversation_history']) ? $_SESSION['conversation_history'] : [];
        $conversationHistory[] = ['role' => 'user', 'content' => $prompt];

        try {
            if ($lang == 'en') {
                $chatCompletion = $this->client->chat()->completions()->create([
                    'messages' => $conversationHistory,
                    'model' => 'llama3-8b-8192',
                ]);
            } elseif ($lang == 'id') {
                $fixedPrompt = 'User choose Indonesian Language, so please answer using Indonesian language response. \n\n' . $prompt;
                $conversationHistory[] = ['role' => 'user', 'content' => $fixedPrompt];
                $chatCompletion = $this->client->chat()->completions()->create([
                    'messages' => $conversationHistory,
                    'model' => 'llama3-8b-8192',
                ]);
            } else {
                $chatCompletion = $this->client->chat()->completions()->create([
                    'messages' => $conversationHistory,
                    'model' => 'llama3-8b-8192',
                ]);
            }
    
            if (isset($chatCompletion['choices'][0]['message']['content'])) {
                $assistantResponse = $chatCompletion['choices'][0]['message']['content'];
                $conversationHistory[] = ['role' => 'assistant', 'content' => $assistantResponse];
            } else {
                $assistantResponse = null;
            }
    
            $_SESSION['conversation_history'] = $conversationHistory;
        } catch (Exception $e) {
            $chatCompletion = null;
        }
        return $assistantResponse;
    }
}