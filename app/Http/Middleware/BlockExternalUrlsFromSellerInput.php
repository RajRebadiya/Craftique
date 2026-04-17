<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BlockExternalUrlsFromSellerInput
{
    public function handle(Request $request, Closure $next)
    {
        // Only enforce on state-changing requests
        if (!in_array(strtoupper($request->method()), ['POST', 'PUT', 'PATCH'], true)) {
            return $next($request);
        }

        // Client requirement: allow only craftique.gr links (and its subdomains) plus relative links.
        $allowedBaseDomain = 'craftique.gr';
        $allowedHosts = [];
        if (app()->environment('local')) {
            $allowedHosts = ['127.0.0.1', 'localhost', '::1'];
        }

        $errors = [];

        $scan = function ($value, string $path) use (&$scan, &$errors, $allowedHosts, $allowedBaseDomain) {
            if (is_array($value)) {
                foreach ($value as $key => $child) {
                    $childPath = $path === '' ? (string) $key : $path . '.' . $key;
                    $scan($child, $childPath);
                }
                return;
            }

            if (!is_string($value) || $value === '') {
                return;
            }

            // Allow relative links and data URIs (posters, etc.)
            // Block only absolute http(s) and "www." URLs that point outside allowed hosts.
            $matches = [];
            preg_match_all('/\bhttps?:\/\/[^\s"\'<>]+|\bwww\.[^\s"\'<>]+/i', $value, $matches);
            $urls = $matches[0] ?? [];

            foreach ($urls as $raw) {
                $url = trim($raw);
                if ($url === '') {
                    continue;
                }

                // Normalize "www." to a URL so parse_url works
                if (stripos($url, 'www.') === 0) {
                    $url = 'http://' . $url;
                }

                $host = parse_url($url, PHP_URL_HOST);
                $host = strtolower((string) $host);

                if ($host === '') {
                    continue;
                }

                $isAllowed = in_array($host, $allowedHosts, true)
                    || $host === $allowedBaseDomain
                    || str_ends_with($host, '.' . $allowedBaseDomain);

                if (!$isAllowed) {
                    $fieldKey = $path ?: 'content';
                    $errors[$fieldKey] = translate('Only craftique.gr links are allowed. External URLs are not permitted.');
                    return;
                }
            }
        };

        $scan($request->all(), '');

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        return $next($request);
    }
}
