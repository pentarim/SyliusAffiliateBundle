<?php

namespace Pentarim\SyliusAffiliateBundle\Command;

use Sylius\Component\Rbac\Model\Permission;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sylius:affiliate:install')
            ->setDescription("Sets up permissions to access features provided by the bundle.")
            ->addOption(
                'skip-permissions',
                null,
                InputOption::VALUE_NONE,
                'Skip creation of permission entries'
            )
            ->addOption(
                'skip-database',
                null,
                InputOption::VALUE_NONE,
                'Skip database schema update'
            )
            ->setHelp("Usage:  <info>$ bin/console sylius:affiliate:install</info>")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Doctrine\ORM\EntityManager $manager */
        $manager = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        if (!$input->getOption('skip-database')) {
            $output->writeln('<info>Updating database...</info>');
            $this->createTables($manager);
        }

        if (!$input->getOption('skip-permissions')) {
            $output->writeln('<info>Creating permissions...</info>');
            $this->createPermissions($manager);
        }

        $output->writeln('<info>Install finished successfully.</info>');
    }

    /**
     * Create permissions required by affiliate bundle.
     *
     * @param $manager
     */
    private function createPermissions($manager)
    {
        $repository = $this->getContainer()->get('sylius.repository.permission');

        $marketingPermission = $repository->findOneBy(['code' => 'sylius.marketing']);

        $affiliateManagePermission = $this->createAffililatePermissions($marketingPermission);
        $manager->persist($affiliateManagePermission);

        $affiliateGoalManagePermission = $this->createAffililateGoalPermissions($marketingPermission);
        $manager->persist($affiliateGoalManagePermission);

        $affiliateBannerManagePermission = $this->createAffililateBannerPermissions($marketingPermission);
        $manager->persist($affiliateBannerManagePermission);

        $settingsPermission = $repository->findOneBy(['code' => 'sylius.manage.settings']);

        $affiliateSettingsPermission = $this->createAffililateSettingsPermissions($settingsPermission);
        $manager->persist($affiliateSettingsPermission);

        $manager->flush();
    }

    /**
     * Create permissions for Affiliate resource.
     *
     * @return Permission
     */
    private function createAffililatePermissions(Permission $parentPermission)
    {
        $managePermission = new Permission();
        $managePermission->setCode('sylius.manage.affiliate');
        $managePermission->setDescription('Manage affiliates');
        $managePermission->setParent($parentPermission);

        $permissions = [
            'sylius.affiliate.show'   => 'Show affiliate',
            'sylius.affiliate.index'  => 'List affiliates',
            'sylius.affiliate.create' => 'Create affiliate',
            'sylius.affiliate.update' => 'Edit affiliate',
            'sylius.affiliate.delete' => 'Delete affiliate',
        ];

        foreach ($permissions as $code => $description) {
            $permission = new Permission();
            $permission->setCode($code);
            $permission->setDescription($description);

            $managePermission->addChild($permission);
        }

        return $managePermission;
    }

    /**
     * Create permissions for AffiliateGoal resource.
     *
     * @return Permission
     */
    private function createAffililateGoalPermissions(Permission $parentPermission)
    {
        $managePermission = new Permission();
        $managePermission->setCode('sylius.manage.affiliate_goal');
        $managePermission->setDescription('Manage affiliate goals');
        $managePermission->setParent($parentPermission);

        $permissions = [
            'sylius.affiliate_goal.show'   => 'Show affiliate goal',
            'sylius.affiliate_goal.index'  => 'List affiliates goal',
            'sylius.affiliate_goal.create' => 'Create affiliate goal',
            'sylius.affiliate_goal.update' => 'Edit affiliate goal',
            'sylius.affiliate_goal.delete' => 'Delete affiliate goal',
        ];

        foreach ($permissions as $code => $description) {
            $permission = new Permission();
            $permission->setCode($code);
            $permission->setDescription($description);

            $managePermission->addChild($permission);
        }

        return $managePermission;
    }

    /**
     * Create permissions for AffiliateBanner resource.
     *
     * @return Permission
     */
    private function createAffililateBannerPermissions(Permission $parentPermission)
    {
        $managePermission = new Permission();
        $managePermission->setCode('sylius.manage.affiliate_banner');
        $managePermission->setDescription('Manage affiliate banners');
        $managePermission->setParent($parentPermission);

        $permissions = [
            'sylius.affiliate_banner.show'   => 'Show affiliate banner',
            'sylius.affiliate_banner.index'  => 'List affiliates banner',
            'sylius.affiliate_banner.create' => 'Create affiliate banner',
            'sylius.affiliate_banner.update' => 'Edit affiliate banner',
            'sylius.affiliate_banner.delete' => 'Delete affiliate banner',
        ];

        foreach ($permissions as $code => $description) {
            $permission = new Permission();
            $permission->setCode($code);
            $permission->setDescription($description);

            $managePermission->addChild($permission);
        }

        return $managePermission;
    }

    /**
     * Create permissions for Affiliate settings.
     *
     * @return Permission
     */
    private function createAffililateSettingsPermissions(Permission $parentPermission)
    {
        $settingsPermission = new Permission();
        $settingsPermission->setCode('sylius.settings.sylius_affiliate');
        $settingsPermission->setDescription('Manage affiliate settings');
        $settingsPermission->setParent($parentPermission);

        return $settingsPermission;
    }

    /**
     * Create tables.
     *
     * @param $manager
     */
    private function createTables($manager)
    {
        $queries = [
            'CREATE TABLE IF NOT EXISTS `sylius_affiliate` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `status` int(11) NOT NULL,
              `referral_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `created_at` datetime NOT NULL,
              `updated_at` datetime DEFAULT NULL,
              `customer_id` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `UNIQ_6930E17D6447454A` (`referral_code`),
              UNIQUE KEY `UNIQ_6930E17D9395C3F3` (`customer_id`),
              KEY `IDX_6930E17D6447454A` (`referral_code`),
              CONSTRAINT `FK_6930E17D9395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `sylius_customer` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci',
            'CREATE TABLE IF NOT EXISTS `sylius_affiliate_banner` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `created_at` datetime NOT NULL,
              `updated_at` datetime DEFAULT NULL,
              `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `status` tinyint(1) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci',
            'CREATE TABLE IF NOT EXISTS `sylius_affiliate_goal` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `usage_limit` int(11) DEFAULT NULL,
              `used` int(11) NOT NULL,
              `starts_at` datetime DEFAULT NULL,
              `ends_at` datetime DEFAULT NULL,
              `created_at` datetime NOT NULL,
              `updated_at` datetime DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci',
            'CREATE TABLE IF NOT EXISTS `sylius_affiliate_goal_channels` (
              `affiliate_goal_id` int(11) NOT NULL,
              `channel_id` int(11) NOT NULL,
              PRIMARY KEY (`affiliate_goal_id`,`channel_id`),
              KEY `IDX_F9144CC63B9AD1CC` (`affiliate_goal_id`),
              KEY `IDX_F9144CC672F5A1AA` (`channel_id`),
              CONSTRAINT `FK_F9144CC63B9AD1CC` FOREIGN KEY (`affiliate_goal_id`) REFERENCES `sylius_affiliate_goal` (`id`) ON DELETE CASCADE,
              CONSTRAINT `FK_F9144CC672F5A1AA` FOREIGN KEY (`channel_id`) REFERENCES `sylius_channel` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci',
            'CREATE TABLE IF NOT EXISTS `sylius_affiliate_provision` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `goal_id` int(11) DEFAULT NULL,
              `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `configuration` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT \'(DC2Type:array)\',
              PRIMARY KEY (`id`),
              KEY `IDX_421E2246667D1AFE` (`goal_id`),
              CONSTRAINT `FK_421E2246667D1AFE` FOREIGN KEY (`goal_id`) REFERENCES `sylius_affiliate_goal` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci',
            'CREATE TABLE IF NOT EXISTS `sylius_affiliate_referrals` (
              `affiliate_id` int(11) NOT NULL,
              `customer_id` int(11) NOT NULL,
              PRIMARY KEY (`affiliate_id`,`customer_id`),
              UNIQUE KEY `UNIQ_E3F8A8409395C3F3` (`customer_id`),
              KEY `IDX_E3F8A8409F12C49A` (`affiliate_id`),
              CONSTRAINT `FK_E3F8A8409395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `sylius_customer` (`id`),
              CONSTRAINT `FK_E3F8A8409F12C49A` FOREIGN KEY (`affiliate_id`) REFERENCES `sylius_affiliate` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci',
            'CREATE TABLE IF NOT EXISTS `sylius_affiliate_reward` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `affiliate_goal_id` int(11) DEFAULT NULL,
              `affiliate_id` int(11) DEFAULT NULL,
              `type` int(11) NOT NULL,
              `amount` int(11) NOT NULL,
              `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
              `created_at` datetime NOT NULL,
              `updated_at` datetime DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `IDX_4AEC133B9AD1CC` (`affiliate_goal_id`),
              KEY `IDX_4AEC139F12C49A` (`affiliate_id`),
              CONSTRAINT `FK_4AEC133B9AD1CC` FOREIGN KEY (`affiliate_goal_id`) REFERENCES `sylius_affiliate_goal` (`id`),
              CONSTRAINT `FK_4AEC139F12C49A` FOREIGN KEY (`affiliate_id`) REFERENCES `sylius_affiliate` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci',
            'CREATE TABLE IF NOT EXISTS `sylius_affiliate_rule` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `goal_id` int(11) DEFAULT NULL,
              `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `configuration` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT \'(DC2Type:array)\',
              PRIMARY KEY (`id`),
              KEY `IDX_E428EB8A667D1AFE` (`goal_id`),
              CONSTRAINT `FK_E428EB8A667D1AFE` FOREIGN KEY (`goal_id`) REFERENCES `sylius_affiliate_goal` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci',
        ];

        $manager->beginTransaction();

        foreach ($queries as $query) {
            $statement = $manager->getConnection()->prepare($query);
            $statement->execute();
        }

        $manager->commit();
    }
}
