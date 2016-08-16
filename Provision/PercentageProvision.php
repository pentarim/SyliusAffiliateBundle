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

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Pentarim\SyliusAffiliateBundle\Model\AffiliateInterface;
use Pentarim\SyliusAffiliateBundle\Model\ProvisionInterface;


class PercentageProvision extends AbstractProvision
{

    /**
     * {@inheritdoc}
     */
    public function apply($subject, ProvisionInterface $provision, AffiliateInterface $affiliate)
    {
        $configuration = $provision->getConfiguration();

        $adjustment = $this->createReward($affiliate, $provision->getGoal());
        $adjustment->setAmount((int) round($subject->getTotal() * $configuration['percentage']));
    }

    /**
     * {@inheritdoc}
     */
    public function execute($subject, array $configuration, AffiliateInterface $affiliate)
    {
        if (!$subject instanceof OrderInterface && !$subject instanceof OrderItemInterface) {
            throw new UnexpectedTypeException(
                $subject,
                'Sylius\Component\Core\Model\OrderInterface or Sylius\Component\Core\Model\OrderItemInterface'
            );
        }

        return parent::execute($subject, $configuration, $affiliate);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationFormType()
    {
        return 'sylius_affiliate_provision_percentage_configuration';
    }
}
