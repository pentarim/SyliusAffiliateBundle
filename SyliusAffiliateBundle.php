<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pentarim\SyliusAffiliateBundle;

use Pentarim\SyliusAffiliateBundle\DependencyInjection\Compiler\RegisterGoalProvisionsPass;
use Pentarim\SyliusAffiliateBundle\DependencyInjection\Compiler\RegisterRuleCheckersPass;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Pentarim\SyliusAffiliateBundle\Model\AffiliateInterface;
use Pentarim\SyliusAffiliateBundle\Model\AffiliateGoalInterface;
use Pentarim\SyliusAffiliateBundle\Model\BannerInterface;
use Pentarim\SyliusAffiliateBundle\Model\InvitationInterface;
use Pentarim\SyliusAffiliateBundle\Model\ProvisionInterface;
use Pentarim\SyliusAffiliateBundle\Model\RuleInterface;
use Pentarim\SyliusAffiliateBundle\Model\RewardInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
class SyliusAffiliateBundle extends AbstractResourceBundle
{
    /**
     * {@inheritdoc}
     */
    public function getSupportedDrivers()
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterGoalProvisionsPass());
        $container->addCompilerPass(new RegisterRuleCheckersPass());
    }

    /**
     * {@inheritdoc}
     */
    protected function getModelInterfaces()
    {
        return array(
            AffiliateInterface::class     => 'sylius.model.affiliate.class',
            BannerInterface::class        => 'sylius.model.affiliate_banner.class',
            AffiliateGoalInterface::class => 'sylius.model.affiliate_goal.class',
            ProvisionInterface::class     => 'sylius.model.affiliate_provision.class',
            RuleInterface::class          => 'sylius.model.affiliate_rule.class',
            InvitationInterface::class    => 'sylius.model.invitation.class',
            RewardInterface::class        => 'sylius.model.reward.class',
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getModelNamespace()
    {
        return 'Pentarim\SyliusAffiliateBundle\Model';
    }
}
