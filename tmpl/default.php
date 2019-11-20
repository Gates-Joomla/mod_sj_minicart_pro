<?php
/**
 * @package Sj Minicart Pro for VirtueMart
 * @version 3.0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2016 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die; ?>

<?php
if (class_exists('vmJsApi')) {
	vmJsApi::jQuery();
}
JHtml::script('modules/' . $module->module . '/assets/js/jquery.mCustomScrollbar.js');

JHtml::stylesheet('modules/' . $module->module . '/assets/css/jquery.mCustomScrollbar.css');
JHtml::stylesheet('modules/' . $module->module . '/assets/css/style.css');
$options = $params->toObject();
$tag_id = 'sj_minicart_pro_' . rand() . time();

$cls_empty = empty($cart->products) ? ' mc-cart-empty' : '';
?>

<div class="sj-minicart-pro <?php echo $cls_empty ?>" id="<?php echo $tag_id; ?>">
	<div class="mc-wrap"><!-- Begin mc-wrap -->

		<div class="mc-header ">
			
			<div class="mc-yourcart ">
				<?php //echo JText::_('YOUR_CART') ?>
				<span class="mc-totalproduct"><?php echo count($cart->products); ?></span>
				<!--<span class="mc-status"> <?php //echo (count($cart->products) > 1) ? JText::_('ITEMS') : JText::_('ITEM') ?> </span> -->
			</div>
			<div class="mc-totalprice">
				<?php echo $cart->billTotal ?>
			</div>
			
		</div>

		<div class="mc-content"><!-- Begin mc-content -->
			<div class="mc-process"></div>
			<div class="mc-empty">
				<?php echo JText::_('EMPTY_CART_LABEL'); ?>
			</div>

			<div class="mc-content-inner">
				<div class="mc-top">
					<?php $check= count($cart->products);?>
					
					<?php if ($options->product_label_display == 1) { ?>
					
						<?php
						$titles = array(' %d товар', ' %d товара', ' %d товаров');
						$number = $check ;
						$checkText = GNZ11\Document\Text::declOfNum( $number , $titles );
      
//						echo'<pre>';print_r( $res );echo'</pre>'.__FILE__.' '.__LINE__;
       
       
							if($check > 1){?>
								<span class="mc-header hidden-xs">
                                    <?php echo JText::_('THERE_ARE') ?>&nbsp;
                                    <small>
                                        <?php echo $checkText;?>&nbsp;
<!--                                        --><?php //echo $check;?><!--&nbsp;--><?php //echo JText::_('ITEMS_CART') ?>
                                    </small>&nbsp;
                                    <?php echo JText::_('IN_YOUR_CART') ?></span>
							<?php } else{ ?>
								<span class="mc-header hidden-xs">
                                    <?php echo JText::_('THERE_ARE') ?>&nbsp;
                                    <small>
                                        <?php echo $checkText;?>&nbsp;
                                        <?php //echo JText::_('ITEM_CART') ?>
                                    </small>&nbsp;<?php echo JText::_('IN_YOUR_CART') ?></span>
							<?php } ?>
					<?php } ?>
					<span class="mc-update-btn"><?php echo JText::_('BUTTON_UPDATE_LABEL') ?></span>
				</div>
				<div class="mc-list">
					<div class="mc-list-inner">
						<?php require JModuleHelper::getLayoutPath($module->module, $layout . '_list'); ?>
					</div>
				</div>
				<?php if ($options->coupon_form_display == 1) { ?>
					<div class="mc-space"></div>
					<?php require JModuleHelper::getLayoutPath($module->module, $layout . '_coupon'); ?>
				<?php }
				if ($options->goto_cart_display == 1 || $options->checkout_display == 1 || $options->total_price_display == 1) { ?>
					<div class="mc-footer">
						<?php if ($options->total_price_display == 1) { ?>
						<span class="mc-totalprice-footer PricesalesPrice">
							<?php echo $cart->billTotal ?>
						</span>
						<?php } ?>
						<?php echo ($options->goto_cart_display) ? $cart->cart_show : ''; ?>
						
						<?php if ($options->checkout_display == 1) { ?>
							<a class="mc-checkout-footer" href="<?php echo $cart->checkout; ?>">
								<span class="mc-checkout"><?php echo JText::_('BUTTON_CHECKOUT_LABEL') ?></span>
								<span class="mc-checkout-arrow"></span>
							</a>
						<?php } ?>
						<div class="clear"></div>
					</div>
				<?php } ?>
				<div class="mc-space"></div>
			</div>

		</div>
		<!-- End mc-content -->

	</div>
	<!-- End mc-wrap -->

	<!--<a class="mc-checkout-top"
	   href="<?php //echo $cart->checkout; ?>"  <?php //echo SjMiniCartProHelper::parseTarget($params->get('item_link_target')); ?> >
		<?php //echo JText::_('BUTTON_CHECKOUT_LABEL') ?>
	</a> -->

</div>

<?php @ob_start(); ?>

#<?php echo $tag_id; ?> .mc-content{
<?php echo ($options->product_list_display == 0 && $options->coupon_form_display == 0 && $options->goto_cart_display == 0 && $options->checkout_display == 0 && $options->total_price_display == 0) ? 'display:none!important;' : ''; ?>
}

#<?php echo $tag_id; ?> .mc-content .mc-content-inner{
width:<?php echo $options->cart_detail_width ?>px;
}

#<?php echo $tag_id; ?> .mc-list .mc-product-inner .mc-image{
max-width:<?php echo $maxwidthImage = ($options->cart_detail_width - 120) ?>px;
}

#<?php echo $tag_id; ?>  .mc-content .mc-content-inner  .mc-list-inner{
max-height:<?php echo $options->product_max_height ?>px;
overflow:hidden!important;
}

#<?php echo $tag_id; ?> .mc-content .mc-content-inner .mc-list,
#<?php echo $tag_id; ?> .mc-content .mc-content-inner .mc-top{
display:<?php echo ($options->product_list_display == 1) ? 'block' : 'none;' ?>
}

#<?php echo $tag_id; ?> .mc-content .mc-content-inner .mc-coupon .coupon-label,
#<?php echo $tag_id; ?> .mc-content .mc-content-inner .mc-coupon .coupon-input{
<?php echo ($options->coupon_label_display == 0) ? 'display:block; text-align:center;' : ''; ?>
}

#<?php echo $tag_id; ?> .mc-content .mc-content-inner .mc-coupon  .coupon-message{
<?php echo ($options->coupon_label_display == 0) ? 'text-align:center;padding:0;' : ''; ?>
}

<?php
$stylesheet = @ob_get_contents();
@ob_end_clean();
$docs = JFactory::getDocument();
$docs->addStyleDeclaration($stylesheet);
?>
