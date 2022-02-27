<?php
class HandHunter implements \SplSubject
{
    private $observers;
    private $vacances;

    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }

    public function attach(\SplObserver $observer) : void
    {
        echo "Subject: Attached an observer.<br>";
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer) : void
    {
        $this->observers->detach($observer);
        echo "Subject: Detached an observer.<br>";
    }

    public function notify() : void
    {
        echo "Subject: Notifying observers...<br>";
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function addVacancy($vacancy) : void
    {
        array_push($this->vacances, $vacancy);
    }
}



class Employee implements \SplObserver
{
    private $name;
    private $email;
    private $experience;
    private $vacances;

    public function __construct($name, $email, $experience) 
    {
        $this->name = $name;
        $this->email = $email;
        $this->experience = $experience;
    }
    public function update(\SplSubject $jobSite) : void
    {
        $this->vacances = $jobSite->vacances;
    }
}



$employee = new Employee('Ivan Ivanov', 'iivanov@yandex.ru', 10);

$handHunter = new HandHunter();

$handHunter->attach($employee);
$handHunter->addVacancy("senior php-developer");
$handHunter->notify();