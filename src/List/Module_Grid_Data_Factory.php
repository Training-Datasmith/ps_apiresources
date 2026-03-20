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
namespace Presta_Shop\Module\Api_Resources\List;

use Presta_Shop\Presta_Shop\Core\Grid\Data\Factory\Doctrine_Grid_Data_Factory;
use Presta_Shop\Presta_Shop\Core\Grid\Data\Grid_Data;
use Presta_Shop\Presta_Shop\Core\Grid\Query\Doctrine_Query_Builder_Interface;
use Presta_Shop\Presta_Shop\Core\Grid\Query\Query_Parser_Interface;
use Presta_Shop\Presta_Shop\Core\Grid\Record\Record_Collection;
use Presta_Shop\Presta_Shop\Core\Grid\Search\Search_Criteria_Interface;
use Presta_Shop\Presta_Shop\Core\Hook\Hook_Dispatcher_Interface;
use Presta_Shop\Presta_Shop\Core\Module\Module_Repository;
/**
 * Custom factory to enrich the data received from database.
 */
class Module_Grid_Data_Factory extends Doctrine_Grid_Data_Factory
{
    public function __construct(Doctrine_Query_Builder_Interface $grid_query_builder, Hook_Dispatcher_Interface $hook_dispatcher, Query_Parser_Interface $query_parser, string $grid_id, protected Module_Repository $module_repository)
    {
        parent::__construct($grid_query_builder, $hook_dispatcher, $query_parser, $grid_id);
    }
    public function get_data(Search_Criteria_Interface $search_criteria)
    {
        $grid_data = parent::get_data($search_criteria);
        $new_modules = [];
        foreach ($grid_data->get_records() as $module_record) {
            $module = $this->module_repository->get_module($module_record['technicalName']);
            $module_record['moduleVersion'] = $module->disk->get('version');
            $new_modules[] = $module_record;
        }
        return new Grid_Data(new Record_Collection($new_modules), $grid_data->get_records_total(), $grid_data->get_query());
    }
}