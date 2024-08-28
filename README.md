# LLM Agents OpenAI Client

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

## Setting it up 🔧

To get the OpenAI client up and running in your Spiral app, you need to register the bootloader. Here's how:

1. Open up your `app/src/Application/Kernel.php` file.

2. In your `Kernel` class, find or create the `defineBootloaders()` method and add the `OpenAIClientBootloader`:

```php
class Kernel extends \Spiral\Framework\Kernel
{
   public function defineBootloaders(): array
   {
       return [
           // ... other bootloaders ...
           \LLM\Agents\OpenAI\Client\Bootloader\OpenAIClientBootloader::class,
       ];
   }
}
```

## Configuration ⚙️

The package uses your OpenAI API key and organization (if you have one) to authenticate. Set these up in your `.env`
file:

```
OPENAI_API_KEY=your_api_key_here
```

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