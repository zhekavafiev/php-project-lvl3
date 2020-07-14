<?php

namespace Src\StateMachine\States\Finished;

use FSM\State\State;

class StateFinished extends State
{
    public function foo($check)
    {
        echo "This state not worked\n";
    }

}
