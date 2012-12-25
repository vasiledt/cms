<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

if ( ! function_exists('checkAccessRights') ) {
	function checkAccessRights($uRights, $rClass = '', $rMethod = '') {
		$_CI = & get_instance();
		if (empty($rClass)) {
			$rClass = $_CI->router->fetch_class();
		}
		if (empty($rMethod)) {
			$rMethod = $_CI->router->fetch_method();
		}
		// var_dump($rClass);
		// var_dump($rMethod);
		$access = FALSE; // by default everything is unnaccessible
		switch ($rMethod) {
			case 'questions':
			case 'getQuestions':
			case 'loadQuestion':
			case 'saveQuestion':
			case 'deleteQuestion':
				$access = checkAccessLevel($uRights, 2);
				break;
			case 'getCompanies':
			case 'viewCompany':
			case 'loadCompany':
			case 'saveCompany':
			case 'deleteCompany':
				$access = checkAccessLevel($uRights, 2);
				break;
			case 'companies':
				$access = checkAccessLevel($uRights, 1);
				break;
			case 'groups':
			case 'getGroups':
			case 'loadGroup':
			case 'saveGroup':
			case 'deleteGroup':
				$access = checkAccessLevel($uRights, 2);
				break;
			case 'factors':
			case 'getFactors':
			case 'loadFactor':
			case 'saveFactor':
			case 'deleteFactor':
			case 'calcFactors':
				$access = checkAccessLevel($uRights, 2);
				break;
			case 'viewFactor':
				$access = checkAccessLevel($uRights, 1);
				break;
			case 'formulas':
			case 'getFormulas':
			case 'loadFormula':
			case 'saveFormula':
			case 'deleteFormula':
				$access = checkAccessLevel($uRights, 2);
				break;
			default:
				$access = TRUE;
		}
		return $access;
	}
}
if ( ! function_exists('checkAccessLevel') ) {
	function checkAccessLevel($uLevel, $minLevel) {
		if (intval($uLevel) >= intval($minLevel)) {
			return TRUE;
		}
		return FALSE;
	}
}

/* End of file MY_system_helper.php */
/* Location: ./application/helpers/MY_system_helper.php */