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
use Pentarim\SyliusAffiliateBundle\Model\ProvisionInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;

interface AffiliateProvisionInterface
{
    /**
     * Applies the reward to its affiliation.
     *
     * @param object             $subject
     * @param ProvisionInterface $provision
     * @param AffiliateInterface $affiliate
     *
     * @throws UnexpectedTypeException
     */
    public function apply($subject, ProvisionInterface $provision, AffiliateInterface $affiliate);

    /**
     * Returns the form name related to this provision.
     *
     * @return string
     */
    public function getConfigurationFormType();
}
