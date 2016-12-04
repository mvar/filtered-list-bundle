<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter\Data;

/**
 * This interface defines methods each filter data holder must implement.
 */
interface FilterDataInterface
{
    /**
     * Returns filter type.
     *
     * @return string
     */
    public function getType() : string;

    /**
     * Returns filter alias.
     *
     * @return string
     */
    public function getAlias() : string;

    /**
     * Returns TRUE if filter is active, FALSE otherwise.
     *
     * @return bool
     */
    public function isActive() : bool;

    /**
     * Returns name of request parameter.
     *
     * @return string
     */
    public function getRequestParameter() : string;

    /**
     * Returns current value.
     *
     * @return mixed
     */
    public function getValue();
}
