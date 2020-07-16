<?php

namespace Src\StateMachine;

use FSM\Client;
use Src\StateMachine\States\EndWithError\StateEndWithError;
use Src\StateMachine\States\Finished\StateFinished;
use Src\StateMachine\States\Created\StateCreated;
use FSM\State\StateInterface;

class StateMachine extends Client
{
    public function __construct()
    {
        parent::__construct();

        $stateCreated = new StateCreated();
        $stateCreated->setName('created');
        $stateCreated->setType(StateInterface::TYPE_INITIAL);

        $stateFinished = new StateFinished();
        $stateFinished->setName('finished');
        $stateFinished->setType(StateInterface::TYPE_FINITE);

        $stateError = new StateEndWithError();
        $stateError->setName('finished_with_error');
        $stateError->setType(StateInterface::TYPE_FINITE);

        $this
            ->addState($stateCreated)
            ->addState($stateFinished)
            ->addState($stateError)
            ->setInitialState($stateCreated)
            ->createTransition('finished', 'created', 'finished')
            ->createTransition('finished_with_error', 'created', 'finished_with_error');
    }
}
