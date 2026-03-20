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
namespace Presta_Shop\Module\Api_Resources\Api_Platform\Resources\Tax;

use Api_Platform\Metadata\Api_Property;
use Api_Platform\Metadata\Api_Resource;
use Presta_Shop\Decimal\Decimal_Number;
use Presta_Shop\Presta_Shop\Core\Search\Filters\Tax_Filters;
use Presta_Shop_Bundle\Api_Platform\Metadata\Paginated_List;
use Presta_Shop_Bundle\Api_Platform\Provider\Query_List_Provider;
#[Api_Resource(operations: [new Paginated_List(uriTemplate: '/taxes', scopes: ['tax_read'], ApiResourceMapping: ['[id_tax]' => '[taxId]', '[active]' => '[enabled]'], gridDataFactory: 'prestashop.core.grid.data_factory.tax', provider: Query_List_Provider::class, filtersClass: Tax_Filters::class, filtersMapping: ['[taxId]' => '[id_tax]', '[enabled]' => '[active]'])], normalizationContext: ['skip_null_values' => false])]
class Tax_List
{
    #[Api_Property(identifier: true)]
    public int $tax_id;
    public string $name;
    public Decimal_Number $rate;
    public bool $enabled;
    public function set_rate(string $rate): self
    {
        $this->rate = new Decimal_Number($rate);
        return $this;
    }
}