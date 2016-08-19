<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pentarim\SyliusAffiliateBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class DateRange extends Constraint
{
    public $message = 'sylius.affiliate.end_date_cannot_be_set_prior_start_date';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return [self::CLASS_CONSTRAINT];
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'sylius_affiliate_date_range_validator';
    }
}
