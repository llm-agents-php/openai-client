# LLM Agents OpenAI Client

[![PHP](https://img.shields.io/packagist/php-v/llm-agents/openai-client.svg?style=flat-square)](https://packagist.org/packages/llm-agents/openai-client)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/llm-agents/openai-client.svg?style=flat-square)](https://packagist.org/packages/llm-agents/openai-client)
[![Total Downloads](https://img.shields.io/packagist/dt/llm-agents/openai-client.svg?style=flat-square)](https://packagist.org/packages/llm-agents/openai-client)

This package is your go-to solution for integrating OpenAI's powerful API into your LLM Agents projects.

## What's in the box?

- Easy setup with Spiral framework
- Smooth integration with OpenAI's API
- Built to work hand-in-hand with LLM Agents

## Installation 🛠️

1. Run this command to add the package to your project:

```bash
composer require llm-agents/openai-client
```

2. That's it! You're ready to roll.

### Setting it up in Spiral

To get the OpenAI client up and running in your Spiral app, you need to register the bootloader.

**Here's how:**

1. Open up your `app/src/Application/Kernel.php` file.

2. In your `Kernel` class add the `LLM\Agents\OpenAI\Client\Integration\Spiral\OpenAIClientBootloader` bootloader:

```php
class Kernel extends \Spiral\Framework\Kernel
{
   public function defineBootloaders(): array
   {
       return [
           // ... other bootloaders ...
           \LLM\Agents\OpenAI\Client\Integration\Spiral\OpenAIClientBootloader::class,
       ];
   }
}
```

The package uses your OpenAI API key and organization (if you have one) to authenticate.

Set these up in your `.env` file:

```
OPENAI_KEY=your_api_key_here
```

### Setting it up in Laravel

If you're using the Laravel framework, you'll need to install the `openai-php/laravel` package register the Service
provider.

**Here's how:**

1. Install the `openai-php/laravel` package:

```bash
composer require openai-php/laravel
```

2. Next, execute the install command:

```bash
php artisan openai:install
```

3. Finally, add your OpenAI API key to your `.env` file:

```
OPENAI_API_KEY=sk-...
OPENAI_ORGANIZATION=org-...
```

4. And register the `LLM\Agents\OpenAI\Client\Integration\Laravel\OpenAIClientServiceProvider`

And that's it! The service provider will take care of registering the `LLMInterface` for you.

## Contributing

We're always happy to get help making this package even better! Here's how you can chip in:

1. Fork the repo
2. Make your changes
3. Create a new Pull Request

Please make sure your code follows PSR-12 coding standards and include tests for any new features.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

That's all, folks! If you run into any issues or have questions, feel free to open an issue on GitHub.