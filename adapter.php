<?php
class SquareAreaLib
{
   public function getSquareArea(int $diagonal)
   {
       $area = ($diagonal**2)/2;

       return $area;
   }
}

class CircleAreaLib
{
   public function getCircleArea(int $diagonal)
   {
       $area = (M_PI * $diagonal**2)/4;

       return $area;
   }
}


interface ISquare
{
function squareArea(int $sideSquare);
}

class SquareAdapter implements ISquare
{
    private $square;
 
    public function __construct(SquareAreaLib $square)
    {
        $this->square = $square;
    }
 
    public function squareArea(int $sideSquare)
    {
        $diagonal = $sideSquare*sqrt(2);
        return $this->square>getSquareArea($diagonal);
    }
}

interface ICircle
{
function circleArea(int $circumference);
}


class CircleAdapter implements ICircle
{
    private $circle;
 
    public function __construct(CircleAreaLib $circle)
    {
        $this->circle = $circle;
    }
 
    public function circleArea(int $circumference)
    {
        $diagonal = $circumference/M_PI;
        return $this->circle>getCircleArea($diagonal);
    }
}



function testCircleAdapter(int $circumference)
{
    $cirlceAdapter = new CircleAdapter(new CircleAreaLib());
    $cirlceAdapter->circleArea($circumference);
}

function testSquareAdapter(int $sideSquare)
{
    $squareAdapter = new SquareAdapter(new SquareAreaLib());
    $squareAdapter->getSquareArea($sideSquare);
}