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

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;
use Pentarim\SyliusAffiliateBundle\Repository\AffiliateGoalRepositoryInterface;


/**
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class AffiliateGoalRepository extends EntityRepository implements AffiliateGoalRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findActiveByChannel(ChannelInterface $channel)
    {
        $queryBuilder = $this->getCollectionQueryBuilder();

        $this->filterByActive($queryBuilder);

        $queryBuilder
            ->innerJoin('o.channels', 'channel')
            ->andWhere($queryBuilder->expr()->eq('channel', ':channel'))
            ->setParameter('channel', $channel)
        ;

        return $queryBuilder
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findActive()
    {
        $qb = $this->getCollectionQueryBuilder('o');

        $this->filterByActive($qb);

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

    protected function getCollectionQueryBuilder()
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.rules', 'r')
            ->addSelect('r')
            ->leftJoin('o.provisions', 'p')
            ->addSelect('p');
    }


    protected function filterByActive(QueryBuilder $qb, \Datetime $date = null)
    {
        if (null === $date) {
            $date = new \Datetime();
        }

        return $qb
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->isNull('o.startsAt'),
                    $qb->expr()->lt('o.startsAt', ':date')
                )
            )
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->isNull('o.endsAt'),
                    $qb->expr()->gt('o.endsAt', ':date')
                )
            )
            ->setParameter('date', $date)
            ;
    }
}