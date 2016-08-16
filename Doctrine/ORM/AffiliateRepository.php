<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pentarim\SyliusAffiliateBundle\Doctrine\ORM;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * Affiliate repository.
 *
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class AffiliateRepository extends EntityRepository
{
    public function getFormQueryBuilder()
    {
        return $this->createQueryBuilder('o');
    }
}
