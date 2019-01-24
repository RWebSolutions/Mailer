<?php
declare(strict_types=1);

namespace Rws\Mailer\Message;

/**
 * @package Rws\Mailer\Provider
 */
class Subject
{
    /**
     * @var string
     */
    private $subject;

    private function __construct(string $subject)
    {
        $this->subject = $subject;
    }

    public static function fromStrong(string $subject): self
    {
        /**
         * @todo add validation rules for subject
         */
        return new static($subject);
    }

    public function __toString(): string
    {
        return $this->subject;
    }
}