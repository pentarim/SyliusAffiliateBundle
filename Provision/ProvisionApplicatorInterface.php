<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pentarim\SyliusAffiliateBundle\Provision;

use Pentarim\SyliusAffiliateBundle\Model\AffiliateInterface;
use Pentarim\SyliusAffiliateBundle\Model\AffiliateGoalInterface;

interface ProvisionApplicatorInterface
{
    /**
     * @param object                    $subject
     * @param AffiliateInterface        $affiliate
     * @param AffiliateGoalInterface    $goal
     */
    public function apply($subject, AffiliateInterface $affiliate, AffiliateGoalInterface $goal);
}
