# PHP SDK guidance

This independent Composer repository publishes `hisend/hisend-php` and requires
PHP >=8.1. Use existing Guzzle and PSR-4 conventions (`Hisend\\` maps to `src/`).
`src/Hisend.php` centralizes HTTP configuration, `src/Resources/` implements
resources, and `src/Webhook.php` handles callback verification.

From this repository, use `composer install` when dependencies are needed;
preserve `composer.lock` and do not run `composer update` as routine setup.
Run `vendor/bin/phpunit --bootstrap vendor/autoload.php tests`.
`tests/ClientTest.php` uses Guzzle MockHandler/history; extend that pattern,
checking method/path/headers/body/errors rather than calling the live service.

Preserve public API compatibility, JSON shape, PHP minimum version, and webhook
verification. Check affected backend contracts and SDK docs when available.
Never log real API keys, bypass signature checks, modify `vendor/`, or publish
without request.
