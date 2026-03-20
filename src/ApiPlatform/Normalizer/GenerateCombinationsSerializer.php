<?php

declare (strict_types=1);
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Normalizer;

use Presta_Shop\Presta_Shop\Core\Domain\Product\Combination\Command\Generate_Product_Combinations_Command;
use Presta_Shop\Presta_Shop\Core\Domain\Shop\Value_Object\Shop_Constraint;
use Presta_Shop_Bundle\Api_Platform\Normalizer\Shop_Constraint_Normalizer;
use Symfony\Component\Serializer\Normalizer\Denormalizer_Interface;
class Generate_Combinations_Serializer implements Denormalizer_Interface
{
    public function __construct(private readonly Shop_Constraint_Normalizer $shop_constraint_normalizer)
    {
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        $grouped_attributes = [];
        foreach ($data['groupedAttributes'] as $attribute_group) {
            $grouped_attributes[$attribute_group['attributeGroupId']] = array_map(static fn($attribute_id): int => (int) $attribute_id, $attribute_group['attributeIds']);
        }
        return new Generate_Product_Combinations_Command($data['productId'], $grouped_attributes, $this->shop_constraint_normalizer->denormalize($data['_context']['shopConstraint'], Shop_Constraint::class));
    }
    public function supports_denormalization(mixed $data, string $type, ?string $format = null): bool
    {
        return $type === Generate_Product_Combinations_Command::class;
    }
    public function get_supported_types(?string $format): array
    {
        return [Generate_Product_Combinations_Command::class => true, 'object' => null, '*' => null];
    }
}