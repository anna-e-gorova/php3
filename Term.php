<?php
abstract class Term
{
    public $name;
    public $childrenLeft;
    public $childrenRight;
    public $parent;
    public $lec;
    public $const;
    public $var;

    public function __construct($name)
    {
        $this->name = $name;
    }

    abstract function calc();
}

class Plus extends Term
{
    public function calc()
    {
        return $this->childrenLeft->calc() + $this->childrenRight->calc();
    }
}

class Minus extends Term
{
    public function calc()
    {
        return $this->childrenLeft->calc() - $this->childrenRight->calc();
    }
}

class Multiply extends Term
{
    public function calc()
    {
        return $this->childrenLeft->calc() * $this->childrenRight->calc();
    }
}

class Fission extends Term
{
    public function calc()
    {
        return $this->childrenLeft->calc() / $this->childrenRight->calc();
    }
}

class Exponent extends Term
{
    public function calc()
    {
        return pow($this->childrenLeft->calc(), $this->childrenRight->calc());
    }
}

class Constant extends Term
{
    public function calc()
    {
        return $this->var;
    }
}

class Variable extends Term
{
    public function calc()
    {
        return $this->var;
    }
}
