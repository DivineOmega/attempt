# attempt
Attempt to run a function, retrying if needed.

## Usage

```php
// Attempts to run the function immediately. If an exception occurs, retry forever.
attempt(function($query) {
    // ...
})->now();

// Attempts to run the function immediately. If an exception occurs, retry up to 5 times.
attempt(function($query) {
    // ...
})->maxAttempts(5)
  ->now();

// Attempts to run the function immediately. If an exception occurs, retry until the specified date time.
attempt(function($query) {
    // ...
})->until($datetime)
  ->now();

// Attempts to run the function immediately. If an exception occurs, retry forever, with a 20 second gap between attempts.
attempt(function($query) {
    // ...
})->withGap(20)
  ->now();

// Attempts to run the function at a specified date time. If an exception occurs, retry forever. The thread will block until the specified date time is reached.
attempt(function($query) {
    // ...
})->at($datetime);

// Attempts to run the function at a specified date time. If an exception occurs, retry forever. This attempt(s) will be made in a new process, not blocking the current thread.
attempt(function($query) {
    // ...
})->inBackground()
  ->at($datetime);
```
