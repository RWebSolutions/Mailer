<?php
declare(strict_types=1);

namespace Rws\Mailer\Message;

/**
 * @package RWS\Mailer\Message
 */
class Message
{
    /**
     * @var Subject
     */
    private $subject;
    /**
     * @var Content
     */
    private $content;
    /**
     * @var Recipients
     */
    private $recipients;
    /**
     * @var Sender
     */
    private $sender;

    public function __construct(Subject $subject, Content $content, Recipients $recipients, Sender $sender)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->recipients = $recipients;
        $this->sender = $sender;
    }

    /**
     * @return EmailAddress[]
     */
    public function getCc(): array
    {
        return $this->recipients->getCc();
    }

    /**
     * @return EmailAddress[]
     */
    public function getBcc(): array
    {
        return $this->recipients->getBcc();
    }

    /**
     * @return Subject
     */
    public function getSubject(): Subject
    {
        return $this->subject;
    }

    public function getRecipient(): EmailAddress
    {
        return $this->recipients->getTo();
    }

    public function getPlainTextContent(): ?string
    {
        return $this->content->getPlain();
    }

    public function getHtmlContent(): ?string
    {
        return $this->content->getHtml();
    }

    public function getSender(): EmailAddress
    {
        return $this->sender->getFrom();
    }

    public function getReplyTo(): ?EmailAddress
    {
        return $this->sender->getReplyTo();
    }
}