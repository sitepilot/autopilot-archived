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
            5 => 'Destroyed',
            6 => 'Testing',
            7 => 'Updating',
            20 => 'Renewing Certificates',
            21 => 'Requesting Certificate',
            40 => 'Checking WordPress State',
            41 => 'Updating WordPress',
            42 => 'Installing WordPress',
            43 => 'Search & Replace WordPress Database'
        ];
    }

    /**
     * Returns state text.
     *
     * @return string
     */
    public function getStateText()
    {
        $states = $this->getStates();

        if (isset($states[$this->state])) {
            return strtolower($states[$this->state]);
        }

        return '';
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

    /**
     * Set state to testing. 
     * 
     * @return void
     */
    public function setStateTesting()
    {
        $this->state = 6;
        $this->save();
    }

    /**
     * Set state to updating. 
     * 
     * @return void
     */
    public function setStateUpdating()
    {
        $this->state = 7;
        $this->save();
    }

    /**
     * Set state to testing. 
     * 
     * @return void
     */
    public function setStateRenewingCert()
    {
        $this->state = 20;
        $this->save();
    }

    /**
     * Set state to testing. 
     * 
     * @return void
     */
    public function setStateRequestingCert()
    {
        $this->state = 21;
        $this->save();
    }

    /**
     * Set state to checking WordPress.
     * 
     * @return void
     */
    public function setStateCheckingWp()
    {
        $this->state = 40;
        $this->save();
    }

    /**
     * Set state to updating WordPress.
     * 
     * @return void
     */
    public function setStateUpdatingWp()
    {
        $this->state = 41;
        $this->save();
    }

    /**
     * Set state to installing WordPress.
     * 
     * @return void
     */
    public function setStateInstallingWp()
    {
        $this->state = 42;
        $this->save();
    }

    /**
     * Set state to search & replace WordPress database.
     * 
     * @return void
     */
    public function setStateSearchReplaceWp()
    {
        $this->state = 43;
        $this->save();
    }

    /**
     * Returns the provisioned state index.
     *
     * @return int
     */
    public static function getProvisionedIndex()
    {
        return 2;
    }
}
