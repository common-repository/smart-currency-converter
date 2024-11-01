var leftCurrency = null;
var rightCurrency = null;

jQuery(document).ready(function () {

  var cr_currency_values = function(){

    var leftSymbol = jQuery("button[id = converterTop]").attr("symbol");
    leftCurrency = parseFloat(jQuery(".dropdown-content.left li a[datasymbol = " + leftSymbol + "]").attr('data-usd-value'));

    var rightSymbol = jQuery("button[id = converterBottom]").attr("symbol");
    rightCurrency = parseFloat(jQuery(".dropdown-content.right li a[datasymbol = " + rightSymbol + "]").attr('data-usd-value'));

    jQuery(this).parent().parent().prev().prev().attr('placeholder', jQuery(this).attr('datasymbol'));

    jQuery('input[data-left]').trigger('keyup');
    jQuery('input[data-right]').trigger('keyup');

  };

  var cr_currency_convert = function(){

    var left = typeof jQuery(this).attr('data-left') !== 'undefined' ? true : false;
    var target = typeof jQuery(this).attr('data-left') !== 'undefined' ? jQuery('.currency-converter-input[data-right]') : jQuery('.currency-converter-input[data-left]');
    var amount = parseFloat(jQuery(this).val() ? jQuery(this).val() : 0);

    var result = null;

    if ( !left ) {
        result = amount * rightCurrency / leftCurrency;
    } else {
        result = amount * leftCurrency / rightCurrency;
    }

    jQuery(target).val(result.toFixed(5));

  };

  leftCurrency = parseFloat(jQuery(jQuery('.dropdown-content.left > li > a')[0]).data('usd-value'));
  rightCurrency = parseFloat(jQuery(jQuery('.dropdown-content.right > li > a')[1]).data('usd-value'));

  jQuery('.dropdown-content > li > a').on('click', cr_currency_values);
  jQuery('.currency-converter-input').on('keyup', cr_currency_convert);

});
