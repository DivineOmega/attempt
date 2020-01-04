<?php

namespace DivineOmega\Attempt;

use DateTime;
use DateTimeInterface;
use DivineOmega\Attempt\Exceptions\DateTimeExceeded;
use DivineOmega\Attempt\Exceptions\MaxAttemptsExceeded;
use Exception;

/**
 * Class AttemptHandler
 * @package DivineOmega\Attempt
 */
class AttemptHandler
{
    /**
     * Function to attempt
     *
     * @var callable
     */
    private $function;

    /**
     * Maximum number of attempts before giving up (0 = no limit)
     *
     * @var int
     */
    private $maxAttempts = 0;

    /**
     * Date time to give up at, or null to not give up based on time
     *
     * @var DateTimeInterface|null
     */
    private $dateTime = null;

    /**
     * Gap (delay) between attempts in seconds
     *
     * @var int
     */
    private $gap = 0;

    /**
     * AttemptHandler constructor.
     *
     * @param callable $function
     */
    public function __construct(callable $function)
    {
        $this->function = $function;
    }

    /**
     * Starts attempting to run the function immediately.
     *
     * @return mixed
     * @throws DateTimeExceeded
     * @throws MaxAttemptsExceeded
     */
    public function now()
    {
        return $this->callFunction();
    }

    /**
     * Waits until a specified date and time, then starts attempting to run the function.
     *
     * @param DateTimeInterface $dateTime
     * @return mixed
     * @throws DateTimeExceeded
     * @throws MaxAttemptsExceeded
     */
    public function at(DateTimeInterface $dateTime)
    {
        while (true) {
            $now = new DateTime();
            if ($now >= $dateTime) {
                break;
            }
            sleep(1);
        }

        return $this->now();
    }

    /**
     * Handles calling the specified function with the specified gap (delay)
     * until the max attempts is reached or the specified date time has passed.
     *
     * @return mixed
     * @throws DateTimeExceeded
     * @throws MaxAttemptsExceeded
     */
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

    /**
     * Specifies the maximum number of attempts
     *
     * @param int $maxAttempts
     * @return $this
     */
    public function maxAttempts(int $maxAttempts)
    {
        $this->maxAttempts = $maxAttempts;
        return $this;
    }

    /**
     * Specifies the date time to attempt until
     *
     * @param DateTimeInterface $dateTime
     * @return $this
     */
    public function until(DateTimeInterface $dateTime)
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * Specifies the gap (delay) between each attempt, in seconds
     *
     * @param int $gap
     * @return $this
     */
    public function withGap(int $gap)
    {
        $this->gap = $gap;
        return $this;
    }
}