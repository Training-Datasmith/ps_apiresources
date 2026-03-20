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
namespace Presta_Shop\Module\Api_Resources\List;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\Query_Builder;
use Presta_Shop\Presta_Shop\Core\Grid\Query\Abstract_Doctrine_Query_Builder;
use Presta_Shop\Presta_Shop\Core\Grid\Query\Doctrine_Search_Criteria_Applicator_Interface;
use Presta_Shop\Presta_Shop\Core\Grid\Search\Search_Criteria_Interface;
class Module_Query_Builder extends Abstract_Doctrine_Query_Builder
{
    public function __construct(Connection $connection, string $db_prefix, private readonly Doctrine_Search_Criteria_Applicator_Interface $search_criteria_applicator)
    {
        parent::__construct($connection, $db_prefix);
    }
    public function get_search_query_builder(Search_Criteria_Interface $search_criteria)
    {
        $builder = $this->get_module_query_builder($search_criteria)->select('m.id_module AS moduleId, m.name AS technicalName, m.active AS enabled, m.version AS installedVersion');
        $this->search_criteria_applicator->apply_sorting($search_criteria, $builder)->apply_pagination($search_criteria, $builder);
        return $builder;
    }
    public function get_count_query_builder(Search_Criteria_Interface $search_criteria)
    {
        return $this->get_module_query_builder($search_criteria)->select('COUNT(id_module)');
    }
    private function get_module_query_builder(Search_Criteria_Interface $search_criteria): Query_Builder
    {
        $qb = $this->connection->create_query_builder()->from($this->db_prefix . 'module', 'm');
        $allowed_filters = ['moduleId' => 'id_module', 'technicalName' => 'name', 'enabled' => 'active', 'version' => 'version'];
        foreach ($search_criteria->get_filters() as $filter_name => $filter_value) {
            if (!array_key_exists($filter_name, $allowed_filters)) {
                continue;
            }
            $column_name = $allowed_filters[$filter_name];
            if (in_array($filter_name, ['moduleId', 'enabled'])) {
                $qb->and_where($column_name . ' = :' . $filter_name);
                $qb->set_parameter($filter_name, $filter_value);
                continue;
            }
            $qb->and_where($column_name . ' LIKE :' . $filter_name);
            $qb->set_parameter($filter_name, '%' . $filter_value . '%');
        }
        return $qb;
    }
}