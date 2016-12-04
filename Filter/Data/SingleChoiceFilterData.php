<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter\Data;

/**
 * Choices aware filter data class.
 */
class SingleChoiceFilterData extends FilterData
{
    /**
     * Returns choices data.
     *
     * @return array
     */
    public function getChoices() : array
    {
        $choices = $this->getParameters()['choices'];
        $value = $this->getValue();

        foreach ($choices as $key => $choice) {
            $choices[$key]['selected'] = $value === $key;
        }

        return $choices;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive() : bool
    {
        return isset($this->getParameters()['choices'][$this->getValue()]);
    }
}
