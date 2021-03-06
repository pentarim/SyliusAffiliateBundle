<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pentarim\SyliusAffiliateBundle\Form\Type\Rule;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

/**
 * Contains affiliate Route visit rule configuration form type.
 *
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class RouteVisitConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('route', 'text', array(
                'label' => 'sylius.form.rule.route_visit_configuration.route',
                'constraints' => array(
                    new NotBlank(),
                    new Type(array('type' => 'string')),
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_affiliate_rule_route_visit_configuration';
    }
}