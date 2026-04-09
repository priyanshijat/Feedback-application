<?php
/**
 * Shared DB credentials (used by config.php, init-db.php, test-db.php).
 * Set environment variables in Windows (or Apache) for production, or edit defaults below.
 */
return [
    'host' => getenv('DB_HOST') ?: 'feedback-db.c3y0s2oko8eh.eu-north-1.rds.amazonaws.com',
    'user' => getenv('DB_USER') ?: 'admin',
    // Replace with your RDS master password (or set DB_PASSWORD in the environment).
    'password' => getenv('DB_PASSWORD') ?: 'feedback-db',
    // IMPORTANT: This must match the DB you actually created on RDS.
    // Your DB is named `feedback-db`, so we use that directly.
    'name' => getenv('DB_NAME') ?: 'feedback-db',
    'port' => (int) (getenv('DB_PORT') ?: '3306'),
];
