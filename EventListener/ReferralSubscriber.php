<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pentarim\SyliusAffiliateBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Pentarim\SyliusAffiliateBundle\Processor\ReferralProcessorInterface;
use Pentarim\SyliusAffiliateBundle\Context\AffiliateContextInterface;

/**
 * Subscribes to all events that are referrable and handles their processing trough its processor.
 *
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class ReferralSubscriber implements EventSubscriberInterface
{
    /**
     * Referral processor.
     *
     * @var ReferralProcessorInterface
     */
    protected $referralProcessor;

    /**
     * Affiliate context.
     *
     * @var AffiliateContextInterface
     */
    protected $affiliateContext;

    /**
     * Constructor.
     *
     * @param ReferralProcessorInterface $referralProcessor
     */
    public function __construct(ReferralProcessorInterface $referralProcessor, AffiliateContextInterface $affiliateContext)
    {
        $this->referralProcessor  = $referralProcessor;
        $this->affiliateContext   = $affiliateContext;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'kernel.request'                    => 'onKernelRequest',
            'sylius.customer.post_register'     => 'onCustomerRegistration',
            'sylius.checkout.finalize.pre_complete' => 'onCheckoutFinalized'
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function onCustomerRegistration(GenericEvent $event)
    {
        if (!$this->affiliateContext->hasAffiliate()) {
            return;
        }

        if ($this->affiliateContext->hasAffiliate()) {
            $customer  = $event->getSubject();
            $affiliate = $this->affiliateContext->getAffiliate();

            $this->referralProcessor->process($customer, $affiliate);
        }
    }

    /**
     * @param KernelEvent $event
     */
    public function onKernelRequest(KernelEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        if ($this->affiliateContext->hasAffiliate()) {
            $request   = $event->getRequest();
            $affiliate = $this->affiliateContext->getAffiliate();

            $this->referralProcessor->process($request, $affiliate);
        }
    }

    /**
     * @param GenericEvent $event
     */
    public function onCheckoutFinalized(GenericEvent $event)
    {
        $order = $event->getSubject();

        if (!$order instanceof OrderInterface) {
            throw new UnexpectedTypeException($order, OrderInterface::class);
        }

        if ($this->affiliateContext->hasAffiliate()) {
            $affiliate = $this->affiliateContext->getAffiliate();

            $this->referralProcessor->process($order, $affiliate);
        }
    }
}
