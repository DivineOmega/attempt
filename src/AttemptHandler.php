<?php

namespace DivineOmega\Attempt;

use DateTime;
use DateTimeInterface;
use DivineOmega\Attempt\Exceptions\DateTimeExceeded;
use DivineOmega\Attempt\Exceptions\MaxAttemptsExceeded;
use Exception;

class AttemptHandler
{
    private $function;
    private $maxAttempts = 0;
    private $dateTime = null;
    private $gap = 0;

    public function __construct(callable $function)
    {
        $this->function = $function;
    }

    public function now()
    {
        return $this->callFunction();
    }

    private function callFunction()
    {
        $attempts = 0;

        while (true) {
            try {

                $function = $this->function;
                return $function();

            } catch (Exception $e) {

                if ($this->maxAttempts > 0) {
                    $attempts++;
                    if ($attempts >= $this->maxAttempts) {
                        throw new MaxAttemptsExceeded('Exceeded max of '.$this->maxAttempts.' attempt(s)', 0, $e);
                    }
                }

                if ($this->dateTime instanceof DateTimeInterface) {
                    $now = new DateTime();
                    if ($now >= $this->dateTime) {
                        throw new DateTimeExceeded('Exceeded date time of '.$this->dateTime->format('Y-m-d H:i:s'), 0, $e);
                    }
                }

                sleep($this->gap);

            }


        }
    }

    public function maxAttempts(int $maxAttempts)
    {
        $this->maxAttempts = $maxAttempts;
        return $this;
    }

    public function until(DateTimeInterface $dateTime)
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    public function withGap(int $gap)
    {
        $this->gap = $gap;
        return $this;
    }
}