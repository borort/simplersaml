<?php namespace SimplerSaml\Contracts;

interface User
{
    /**
     * Set the raw user array from the provider.
     * Basically just add the attributes this way to allow arrayAccess
     *
     * @param  array $user
     * @return \SimplerSaml\User
     */
    public function setRaw(array $user);

    /**
     * Map the given array onto the user's properties.
     *
     * @param  array $attributes
     * @return \SimplerSaml\User
     */
    public function map(array $attributes);
}
