<?php

namespace Src\StateMachine\States\InWork;

use FSM\State\State;
use FSM\State\StateIntrface;

class StateInWork extends State
{
    public function foo($name)
    {
        echo "hello {$name}\n";
    }
}
