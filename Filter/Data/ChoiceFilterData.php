<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Filter\Data;

class ChoiceFilterData extends FilterData
{
    /**
     * Returns choices data.
     *
     * @return array
     */
    public function getChoices()
    {
        $choices = $this->getParameters()['choices'];
        $values = $this->getValue();

        foreach ($choices as $value => $choice) {
            $choices[$value]['selected'] = is_array($values) && in_array($value, $values);
        }

        return $choices;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return is_array($this->getValue());
    }
}
