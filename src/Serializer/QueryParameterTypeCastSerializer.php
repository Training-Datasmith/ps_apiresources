<?php

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
declare (strict_types=1);
namespace Presta_Shop\Module\Api_Resources\Serializer;

use Presta_Shop_Bundle\Api_Platform\Serializer\Cqrs_Api_Serializer;
/**
 * Extends CQRSApiSerializer to add automatic type casting for query parameters.
 */
class Query_Parameter_Type_Cast_Serializer extends Cqrs_Api_Serializer
{
    /**
     * {@inheritdoc}
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (is_array($data) && str_starts_with($type, 'PrestaShop\PrestaShop\Core\Domain\\')) {
            $data = $this->cast_query_parameters_to_expected_types($data, $type);
        }
        return parent::denormalize($data, $type, $format, $context);
    }
    /**
     * Cast string query parameter values to their expected types based on the CQRS query constructor.
     */
    private function cast_query_parameters_to_expected_types(array $data, string $query_class): array
    {
        try {
            $reflection = new \ReflectionClass($query_class);
            $constructor = $reflection->get_constructor();
            if (!$constructor) {
                return $data;
            }
            foreach ($constructor->get_parameters() as $parameter) {
                $param_name = $parameter->get_name();
                if (!array_key_exists($param_name, $data)) {
                    continue;
                }
                if (!is_string($data[$param_name])) {
                    continue;
                }
                $type = $parameter->get_type();
                if (!$type instanceof \ReflectionNamedType) {
                    continue;
                }
                if (!$type->is_builtin()) {
                    continue;
                }
                $data[$param_name] = match ($type->get_name()) {
                    'int' => (int) $data[$param_name],
                    'float' => (float) $data[$param_name],
                    'bool' => filter_var($data[$param_name], FILTER_VALIDATE_BOOLEAN),
                    default => $data[$param_name],
                };
            }
        } catch (\Reflection_Exception) {
            return $data;
        }
        return $data;
    }
}