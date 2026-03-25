<?php

namespace Hisend;

/**
 * Hisend Webhook signature verification helper.
 *
 * Usage:
 *
 *   use Hisend\Webhook;
 *
 *   $payload   = file_get_contents('php://input');
 *   $signature = $_SERVER['HTTP_X_HISEND_SIGNATURE'] ?? '';
 *   $secret    = getenv('HISEND_WEBHOOK_SECRET');
 *
 *   if (!Webhook::verify($payload, $signature, $secret)) {
 *       http_response_code(401);
 *       exit(json_encode(['error' => 'Invalid webhook signature']));
 *   }
 *
 *   $event = json_decode($payload, true);
 *   // ... handle event
 */
class Webhook
{
    /**
     * Verify the HMAC-SHA256 signature of an incoming Hisend webhook.
     *
     * @param string $payload   Raw request body exactly as received.
     * @param string $signature Value of the X-Hisend-Signature header.
     * @param string $secret    Your Webhook Signing Secret (starts with whsec_).
     *
     * @return bool True if the signature is valid, false otherwise.
     */
    public static function verify(string $payload, string $signature, string $secret): bool
    {
        if ($payload === '' || $signature === '' || $secret === '') {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        // hash_equals provides constant-time comparison to prevent timing attacks.
        return hash_equals($expected, $signature);
    }
}
