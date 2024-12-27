<?php

/**
 * Google API configuration file.
 *
 * This file returns an array containing the configuration settings for
 * Google's API client. The settings include the client ID, client secret,
 * and the callback URL, all of which are retrieved from environment variables.
 *
 * Configuration settings:
 * - 'client': An array containing the 'id' and 'secret' for the Google API client.
 *   - 'id': The Google API client ID, retrieved from the environment variable 'GOOGLE_CLIENT_ID'.
 *   - 'secret': The Google API client secret, retrieved from the environment variable 'GOOGLE_CLIENT_SECRET'.
 * - 'callback_url': The URL to which Google will redirect after authentication,
 *   retrieved from the environment variable 'GOOGLE_REDIRECT_URI'.
 *
 * @return array The configuration settings for the Google API client.
 */

return [
    'client' => [
        'id' => env('GOOGLE_CLIENT_ID'),
        'secret' => env('GOOGLE_CLIENT_SECRET')
    ],
    'callback_url' => env('GOOGLE_REDIRECT_URI')
];
