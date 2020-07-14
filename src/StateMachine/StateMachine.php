<?php

namespace Src\StateMachine\StateMachine;

use FSM\Client;
use Src\StateMachine\States\InWork\StateInWork;
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
        // для чего установка через интерфейсы?
        $stateCreated->setType(StateInterface::TYPE_INITIAL);

        $stateInWork = new StateInWork();
        $stateInWork->setName('in_work');
        $stateInWork->setType(StateInterface::TYPE_REGULAR);

        $stateFinished = new StateFinished();
        $stateFinished->setName('finished');
        $stateFinished->setType(StateInterface::TYPE_FINITE);

        $stateError = new StateEndWithError();
        $stateError->setName('error');
        $stateError->setType(StateInterface::TYPE_FINITE);

        $this
            ->addState($stateCreated)
            ->addState($stateInWork)
            ->addState($stateFinished)
            ->addState($stateError)
            ->setInitialState($stateCreated)
            ->createTransition('send_in_work', 'created', 'in_work')
            ->createTransition('finished', 'in_work', 'finished')
            ->createTransition('finished_with_error', 'in_work', 'error');
    }
}