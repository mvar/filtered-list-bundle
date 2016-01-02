<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter;

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
    public function getType();

    /**
     * Returns filter alias.
     *
     * @return string
     */
    public function getAlias();

    /**
     * Returns TRUE if filter is active, FALSE otherwise.
     *
     * @return bool
     */
    public function isActive();

    /**
     * Returns name of request parameter.
     *
     * @return string
     */
    public function getRequestParameter();

    /**
     * Returns entity field name.
     *
     * @return string
     */
    public function getField();

    /**
     * Returns current value.
     *
     * @return mixed
     */
    public function getValue();
}
