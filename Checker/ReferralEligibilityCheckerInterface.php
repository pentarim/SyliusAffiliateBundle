<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pentarim\SyliusAffiliateBundle\Checker;

use Pentarim\SyliusAffiliateBundle\Model\AffiliateInterface;
use Pentarim\SyliusAffiliateBundle\Model\AffiliateGoalInterface;

interface ReferralEligibilityCheckerInterface
{
    /**
     * @param AffiliateGoalInterface    $goal
     * @param AffiliateInterface        $affiliate
     * @param mixed                     $subject
     *
     * @return Boolean
     */
    public function isEligible(AffiliateGoalInterface $goal, AffiliateInterface $affiliate, $subject = null);
}
