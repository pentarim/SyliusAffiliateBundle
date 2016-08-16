<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pentarim\SyliusAffiliateBundle\Context;

use Pentarim\SyliusAffiliateBundle\Model\AffiliateInterface;
use Pentarim\SyliusAffiliateBundle\Resolver\AffiliateResolverInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Affiliate context
 *
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class AffiliateContext implements AffiliateContextInterface
{
    /**
     * @var AffiliateInterface
     */
    protected $affiliate;

    /**
     * @var AffiliateResolverInterface
     */
    protected $affiliateResolver;

    /**
     * @var string
     */
    protected $queryParameter;

    /**
     * @var string
     */
    protected $cookieName;

    /**
     * @var int
     */
    protected $cookieLifetime;

    /**
     * @param AffiliateResolverInterface $affiliateResolver
     * @param $queryParameter
     * @param $cookieName
     * @param $cookieLifetime
     */
    public function __construct(
        AffiliateResolverInterface $affiliateResolver,
        $queryParameter,
        $cookieName,
        $cookieLifetime
    ) {
        $this->affiliateResolver = $affiliateResolver;
        $this->queryParameter    = $queryParameter;
        $this->cookieName        = $cookieName;
        $this->cookieLifetime    = $cookieLifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function getAffiliate()
    {
        return $this->affiliate;
    }

    /**
     * {@inheritdoc}
     */
    public function setAffiliate(AffiliateInterface $affiliate)
    {
        $this->affiliate = $affiliate;

        return $this;
    }

    /**
     * Does the context contain an affiliate.
     *
     * @return bool
     */
    public function hasAffiliate()
    {
        return $this->getAffiliate() instanceof AffiliateInterface;
    }

    /**
     * @inheritdoc
     */
    public function onKernelRequest(KernelEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();

        if (($request->query->has($this->queryParameter) && !$request->cookies->has($this->cookieName))) {
            $referralCode = $request->query->get($this->queryParameter);
        } elseif ($request->cookies->has($this->cookieName)) {
            $referralCode = $request->cookies->get($this->cookieName);
        } else {
            return;
        }

        $affiliate = $this->affiliateResolver->resolve($referralCode);

        if ($affiliate instanceof AffiliateInterface) {
            $this->setAffiliate($affiliate);
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        if ($this->hasAffiliate()) {
            $response = $event->getResponse();
            $response->headers->setCookie(new Cookie($this->cookieName, $this->getAffiliate()->getReferralCode(), new \DateTime($this->cookieLifetime)));
            $event->setResponse($response);
        }
    }
}
