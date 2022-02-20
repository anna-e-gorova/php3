<?php
interface INotifier
{
    public function send(string $text): string;
}
 
class NullNotifier implements INotifier
{
    private $text;
 
    public function __construct(string $text)
    {
        $this->text = $text;
    }
 
    public function send($text): string
    {
        return "Success send";
    }
}


abstract class NotifierDecorator implements INotifier
{
    protected $notifier = null;
 
    public function __construct(INotifier $nextNotifier = null)
    {
        $this->notifier = $nextNotifier;
    }
}

class SmsNotifier extends NotifierDecorator
{
    public function send($text): string
    {
        /*...*/
        return $this->notifier->send($text);
    }
}
 
class EmailNotifier extends NotifierDecorator
{
    public function send($text): string
    {
        /*...*/
        return $this->notifier->send($text);
    }
}
 
class ChromeNotifier extends NotifierDecorator
{
    public function send($text): string
    {
        /*...*/
        return $this->notifier->send($text);
    }
}


function testNotifier(string $text)
{
    $notifier =
        new EmailNotifier(
            new ChromeNotifier(
                new NullNotifier($text)
            )
        );
    $notifier->send("Hello World!");
}
