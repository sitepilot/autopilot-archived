<?php

namespace App\Traits;

trait HasState
{
    /**
     * Returns available states.
     *
     * @return void
     */
    public static function getStates()
    {
        return [
            0 => 'Waiting for action',
            1 => 'Provisioning',
            2 => 'Provisioned',
            3 => 'Error',
            4 => 'Destroying',
            5 => 'Destroyed'
        ];
    }

    /**
     * Set state to waiting.
     *
     * @return void
     */
    public function setStateWaiting()
    {
        $this->state = 0;
        $this->save();
    }

    /**
     * Set state to provisioning.
     *
     * @return void
     */
    public function setStateProvisioning()
    {
        $this->state = 1;
        $this->save();
    }

    /**
     * Set state to active.
     *
     * @return void
     */
    public function setStateProvisioned()
    {
        $this->state = 2;
        $this->save();
    }

    /**
     * Set state to error.
     *
     * @return void
     */
    public function setStateError()
    {
        $this->state = 3;
        $this->save();
    }

    /**
     * Set state to active.
     *
     * @return void
     */
    public function setStateDestroying()
    {
        $this->state = 4;
        $this->save();
    }

    /**
     * Set state to active.
     *
     * @return void
     */
    public function setStateDestroyed()
    {
        $this->state = 5;
        $this->save();
    }
}
