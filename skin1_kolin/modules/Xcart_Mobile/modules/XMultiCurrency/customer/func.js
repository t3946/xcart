/* 
  $Id: func.js 78 2012-12-28 13:59:37Z skot $ 
 */
function setCurrencyByCountry(country_code)
{
  var currency_code = '';
  for (var i = 0; i < mc_countries.length; i++) {
    if (mc_countries[i][0] == country_code) {
      currency_code = mc_countries[i][1];
      break;
    }
  }
  if (currency_code != '') {
    $('#mc-currency option[value="' + currency_code + '"]').attr('selected', 'selected');
    $('#mc-currency').selectmenu('refresh', true)
  }
}
function setLanguageByCountry(country_code)
{
  var language_code = '';
  for (var i = 0; i < mc_countries.length; i++) {
    if (mc_countries[i][0] == country_code) {
      language_code = mc_countries[i][2];
      break;
    }
  }
  if (language_code != '') {
    $('#mc-selector-language option[value="' + language_code + '"]').attr('selected', 'selected');
    $('#mc-selector-language').selectmenu('refresh', true);
  }
}