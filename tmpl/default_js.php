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
	$doc = JFactory::getDocument();
	$doc->addScriptOptions('siteUrl',JUri::root());

?>

<script type="text/javascript">
	//<![CDATA[		
	jQuery(document).ready(function ($) {
		;
		(function (minicart) {
			var $minicart = $(minicart);
			/*
			 * Set display jscrollpanel
			 */
			//var  jscrollDisplay = function (){
			$('.mc-list-inner', $minicart).mCustomScrollbar({
				scrollInertia: 550,
				horizontalScroll: false,
				mouseWheelPixels: 116,
				autoDraggerLength: true,
				scrollButtons: {
					enable: true,
					scrollAmount: 116
				},
				advanced: {
					updateOnContentResize: true,
					autoScrollOnFocus: false
				}, theme: "dark"
			});
			//return true;
			//}

			var $_mc_wrap = $('.mc-wrap', $minicart);
			var $_mc_header = $('.mc-header', $minicart);
			var $_mc_content = $('.mc-content', $_mc_wrap);
			var _posLR = function () {
				var $_width_minicart = $minicart.width(), $_posleft = $minicart.offset().left,
					$_posright = $(window).innerWidth() - $_width_minicart - $_posleft,
					$_width_content = $_mc_content.width();
				if (($_posleft + $_width_content) > $(window).innerWidth()) {
					if (!$_mc_wrap.hasClass('mc-right')) {
						$_mc_wrap.removeClass('mc-left').addClass('mc-right');
					}
				} else {
					if (!$_mc_wrap.hasClass('mc-left')) {
						$_mc_wrap.removeClass('mc-right').addClass('mc-left');
					}
				}
			};

			_posLR();

			$(window).resize(function () {
				_posLR();
			});
			//jscrollDisplay();
			/*
			 * MouseOver - MouseOut
			 */
            $_mc_wrap.bind('mouseenter ', function (e) {
                var $this = $(this);
                if ($this.hasClass('over')) {
                    return;
                }
                if ($minicart.data('timeout')) {
                    clearTimeout($minicart.data('timeout'));
                }
                var timeout = setTimeout(function () {
                    $this.addClass('over');
                    $('.mc-content', $this).stop(false, true).slideDown('slow');
                    //jscrollDisplay();
                }, 300);
                $minicart.data('timeout', timeout);

            }).bind('mouseleave ', function (e) {
                var $this = $(this);
                if ($minicart.data('timeout')) {
                    clearTimeout($minicart.data('timeout'));
                }
                var timeout = setTimeout(function () {
                    $('.mc-content', $this).stop(false, true).slideUp('fast');
                    $this.removeClass('over');

                }, 300);
                $minicart.data('timeout', timeout);
            });
			
			
			$_mc_header.bind('touchstart', function (e) {
				if ($minicart.data('timeout')) {
					clearTimeout($minicart.data('timeout'));
				}
				var timeout = setTimeout(function () {
					$('.mc-content', $_mc_wrap).slideToggle();
				}, 300);
				$minicart.data('timeout', timeout);
			});
				
			  
			


			/*
			 * Event Addtocart Button - no load page
			 */
			var _timeout;
			function _addTocart() {
				$('input[name="addtocart"]').off('click.sjaddtocart').on('click.sjaddtocart',function () {
					if(typeof _timeout == 'undefined')
						_timeout = 0;
					clearTimeout(_timeout);
					_timeout = setTimeout(function () {
						productsRefreshMiniVm();
					},2500);
				});
			}
			var _time = 0;
			if(_time)
				clearInterval(_time);
			_time = setInterval(_addTocart, 1000);
			
			var $_mark_process = $('.mc-process', $minicart);
			var _processGeneral = function () {
				var $_product = $('.mc-product', $minicart);
				$_product.each(function () {
					var $_prod = $(this);
					var $_pid = $_prod.attr('data-product-id');
					var $_quantity = $($_prod.find('.mc-quantity'));
					$_quantity.click(function () {
						return false;
					});
					/*-- process when click quantity control and change value input quantity --*/
					$('.quantity-control', $_prod).each(function () {
						$(this).children().click(function () {
							var Qtt = parseInt($_quantity.val());
							if ($(this).is('.quantity-plus')) {
								$_quantity.val(Qtt + 1);
							} else {
								if (!isNaN(Qtt) && Qtt > 1) {
									$_quantity.val(Qtt - 1);
								} else {
									$_quantity.val(1);
								}
							}
                            var $paren = $(this).closest('.mc-product')
							var productId = $paren.data('product-id') ;
							var val = $_quantity.val();

                            if (typeof GNZ11 !== 'function') {
                                console.group('GNZ11 Error');
                                console.error('Необходима библиотека GNZ11 ');
                                console.warn('Ссылка для загрузки - https://github.com/Gates-Joomla/library_GNZ11')
                                console.warn('Содежимое архива из директории library_GNZ11-master - расспаковать в директорию /libraries/GNZ11 ')
                                console.groupEnd();
                                return ;
                            }
                            
							
                            gnz11.getModul('Ajax').then(function( Ajax ){
                                Joomla.loadOptions({
                                    GNZ11: {
                                        Ajax: {
                                            'csrf.token': Joomla.getOptions('csrf.token'),
                                        }
                                    }
                                });
                                
                                var data = {
                                    option : 'com_virtuemart' ,
                                    view : 'productdetails' ,
                                    task : 'recalculate' ,
                                    format : 'json' ,
                                    nosef : 1 ,
                                    quantity: val,
                                    virtuemart_product_id : [productId]
                                    
                                };
                                Ajax.send(data , 'recalculate' , {method : 'GET'}).then(function (res) {
                                    $paren.find('.PricesalesPrice .value').text(res.basePriceWithTax);
                                    
                                },function (err) {
                                    console.log(err)
                                });
                            });
            
							return false;
						});
					});
					var $timer = 0;
					$_quantity.on('keyup', function () {
						var $that = $(this);
						var _Qtt = parseInt($that.val());
						if ($timer) {
							clearTimeout($timer);
							$timer = 0;
						}
						$timer = setTimeout(function () {
							if (!isNaN(_Qtt) && _Qtt >= 1) {
								$that.val(_Qtt);
							} else {
								$that.val(0);
								if (!$_prod.hasClass('mc-product-zero')) {
									$_prod.addClass('mc-product-zero');
								}
							}
						}, 500);
					});

					/*-- Process delete product --*/
					$('.mc-remove', $_prod).click(function () {
						$_mark_process.show();
						if (!$_prod.hasClass('mc-product-zero')) {
							$_prod.addClass('mc-product-zero');
						}

						$.ajax({
							type: 'POST',
							url: ajax_url,
							data: {
								vm_minicart_ajax: 1,
								tmpl: 'component',
								option: 'com_virtuemart',
								view: 'cart',
								minicart_task: 'delete',
								cart_virtuemart_product_id: $_pid // important
							},
							success: function ($json) {
								if ($json.status && $json.status == 1) {
									productsRefreshMiniVm();
								}
							},
							dataType: 'json'
						});
					});

				});
			};

			_processGeneral();

			/*
			 * Update Products
			 */
			$('.mc-update-btn', $minicart).click(function () {
				var array_id = [], array_qty = [], array_index = [];
				var $_flag = false;
				$('.mc-product', $minicart).each(function () {
					var $this = $(this);
					var $_pid = $this.attr('data-product-id');
					var $_pindex = $this.attr('data-index');
					var $_quantity = $($this.find('.mc-quantity'));
					var $_old_quantity = $this.attr('data-old-quantity');
					if ($_quantity.val() != $_old_quantity) {
						$_flag = true;
					}
					array_id.push($_pid);
					array_qty.push($_quantity.val());
					array_index.push($_pindex);
				});
				if ($_flag) {
					$_mark_process.show();
					$.ajax({
						type: 'POST',
						url: ajax_url,
						data: {
							vm_minicart_ajax: 1,
							tmpl: 'component',
							option: 'com_virtuemart',
							view: 'cart',
							minicart_task: 'update',
							cart_virtuemart_product_id: array_id,
							quantity: array_qty,
							product_index:array_index
						},
						success: function ($json) {
							if ($json.status && $json.status == 1) {
								productsRefreshMiniVm();
							}
						},
						dataType: 'json'
					});
				}
			});


			/*
			 *  Ajax url
			 */
			//var ajax_url = '<?php //echo $cart->ajaxurl; ?>//';
			var ajax_url = '<?=JUri::root(); ?>';

			/*
			 * Refresh
			 */
			function productsRefreshMiniVm (cart) {
				var $cart = cart ? $(cart) : $minicart;
				$.ajax({
					type: 'POST',
					url: ajax_url,
					data: {
						vm_minicart_ajax: 1,
						option: 'com_virtuemart',
						minicart_task: 'refresh',
						minicart_modid: '<?php echo $module->id; ?>',
						view: 'cart',
						tmpl: 'component'
					},
					success: function (list) {
						var $mpEmpty = $cart.find('.mc-product-zero');
						$('.mc-product-wrap', $cart).html($.trim(list.list_html));
						$('.mc-totalprice ,.mc-totalprice-footer', $cart).html(list.billTotal);
						$('.mc-totalproduct', $cart).html(list.length);
						
						$('.sj-minicart-pro').find('.mc-header small').text(list.textCount);
						
						
						_processGeneral();
						if (list.length > 0) {
							$mpEmpty.fadeOut('slow').remove();
							$cart.removeClass('mc-cart-empty');
						} else {
							$cart.addClass('mc-cart-empty');
						}
						if (list.length > 1) {
							$cart.find('.mc-status').html('<?php echo JText::_('ITEMS') ?>');
						} else {
							$cart.find('.mc-status').html('<?php echo JText::_('ITEM') ?>');
						}
						$_mark_process.hide();
						_posLR();
					},
					dataType: 'json'
				});
				return;
			}

			/*
			 *  Set coupon
			 */

			var $_coupon_btn_add = $('.coupon-button-add', $minicart),
				$_preloader = $('.preloader', $minicart),
				$_coupon_mesg = $('.coupon-message', $minicart),
				$_coupon_title = $('.coupon-title', $minicart),
				$_coupon_input = $('.coupon-input', $minicart),
				$_coupon_label = $('.coupon-label', $minicart),
				$_coupon_close = $('.coupon-close', $minicart),
				$_coupon_code = $('.coupon-code', $minicart);
			$_coupon_btn_add.click(function () {
				if($_coupon_code.val() == 'Enter your Coupon code' ||  $_coupon_code.val() == ''){
					$('.mc-coupon', $minicart).after("<p class='add-messeger'>Error: Text Has No Coupon !</p>");
					$('.add-messeger', $minicart).delay(500).fadeOut(3000);
				}

				if ($_coupon_code.val() != '' && $_coupon_code.val() != '<?php echo JText::_('PLACER_HOLDER_COUPON'); ?>') {
					$_mark_process.show();
					$.ajax({
						type: 'POST',
						url: ajax_url,
						data: {
							vm_minicart_ajax: 1,
							option: 'com_virtuemart',
							view: 'cart',
							minicart_task: 'setcoupon',
							coupon_code: $_coupon_code.val(),
							tmpl: 'component'
						},
						success: function ($json) {
							if ($json.status && $json.status == 1 && $json.message != '') {
								$_coupon_mesg.hide();
								$_coupon_input.hide();
								$_coupon_label.show();
								$_coupon_title.show();
								$('.coupon-text', $minicart).html($json.message);
								productsRefreshMiniVm();
							} else {
								$_mark_process.hide();
								$_coupon_title.show();
								$_coupon_input.show();
								$_coupon_mesg.show();
								$_coupon_mesg.delay(300).fadeOut(3000);
							}

						},
						dataType: 'json'
					});
				}
			});

			/*
			 * Close coupon
			 */
			$_coupon_close.click(function () {
				$_mark_process.show();
				$_coupon_label.hide();
				$_coupon_title.show();
				$_coupon_input.show();
				$_coupon_code.val('<?php echo JText::_('PLACER_HOLDER_COUPON'); ?>');
				$.ajax({
					type: 'POST',
					url: ajax_url,
					data: {
						vm_minicart_ajax: 1,
						view: 'cart',
						option: 'com_virtuemart',
						minicart_task: 'setcoupon',
						coupon_code: 'null',
						tmpl: 'component'
					},
					success: function ($json) {
						productsRefreshMiniVm();
					},
					dataType: 'json'
				});

			});

		})('#<?php echo $tag_id;?>');
	});
	//]]>
</script>