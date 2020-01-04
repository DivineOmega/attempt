# Attempt

This package allows you to attempt to run a function, automatically retrying if an 
exception occurs.

## Installation

To install Attempt, just run the following command.

```bash
composer require divineomega/attempt
```

## Usage

See the following usage examples.

```php
// Attempts to run the function immediately. If an exception occurs, retry forever.
attempt(function() {
    // ...
})->now();

// Attempts to run the function immediately. If an exception occurs, retry up to 5 times.
attempt(function() {
    // ...
})->maxAttempts(5)
  ->now();

// Attempts to run the function immediately. If an exception occurs, retry until the specified date time.
attempt(function() {
    // ...
})->until($datetime)
  ->now();

// Attempts to run the function immediately. If an exception occurs, retry forever, with a 20 second gap between attempts.
attempt(function() {
    // ...
})->withGap(20)
  ->now();

// Attempts to run the function at a specified date time. If an exception occurs, retry forever. The thread will block until the specified date time is reached.
attempt(function() {
    // ...
})->at($datetime);
```
