<?php

namespace App\Traits;

use Exception;

trait Encryptable
{
    /**
     * Decrypt attributes.
     *
     * @param string $key
     * @return void
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable)) {
            try {
                $value = decrypt($value);
                if (in_array($key, $this->casts) && $this->casts[$key] == 'array') {
                    $value = json_decode($value);
                }
            } catch (Exception $e) {
                //
            }
        }

        return $value;
    }

    /**
     * Encrypt attributes.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            if (is_array($value)) {
                $value = encrypt($value);
                if (in_array($key, $this->casts) && $this->casts[$key] == 'array') {
                    $value = json_encode($value);
                }
            } else {
                $value = encrypt($value);
            }
        }

        return parent::setAttribute($key, $value);
    }
}
