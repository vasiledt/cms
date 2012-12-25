<?php

class Entity_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/* Get data from DB */
	function getItem($context = array(), $fields = "*", $flags = "", $cacheArr = array()) {
		$CI = &get_instance();
		// $trace = debug_backtrace();
		// $trace = $trace[0]['file'].', line: '.$trace[0]['line'];
		$this->db->select($fields, FALSE);
		$this->setContext($context);
		unset($context);
		return $this->db->get()->result_array();
	}

	/* Save data to DB */
	function saveItem($context, $values) {
		$this->setContext($context);

		$isInsert = TRUE;
		if (isset($context["where"]) && !empty($context["where"])) {
			$isInsert = FALSE;
		}

		if ($isInsert) { // insert
			if ($this->db->insert($context['table'], $values)) {
				return $this->db->insert_id();
			} else { // error
				return FALSE;
			}
		} else { // update
			if ($item = $this->db->update($context['table'], $values)) {
				if (isset($context["where"]["id"])){
					return $context["where"]["id"];
				}
				return $item;
			} else { // error
				return FALSE;
			}
		}
	}

	/* delete data from DB */
	function deleteItem($context) {
		$table = $context['table'];

		$this->setContext($context);
		unset($context['table']);

		return $this->db->delete($table);
	}
	
	/* prepare DB context */
	function setContext($context) {
		/*$context = array(
			'table' => 'categories',
			'where' => array(),
			'where_in' => array(),
			'having' => '',
			'sort' => array(
				'priority' => 'asc'
			),
			'limit' => array(
				'from' => 1,
				'count' => 10
			),
			'join' => array(
				array(
					'type' => 'inner',
					'table' => 'seo_info',
					'on' => array(
						'categories.id = seo_info.iditem',
						'seo_info.entity = "categories"'
					)
				)
			)
		);*/

		if (isset($context['table'])) {
			$this->db->from($context['table']);
		}

		if (isset($context['where'])) {
			if (is_array($context['where'])) {
				$this->db->where($context['where']);
			} else if ( is_string($context['where']) ){
				$this->db->where($context['where']);
			}
		}

		if (isset($context['where_in'])) {
			if (is_array($context['where_in'])) {
				foreach($context['where_in'] as $field => $cond) {
					$this->db->where_in($field, $cond);
				}
			}
		}

		if (isset($context['sort'])) {
			if (is_array($context['sort'])) {
				foreach ($context['sort'] as $key => $val) {
					$this->db->order_by($key, $val);
				}
			}
		}

		if (isset($context['limit'])) {
			if (is_array($context['limit'])) {
				$this->db->limit($context['limit']['count'], $context['limit']['from']);
			}
		}

		if (isset($context['group_by'])) {
			$this->db->group_by($context['group_by']);
		}

		if (isset($context['having'])) {
			$this->db->having($context['having']);
		}
		
		if (isset($context['join'])) {
			if (is_array($context['join'])) {
				foreach ($context['join'] as $join) {
					$this->db->join($join['table'], implode(' AND ', $join['on']), $join['type']);
				}
			}
		}
	}

}

/* End of file Entity_model.php */
/* Location: ./application/models/Entity_model.php */ 