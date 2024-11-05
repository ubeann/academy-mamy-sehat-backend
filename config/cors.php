<?php

return [
    // Define specific paths for CORS to limit it to certain API routes
    'paths' => ['*'],

    // Specify allowed HTTP methods instead of allowing all, if possible
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'],

    // Define trusted origins instead of using a wildcard
    // Replace 'http://localhost:3000' with your actual frontend URL if known
    'allowed_origins' => ['http://localhost:3000', 'http://127.0.0.1:3000'],

    // Use patterns if you have dynamic subdomains (optional)
    'allowed_origins_patterns' => ['/^https?:\/\/(.+\.)?mamysehat\.id$/'],

    // Specify which headers are allowed instead of using a wildcard
    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization'],

    // Expose specific headers to the client if necessary (optional)
    'exposed_headers' => [],

    // Cache preflight responses for better performance
    'max_age' => 86400, // 24 hours

    // Allow credentials if the application requires cookies or auth tokens
    'supports_credentials' => true, // Set to true if using credentials

];
