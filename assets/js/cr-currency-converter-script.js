/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function crDropdown(e) {
    var id = e.id;
    jQuery("ul[aria-labelledby = " + id + " ]").addClass("show");
}
function crSetCurrency(e) {
    var symbol = jQuery(e).attr('datasymbol');
    var converter = jQuery(e).closest('ul').attr('aria-labelledby');

    jQuery("button[id = " + converter + " ]").attr('symbol', symbol)
    jQuery("button[id = " + converter + " ]").html("<span class=\"flags flags-" + symbol.toLowerCase() + "\"></span><span class=\"currency-symbol\">" + symbol + "</span>&nbsp<span class=\"caret\"></span>");
}


// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if ( jQuery('.btn-currencies').length > 0 && ! jQuery('.btn-currencies').is(event.target) && jQuery('.btn-currencies').has(event.target).length === 0 && jQuery('#cr-curriencies-dropdown').css('display') === 'block' ) {
    jQuery('#cr-curriencies-dropdown').removeClass('show');
  }
  if ( jQuery('.btn-currencies').length > 0 && ! jQuery('.btn-currencies').is(event.target) && jQuery('.btn-currencies').has(event.target).length === 0 && jQuery('#cr-curriencies-dropdown2').css('display') === 'block' ) {
    jQuery('#cr-curriencies-dropdown2').removeClass('show');
  }
}
