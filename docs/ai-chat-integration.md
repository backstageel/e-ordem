# AI Chat Integration

This document provides instructions for setting up and using the AI chat feature in the Ordem dos Médicos de Moçambique application.

## Overview

The AI chat feature integrates OpenAI's GPT models to provide an intelligent assistant for medical professionals. The assistant can answer questions about medical information, procedures, guidelines, and more.

## Setup Instructions

### 1. Install Dependencies

The AI chat feature requires several PHP packages. These have been added to the `composer.json` file. To install them, run:

```bash
composer update
```

This will install the following packages:
- `openai-php/client`: The OpenAI PHP client library
- `symfony/http-client`: A HTTP client implementation required by the OpenAI PHP client
- `nyholm/psr7`: A PSR-7 implementation required by the OpenAI PHP client

### 2. Configure OpenAI API Key

To use the OpenAI API, you need an API key. Follow these steps to obtain and configure the API key:

1. Create an account at [OpenAI](https://openai.com/) if you don't already have one
2. Navigate to the [API keys page](https://platform.openai.com/api-keys) in your OpenAI account
3. Create a new API key
4. Add the API key to your `.env` file:

```
OPENAI_API_KEY=your_api_key_here
OPENAI_MODEL=gpt-3.5-turbo  # Optional, defaults to gpt-3.5-turbo
```

### 3. Clear Configuration Cache

After updating the `.env` file, clear the configuration cache:

```bash
sail artisan config:clear
```

## Using the AI Chat Feature

### Accessing the Chat Interface

1. Log in to the application with an admin account
2. In the sidebar menu, click on "Inteligência Artificial"
3. You will be taken to the AI chat interface

### Interacting with the AI Assistant

1. Type your message in the input field at the bottom of the chat interface
2. Press Enter or click the send button to send your message
3. The AI will process your message and respond
4. You can continue the conversation by sending additional messages

### Example Questions

The AI assistant is designed to help with medical-related questions. Here are some examples of questions you can ask:

- "What are the symptoms of malaria?"
- "What is the standard treatment for tuberculosis?"
- "What are the latest guidelines for COVID-19 vaccination?"
- "How do I register a new member in the Ordem dos Médicos?"
- "What documents are required for medical license renewal?"

## Customization

### Changing the AI Model

You can change the OpenAI model used by the AI chat feature by updating the `OPENAI_MODEL` value in your `.env` file. For example:

```
OPENAI_MODEL=gpt-4
```

Available models include:
- `gpt-3.5-turbo`: Good balance of capability and cost
- `gpt-4`: More capable but more expensive
- `gpt-4-turbo`: Latest version of GPT-4 with improved capabilities

### Modifying the System Prompt

The system prompt defines the AI assistant's behavior and knowledge domain. To modify it, edit the `app/Http/Controllers/Admin/AiChatController.php` file and update the `content` value in the system message:

```php
[
    'role' => 'system',
    'content' => 'You are a helpful assistant for medical professionals. Provide accurate and concise information.'
]
```

## Troubleshooting

### API Key Issues

If you see an error message about the API key not being configured, check that:
- The `OPENAI_API_KEY` is correctly set in your `.env` file
- The API key is valid and has not expired
- You have sufficient credits in your OpenAI account

### Rate Limiting

OpenAI imposes rate limits on API requests. If you encounter rate limiting issues, you may need to:
- Implement request throttling
- Upgrade your OpenAI account for higher rate limits

### Connection Issues

If the AI assistant is not responding, check that:
- Your server has internet access
- The OpenAI API is operational (check [OpenAI status page](https://status.openai.com/))
- Your firewall is not blocking outgoing connections to the OpenAI API

## Security Considerations

The AI chat feature sends data to OpenAI's servers for processing. Be aware that:
- Information sent to OpenAI may be stored and used to improve their models
- Sensitive patient information should not be shared with the AI assistant
- Users should be informed about data privacy implications

## Support

For issues with the AI chat feature, please contact the system administrator or refer to the [OpenAI documentation](https://platform.openai.com/docs/introduction).
