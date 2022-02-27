<?php
interface Command
{
    public function execute() : void;
}


class Copy implements Command
{
    public function execute(Editor $editor) : void
    {
        $editor->buffer = $editor->selectArea;
    }
}

class Cut implements Command
{
    public function execute(Editor $editor) : void
    {
        $editor->buffer = $editor->selectArea;
        $editor->selectArea = '';
    }
}

class Paste implements Command
{
    public function execute(Editor $editor) : void
    {
        $editor->selectArea = $editor->buffer;
    }
}

class RemoteControl
{
    private $state = [];

    public function __construct(Editor $editor)
    {
        $this->state['old'] = $editor;
        $this->state['new'] = $editor;
    }

    public function executeCommand(ICommand $command)
    {
        $this->state['old'] = $this->state['new'];
        $command->execute($this->state['new']);
        $state['new']->mergeText();
        $this->toLog();
    }

    public function revert()
    {
        $this->state['new'] = $this->state['old'];
    }

    public function toLog() : void
    {
        echo "hello command";
    }
}

class Editor
 {
    private $filePath;
    private $fileContent;
    private $selectArea;
    private $beforeStart;
    private $afterEnd;
    private $buffer;

    public function __construct(string $filePath, int $start, int $end) 
    {
        $this->fileContent = file_get_contents($filePath);
        $this->spliceText($start, $end);
    }

    public function spliceText($start, $end){
        $this->selectArea = substr($this->fileContent, $start, $end);
        $this->beforeStart = substr($this->fileContent, $start);
        $this->afterEnd = substr($this->fileContent, -$end);
    }

    public function mergeText(){
        $this->fileContent = $this->beforeStart . $this->selectArea . $this->afterEnd;
    }

    public function save($filePath = false) : void
    {
        $filePath ?? $this->filePath;
        file_put_contents($filePath, $this->fileContent);
    }

    public function openNewFile($newfilePath) : void
    {
        $this->fileContent = file_get_contents($newfilePath) && $this->filePath = $newfilePath;
    }

}


$editor = new Editor('text.txt', 3, 33);
$control = new RemoteControl($editor);
$control->executeCommand(new Cut);



