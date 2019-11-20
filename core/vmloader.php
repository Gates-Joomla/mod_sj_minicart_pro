<?php
/**
 * @package Sj Minicart Pro for VirtueMart
 * @version 3.0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2016 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;

if (!function_exists('_core_register')) {
	function _core_register($class, $path)
	{
		if (file_exists($path)) {
			JLoader::register($class, $path);
		}
	}
}
if (defined('JPATH_VM_ADMINISTRATOR')) {
	if (file_exists(JPATH_ADMINISTRATOR . '/components/com_virtuemart\helpers\config.php')) {
		require_once (JPATH_ADMINISTRATOR . '/components/com_virtuemart\helpers\config.php');
	}
}
if (!class_exists('VmConfig')) {
	require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
}
VmConfig::loadConfig();

VmConfig::loadJLang('com_virtuemart.sys');
VmConfig::loadJLang('com_virtuemart');

if (defined('JPATH_VM_ADMINISTRATOR')) {
	// helpers
	_core_register('calculationHelper', JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/calculationh.php');
	_core_register('CurrencyDisplay', JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/currencydisplay.php');
	_core_register('VmImage', JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/image.php');
	_core_register('ShopFunctions', JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/shopfunctions.php');

	// models
	_core_register('VirtueMartModelCategory', JPATH_ADMINISTRATOR . '/components/com_virtuemart/models/category.php');
	_core_register('VirtueMartModelProduct', JPATH_ADMINISTRATOR . '/components/com_virtuemart/models/product.php');
	_core_register('VirtueMartModelRatings', JPATH_ADMINISTRATOR . '/components/com_virtuemart/models/ratings.php');
	_core_register('VirtueMartModelVendor', JPATH_ADMINISTRATOR . '/components/com_virtuemart/models/vendor.php');
	_core_register('VirtueMartModelVirtueMart', JPATH_ADMINISTRATOR . '/components/com_virtuemart/models/virtuemart.php');
}
if (defined('JPATH_VM_SITE')) {
	_core_register('VirtueMartCart', JPATH_VM_SITE . '/helpers/cart.php');
	_core_register('CouponHelper', JPATH_VM_SITE . '/helpers/coupon.php');
	_core_register('CouponHelper', JPATH_VM_SITE . '/helpers/coupon.php');
	_core_register('shopFunctionsF', JPATH_VM_SITE . '/helpers/shopfunctionsf.php');
}
