<?php

require_once 'Distance.php';

class DistanceTest extends PHPUnit_Framework_TestCase
{

    public function testTrueIsTrue()
    {
        $foo = true;
        $this->assertTrue($foo);
    }

    public function testReturn()
    {

        $foo = new Distance("53.333", "-6.267");
        $data = $foo->proceedData('https://gist.githubusercontent.com/mwtorkowski/16ca26a0c072ef743734/raw/2aa20e8de9f2292d58a4856602c1f0634d8611a7/cities.json',true);
        foreach($data as $value){
            if($value > 500){
                 $this->assertTrue(FALSE);
            }
        }
        
        $this->assertTrue(TRUE);
    }
   
}
