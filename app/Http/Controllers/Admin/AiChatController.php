<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI;
use OpenAI\Client;

class AiChatController extends Controller
{
    /**
     * The OpenAI client instance.
     */
    protected $client;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $apiKey = config('services.openai.api_key');
        if ($apiKey) {
            $this->client = OpenAI::client($apiKey);
        }
    }

    /**
     * Display the AI chat interface.
     */
    public function index()
    {
        return view('admin.ai.index');
    }

    /**
     * Process a chat request and return the AI response.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            if (! $this->client) {
                return response()->json([
                    'success' => false,
                    'message' => 'API key not configured. Please set OPENAI_API_KEY in your .env file.',
                ], 500);
            }

            // Get chat history from session or initialize empty array
            $chatHistory = session('chat_history', []);

            // Add user message to history
            $chatHistory[] = [
                'role' => 'user',
                'content' => $request->message,
            ];

            // Prepare messages for API request
            $messages = array_merge([
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant for medical professionals. Provide accurate and concise information.',
                ],
            ], $chatHistory);

            // Make request to OpenAI API using the client
            $response = $this->client->chat()->create([
                'model' => config('services.openai.model', 'gpt-3.5-turbo'),
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            $aiMessage = $response->choices[0]->message->content;

            // Add AI response to history
            $chatHistory[] = [
                'role' => 'assistant',
                'content' => $aiMessage,
            ];

            // Store updated chat history in session
            session(['chat_history' => $chatHistory]);

            return response()->json([
                'success' => true,
                'message' => $aiMessage,
            ]);
        } catch (\Exception $e) {
            Log::error('AI Chat Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: '.$e->getMessage(),
            ], 500);
        }
    }
}
