<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pentarim\SyliusAffiliateBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Channel\Model\ChannelInterface;


class AffiliateGoal implements AffiliateGoalInterface
{
    /**
     * Id.
     *
     * @var int
     */
    protected $id;

    /**
     * Name.
     *
     * @var string
     */
    protected $name;

    /**
     * Description.
     *
     * @var string
     */
    protected $description;

    /**
     * Channels in which this product is available.
     *
     * @var ChannelInterface[]|Collection
     */
    protected $channels;
    
    /**
     * Usage limit
     *
     * @var integer
     */
    protected $usageLimit;

    /**
     * Number of times this coupon has been used.
     *
     * @var int
     */
    protected $used = 0;

    /**
     * Associated rules.
     *
     * @var Collection|RuleInterface[]
     */
    protected $rules;

    /**
     * Associated provisions.
     *
     * @var Collection|ProvisionInterface[]
     */
    protected $provisions;

    /**
     * Rewards made for this goal.
     *
     * @var Collection|RewardInterface[]
     */
    protected $rewards;

    /**
     * Start date.
     *
     * @var \DateTime
     */
    protected $startsAt;

    /**
     * End date.
     *
     * @var \DateTime
     */
    protected $endsAt;

    /**
     * Last time updated
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * Creation date
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->channels = new ArrayCollection();
        $this->rules = new ArrayCollection();
        $this->provisions = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsageLimit()
    {
        return $this->usageLimit;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsageLimit($usageLimit)
    {
        $this->usageLimit = $usageLimit;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementUsed()
    {
        ++$this->used;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRules()
    {
        return !$this->rules->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRule(RuleInterface $rule)
    {
        return $this->rules->contains($rule);
    }

    /**
     * {@inheritdoc}
     */
    public function addRule(RuleInterface $rule)
    {
        if (!$this->hasRule($rule)) {
            $rule->setGoal($this);
            $this->rules->add($rule);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRule(RuleInterface $rule)
    {
        if ($this->hasRule($rule)) {
            $rule->setGoal(null);
            $this->rules->removeElement($rule);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasProvisions()
    {
        return !$this->provisions->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function getProvisions()
    {
        return $this->provisions;
    }

    /**
     * {@inheritdoc}
     */
    public function hasProvision(ProvisionInterface $provision)
    {
        return $this->provisions->contains($provision);
    }

    /**
     * {@inheritdoc}
     */
    public function addProvision(ProvisionInterface $provision)
    {
        if (!$this->hasProvision($provision)) {
            $provision->setGoal($this);
            $this->provisions->add($provision);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeProvision(ProvisionInterface $provision)
    {
        if ($this->hasProvision($provision)) {
            $provision->setGoal(null);
            $this->provisions->removeElement($provision);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRewards()
    {
        return !$this->rewards->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function getRewards()
    {
        return $this->rewards;
    }

    /**
     * {@inheritdoc}
     */
    public function hasReward(RewardInterface $reward)
    {
        return $this->rewards->contains($reward);
    }

    /**
     * {@inheritdoc}
     */
    public function addReward(RewardInterface $reward)
    {
        if (!$this->hasReward($reward)) {
            $reward->setGoal($this);
            $this->rewards->add($reward);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeReward(RewardInterface $reward)
    {
        if ($this->hasReward($reward)) {
            $reward->setGoal(null);
            $this->rewards->removeElement($reward);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStartsAt()
    {
        return $this->startsAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setStartsAt(\DateTime $startsAt = null)
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndsAt()
    {
        return $this->endsAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setEndsAt(\DateTime $endsAt = null)
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * {@inheritdoc}
     */
    public function setChannels(Collection $channels)
    {
        $this->channels = $channels;
    }

    /**
     * {@inheritdoc}
     */
    public function addChannel(ChannelInterface $channel)
    {
        if (!$this->hasChannel($channel)) {
            $this->channels->add($channel);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeChannel(ChannelInterface $channel)
    {
        if ($this->hasChannel($channel)) {
            $this->channels->removeElement($channel);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasChannel(ChannelInterface $channel)
    {
        return $this->channels->contains($channel);
    }
    
}
