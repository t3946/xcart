<?php /* Smarty version 2.6.12, created on 2011-10-11 07:04:03
         compiled from payments/ps_paypal_pro.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'payments/ps_paypal_pro.tpl', 5, false),)), $this); ?>
<?php func_load_lang($this, "payments/ps_paypal_pro.tpl","lbl_paypal_api_access_username,lbl_paypal_api_access_password,lbl_paypal_api_use_method,lbl_paypal_api_signature_type,lbl_paypal_api_certificate_type,lbl_paypal_api_certificate_file,lbl_paypal_api_access_signature,lbl_cc_currency,lbl_cc_testlive_mode,lbl_cc_testlive_test,lbl_cc_testlive_live,lbl_paypal_test_mode_note,lbl_paypal_transaction_type,lbl_cc_order_prefix"); ?><table cellspacing="10">
<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_paypal_api_access_username']; ?>
:</td>
<td><input type="text" name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[param01]" size="24" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['module_data']['param01'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>

<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_paypal_api_access_password']; ?>
:</td>
<td><input type="text" name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[param02]" size="24" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['module_data']['param02'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>

<tr>
<td valign="top"><?php echo $this->_tpl_vars['lng']['lbl_paypal_api_use_method']; ?>
:</td>
<td>
<table>
<tr>
	<td><input type="radio" id="APIS" name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[param07]" value="S"<?php if ($this->_tpl_vars['module_data']['param07'] != 'C'): ?> checked="checked"<?php endif; ?> /></td>
	<td><label for="APIS"><?php echo $this->_tpl_vars['lng']['lbl_paypal_api_signature_type']; ?>
</label></td>
</tr>
<tr>
	<td><input type="radio" id="APIC" name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[param07]" value="C"<?php if ($this->_tpl_vars['module_data']['param07'] == 'C'): ?> checked="checked"<?php endif; ?> /></td>
	<td><label for="APIC"><?php echo $this->_tpl_vars['lng']['lbl_paypal_api_certificate_type']; ?>
</label></td>
</tr>
</table>
</td>
</tr>

<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_paypal_api_certificate_file']; ?>
:</td>
<td>
xcart_dir/payment/certs/<input type="text" name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[param04]" size="24" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['module_data']['param04'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</td>
</tr>

<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_paypal_api_access_signature']; ?>
:</td>
<td><input type="text" name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[param05]" size="32" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['module_data']['param05'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>

<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_cc_currency']; ?>
:</td>
<td>
<select name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[param03]">
<option value="AFA"<?php if ($this->_tpl_vars['module_data']['param03'] == 'AFA'): ?> selected="selected"<?php endif; ?>>Afghani (Afghanistan)
<option value="DZD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'DZD'): ?> selected="selected"<?php endif; ?>>Algerian Dinar (Algeria)
<option value="ADP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ADP'): ?> selected="selected"<?php endif; ?>>Andorran Peseta (Andorra)
<option value="ARS"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ARS'): ?> selected="selected"<?php endif; ?>>Argentine Peso (Argentina)
<option value="AMD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'AMD'): ?> selected="selected"<?php endif; ?>>Armenian Dram (Armenia)
<option value="AWG"<?php if ($this->_tpl_vars['module_data']['param03'] == 'AWG'): ?> selected="selected"<?php endif; ?>>Aruban Guilder (Aruba)
<option value="AUD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'AUD'): ?> selected="selected"<?php endif; ?>>Australian Dollar (Australia)
<option value="AZM"<?php if ($this->_tpl_vars['module_data']['param03'] == 'AZM'): ?> selected="selected"<?php endif; ?>>Azerbaijanian Manat (Azerbaijan)
<option value="BSD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BSD'): ?> selected="selected"<?php endif; ?>>Bahamian Dollar (Bahamas)
<option value="BHD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BHD'): ?> selected="selected"<?php endif; ?>>Bahraini Dinar (Bahrain)
<option value="THB"<?php if ($this->_tpl_vars['module_data']['param03'] == 'THB'): ?> selected="selected"<?php endif; ?>>Baht (Thailand)
<option value="PAB"<?php if ($this->_tpl_vars['module_data']['param03'] == 'PAB'): ?> selected="selected"<?php endif; ?>>Balboa (Panama)
<option value="BBD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BBD'): ?> selected="selected"<?php endif; ?>>Barbados Dollar (Barbados)
<option value="BYR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BYB'): ?> selected="selected"<?php endif; ?>>Belarussian Ruble (Belarus)
<option value="BZD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BZD'): ?> selected="selected"<?php endif; ?>>Belize Dollar (Belize)
<option value="BMD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BMD'): ?> selected="selected"<?php endif; ?>>Bermudian Dollar (Bermuda)
<option value="VEB"<?php if ($this->_tpl_vars['module_data']['param03'] == 'VEB'): ?> selected="selected"<?php endif; ?>>Bolivar (Venezuela)
<option value="BOB"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BOB'): ?> selected="selected"<?php endif; ?>>Boliviano (Bolivia)
<option value="BRL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BRL'): ?> selected="selected"<?php endif; ?>>Brazilian Real (Brazil)
<option value="BND"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BND'): ?> selected="selected"<?php endif; ?>>Brunei Dollar (Brunei Darussalam)
<option value="BGN"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BGN'): ?> selected="selected"<?php endif; ?>>Bulgarian Lev (Bulgaria)
<option value="BIF"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BIF'): ?> selected="selected"<?php endif; ?>>Burundi Franc (Burundi)
<option value="CAD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'CAD'): ?> selected="selected"<?php endif; ?>>Canadian Dollar (Canada)
<option value="CVE"<?php if ($this->_tpl_vars['module_data']['param03'] == 'CVE'): ?> selected="selected"<?php endif; ?>>Cape Verde Escudo (Cape Verde)
<option value="KYD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'KYD'): ?> selected="selected"<?php endif; ?>>Cayman Islands Dollar (Cayman Islands)
<option value="GHC"<?php if ($this->_tpl_vars['module_data']['param03'] == 'GHC'): ?> selected="selected"<?php endif; ?>>Cedi (Ghana)
<option value="XOF"<?php if ($this->_tpl_vars['module_data']['param03'] == 'XOF'): ?> selected="selected"<?php endif; ?>>CFA Franc BCEAO (Guinea-Bissau)
<option value="XAF"<?php if ($this->_tpl_vars['module_data']['param03'] == 'XAF'): ?> selected="selected"<?php endif; ?>>CFA Franc BEAC (Central African Republic)
<option value="XPF"<?php if ($this->_tpl_vars['module_data']['param03'] == 'XPF'): ?> selected="selected"<?php endif; ?>>CFP Franc (New Caledonia)
<option value="CLP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'CLP'): ?> selected="selected"<?php endif; ?>>Chilean Peso (Chile)
<option value="COP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'COP'): ?> selected="selected"<?php endif; ?>>Colombian Peso (Colombia)
<option value="KMF"<?php if ($this->_tpl_vars['module_data']['param03'] == 'KMF'): ?> selected="selected"<?php endif; ?>>Comoro Franc (Comoros)
<option value="BAM"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BAM'): ?> selected="selected"<?php endif; ?>>Convertible Marks (Bosnia And Herzegovina)
<option value="NIO"<?php if ($this->_tpl_vars['module_data']['param03'] == 'NIO'): ?> selected="selected"<?php endif; ?>>Cordoba Oro (Nicaragua)
<option value="CRC"<?php if ($this->_tpl_vars['module_data']['param03'] == 'CRC'): ?> selected="selected"<?php endif; ?>>Costa Rican Colon (Costa Rica)
<option value="CUP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'CUP'): ?> selected="selected"<?php endif; ?>>Cuban Peso (Cuba)
<option value="CYP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'CYP'): ?> selected="selected"<?php endif; ?>>Cyprus Pound (Cyprus)
<option value="CZK"<?php if ($this->_tpl_vars['module_data']['param03'] == 'CZK'): ?> selected="selected"<?php endif; ?>>Czech Koruna (Czech Republic)
<option value="GMD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'GMD'): ?> selected="selected"<?php endif; ?>>Dalasi (Gambia)
<option value="DKK"<?php if ($this->_tpl_vars['module_data']['param03'] == 'DKK'): ?> selected="selected"<?php endif; ?>>Danish Krone (Denmark)
<option value="MKD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MKD'): ?> selected="selected"<?php endif; ?>>Denar (The Former Yugoslav Republic Of Macedonia)
<option value="AED"<?php if ($this->_tpl_vars['module_data']['param03'] == 'AED'): ?> selected="selected"<?php endif; ?>>Dirham (United Arab Emirates)
<option value="DJF"<?php if ($this->_tpl_vars['module_data']['param03'] == 'DJF'): ?> selected="selected"<?php endif; ?>>Djibouti Franc (Djibouti)
<option value="STD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'STD'): ?> selected="selected"<?php endif; ?>>Dobra (Sao Tome And Principe)
<option value="DOP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'DOP'): ?> selected="selected"<?php endif; ?>>Dominican Peso (Dominican Republic)
<option value="VND"<?php if ($this->_tpl_vars['module_data']['param03'] == 'VND'): ?> selected="selected"<?php endif; ?>>Dong (Vietnam)
<option value="XCD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'XCD'): ?> selected="selected"<?php endif; ?>>East Caribbean Dollar (Grenada)
<option value="EGP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'EGP'): ?> selected="selected"<?php endif; ?>>Egyptian Pound (Egypt)
<option value="SVC"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SVC'): ?> selected="selected"<?php endif; ?>>El Salvador Colon (El Salvador)
<option value="ETB"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ETB'): ?> selected="selected"<?php endif; ?>>Ethiopian Birr (Ethiopia)
<option value="EUR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'EUR'): ?> selected="selected"<?php endif; ?>>Euro (Europe)
<option value="FKP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'FKP'): ?> selected="selected"<?php endif; ?>>Falkland Islands Pound (Falkland Islands)
<option value="FJD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'FJD'): ?> selected="selected"<?php endif; ?>>Fiji Dollar (Fiji)
<option value="HUF"<?php if ($this->_tpl_vars['module_data']['param03'] == 'HUF'): ?> selected="selected"<?php endif; ?>>Forint (Hungary)
<option value="CDF"<?php if ($this->_tpl_vars['module_data']['param03'] == 'CDF'): ?> selected="selected"<?php endif; ?>>Franc Congolais (The Democratic Republic Of Congo)
<option value="GIP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'GIP'): ?> selected="selected"<?php endif; ?>>Gibraltar Pound (Gibraltar)
<option value="HTG"<?php if ($this->_tpl_vars['module_data']['param03'] == 'HTG'): ?> selected="selected"<?php endif; ?>>Gourde (Haiti)
<option value="PYG"<?php if ($this->_tpl_vars['module_data']['param03'] == 'PYG'): ?> selected="selected"<?php endif; ?>>Guarani (Paraguay)
<option value="GNF"<?php if ($this->_tpl_vars['module_data']['param03'] == 'GNF'): ?> selected="selected"<?php endif; ?>>Guinea Franc (Guinea)
<option value="GWP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'GWP'): ?> selected="selected"<?php endif; ?>>Guinea-Bissau Peso (Guinea-Bissau)
<option value="GYD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'GYD'): ?> selected="selected"<?php endif; ?>>Guyana Dollar (Guyana)
<option value="HKD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'HKD'): ?> selected="selected"<?php endif; ?>>Hong Kong Dollar (Hong Kong)
<option value="UAH"<?php if ($this->_tpl_vars['module_data']['param03'] == 'UAH'): ?> selected="selected"<?php endif; ?>>Hryvnia (Ukraine)
<option value="ISK"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ISK'): ?> selected="selected"<?php endif; ?>>Iceland Krona (Iceland)
<option value="INR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'INR'): ?> selected="selected"<?php endif; ?>>Indian Rupee (India)
<option value="IRR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'IRR'): ?> selected="selected"<?php endif; ?>>Iranian Rial (Islamic Republic Of Iran)
<option value="IQD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'IQD'): ?> selected="selected"<?php endif; ?>>Iraqi Dinar (Iraq)
<option value="JMD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'JMD'): ?> selected="selected"<?php endif; ?>>Jamaican Dollar (Jamaica)
<option value="JOD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'JOD'): ?> selected="selected"<?php endif; ?>>Jordanian Dinar (Jordan)
<option value="KES"<?php if ($this->_tpl_vars['module_data']['param03'] == 'KES'): ?> selected="selected"<?php endif; ?>>Kenyan Shilling (Kenya)
<option value="PGK"<?php if ($this->_tpl_vars['module_data']['param03'] == 'PGK'): ?> selected="selected"<?php endif; ?>>Kina (Papua New Guinea)
<option value="LAK"<?php if ($this->_tpl_vars['module_data']['param03'] == 'LAK'): ?> selected="selected"<?php endif; ?>>Kip (Lao People's Democratic Republic)
<option value="EEK"<?php if ($this->_tpl_vars['module_data']['param03'] == 'EEK'): ?> selected="selected"<?php endif; ?>>Kroon (Estonia)
<option value="HRK"<?php if ($this->_tpl_vars['module_data']['param03'] == 'HRK'): ?> selected="selected"<?php endif; ?>>Kuna (Croatia)
<option value="KWD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'KWD'): ?> selected="selected"<?php endif; ?>>Kuwaiti Dinar (Kuwait)
<option value="MWK"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MWK'): ?> selected="selected"<?php endif; ?>>Kwacha (Malawi)
<option value="ZMK"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ZMK'): ?> selected="selected"<?php endif; ?>>Kwacha (Zambia)
<option value="AOA"<?php if ($this->_tpl_vars['module_data']['param03'] == 'AOA'): ?> selected="selected"<?php endif; ?>>Kwanza (Angola)
<option value="MMK"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MMK'): ?> selected="selected"<?php endif; ?>>Kyat (Myanmar)
<option value="GEL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'GEL'): ?> selected="selected"<?php endif; ?>>Lari (Georgia)
<option value="LVL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'LVL'): ?> selected="selected"<?php endif; ?>>Latvian Lats (Latvia)
<option value="LBP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'LBP'): ?> selected="selected"<?php endif; ?>>Lebanese Pound (Lebanon)
<option value="ALL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ALL'): ?> selected="selected"<?php endif; ?>>Lek (Albania)
<option value="HNL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'HNL'): ?> selected="selected"<?php endif; ?>>Lempira (Honduras)
<option value="SLL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SLL'): ?> selected="selected"<?php endif; ?>>Leone (Sierra Leone)
<option value="ROL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ROL'): ?> selected="selected"<?php endif; ?>>Leu (Romania)
<option value="BGL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BGL'): ?> selected="selected"<?php endif; ?>>Lev (Bulgaria)
<option value="LRD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'LRD'): ?> selected="selected"<?php endif; ?>>Liberian Dollar (Liberia)
<option value="LYD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'LYD'): ?> selected="selected"<?php endif; ?>>Libyan Dinar (Libyan Arab Jamahiriya)
<option value="SZL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SZL'): ?> selected="selected"<?php endif; ?>>Lilangeni (Swaziland)
<option value="LTL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'LTL'): ?> selected="selected"<?php endif; ?>>Lithuanian Litas (Lithuania)
<option value="LSL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'LSL'): ?> selected="selected"<?php endif; ?>>Loti (Lesotho)
<option value="MGF"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MGF'): ?> selected="selected"<?php endif; ?>>Malagasy Franc (Madagascar)
<option value="MYR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MYR'): ?> selected="selected"<?php endif; ?>>Malaysian Ringgit (Malaysia)
<option value="MTL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MTL'): ?> selected="selected"<?php endif; ?>>Maltese Lira (Malta)
<option value="TMM"<?php if ($this->_tpl_vars['module_data']['param03'] == 'TMM'): ?> selected="selected"<?php endif; ?>>Manat (Turkmenistan)
<option value="MUR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MUR'): ?> selected="selected"<?php endif; ?>>Mauritius Rupee (Mauritius)
<option value="MZM"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MZM'): ?> selected="selected"<?php endif; ?>>Metical (Mozambique)
<option value="MXN"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MXN'): ?> selected="selected"<?php endif; ?>>Mexican Peso (Mexico)
<option value="MXV"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MXV'): ?> selected="selected"<?php endif; ?>>Mexican Unidad de Inversion (Mexico)
<option value="MDL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MDL'): ?> selected="selected"<?php endif; ?>>Moldovan Leu (Republic Of Moldova)
<option value="MAD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MAD'): ?> selected="selected"<?php endif; ?>>Moroccan Dirham (Morocco)
<option value="BOV"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BOV'): ?> selected="selected"<?php endif; ?>>Mvdol (Bolivia)
<option value="NGN"<?php if ($this->_tpl_vars['module_data']['param03'] == 'NGN'): ?> selected="selected"<?php endif; ?>>Naira (Nigeria)
<option value="ERN"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ERN'): ?> selected="selected"<?php endif; ?>>Nakfa (Eritrea)
<option value="NAD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'NAD'): ?> selected="selected"<?php endif; ?>>Namibia Dollar (Namibia)
<option value="NPR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'NPR'): ?> selected="selected"<?php endif; ?>>Nepalese Rupee (Nepal)
<option value="ANG"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ANG'): ?> selected="selected"<?php endif; ?>>Netherlands (Netherlands)
<option value="YUM"<?php if ($this->_tpl_vars['module_data']['param03'] == 'YUM'): ?> selected="selected"<?php endif; ?>>New Dinar (Yugoslavia)
<option value="ILS"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ILS'): ?> selected="selected"<?php endif; ?>>New Israeli Sheqel (Israel)
<option value="TWD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'TWD'): ?> selected="selected"<?php endif; ?>>New Taiwan Dollar (Province Of China Taiwan)
<option value="NZD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'NZD'): ?> selected="selected"<?php endif; ?>>New Zealand Dollar (New Zealand)
<option value="BTN"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BTN'): ?> selected="selected"<?php endif; ?>>Ngultrum (Bhutan)
<option value="KPW"<?php if ($this->_tpl_vars['module_data']['param03'] == 'KPW'): ?> selected="selected"<?php endif; ?>>North Korean Won (Democratic People's Republic Of Korea)
<option value="NOK"<?php if ($this->_tpl_vars['module_data']['param03'] == 'NOK'): ?> selected="selected"<?php endif; ?>>Norwegian Krone (Norway)
<option value="PEN"<?php if ($this->_tpl_vars['module_data']['param03'] == 'PEN'): ?> selected="selected"<?php endif; ?>>Nuevo Sol (Peru)
<option value="MRO"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MRO'): ?> selected="selected"<?php endif; ?>>Ouguiya (Mauritania)
<option value="TOP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'TOP'): ?> selected="selected"<?php endif; ?>>Pa'anga (Tonga)
<option value="PKR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'PKR'): ?> selected="selected"<?php endif; ?>>Pakistan Rupee (Pakistan)
<option value="MOP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MOP'): ?> selected="selected"<?php endif; ?>>Pataca (Macau)
<option value="UYU"<?php if ($this->_tpl_vars['module_data']['param03'] == 'UYU'): ?> selected="selected"<?php endif; ?>>Peso Uruguayo (Uruguay)
<option value="PHP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'PHP'): ?> selected="selected"<?php endif; ?>>Philippine Peso (Philippines)
<option value="GBP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'GBP'): ?> selected="selected"<?php endif; ?>>Pound Sterling (United Kingdom)
<option value="BWP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BWP'): ?> selected="selected"<?php endif; ?>>Pula (Botswana)
<option value="QAR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'QAR'): ?> selected="selected"<?php endif; ?>>Qatari Rial (Qatar)
<option value="GTQ"<?php if ($this->_tpl_vars['module_data']['param03'] == 'GTQ'): ?> selected="selected"<?php endif; ?>>Quetzal (Guatemala)
<option value="ZAR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ZAR'): ?> selected="selected"<?php endif; ?>>Rand (South Africa)
<option value="OMR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'OMR'): ?> selected="selected"<?php endif; ?>>Rial Omani (Oman)
<option value="KHR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'KHR'): ?> selected="selected"<?php endif; ?>>Riel (Cambodia)
<option value="MVR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MVR'): ?> selected="selected"<?php endif; ?>>Rufiyaa (Maldives)
<option value="IDR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'IDR'): ?> selected="selected"<?php endif; ?>>Rupiah (Indonesia)
<option value="RUB"<?php if ($this->_tpl_vars['module_data']['param03'] == 'RUB'): ?> selected="selected"<?php endif; ?>>Russian Ruble (Russian Federation)
<option value="RUR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'RUR'): ?> selected="selected"<?php endif; ?>>Russian Ruble (Russian Federation)
<option value="RWF"<?php if ($this->_tpl_vars['module_data']['param03'] == 'RWF'): ?> selected="selected"<?php endif; ?>>Rwanda Franc (Rwanda)
<option value="SAR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SAR'): ?> selected="selected"<?php endif; ?>>Saudi Riyal (Saudi Arabia)
<option value="SCR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SCR'): ?> selected="selected"<?php endif; ?>>Seychelles Rupee (Seychelles)
<option value="SGD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SGD'): ?> selected="selected"<?php endif; ?>>Singapore Dollar (Singapore)
<option value="SKK"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SKK'): ?> selected="selected"<?php endif; ?>>Slovak Koruna (Slovakia)
<option value="SBD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SBD'): ?> selected="selected"<?php endif; ?>>Solomon Islands Dollar (Solomon Islands)
<option value="KGS"<?php if ($this->_tpl_vars['module_data']['param03'] == 'KGS'): ?> selected="selected"<?php endif; ?>>Som (Kyrgyzstan)
<option value="SOS"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SOS'): ?> selected="selected"<?php endif; ?>>Somali Shilling (Somalia)
<option value="LKR"<?php if ($this->_tpl_vars['module_data']['param03'] == 'LKR'): ?> selected="selected"<?php endif; ?>>Sri Lanka Rupee (Sri Lanka)
<option value="SHP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SHP'): ?> selected="selected"<?php endif; ?>>St Helena Pound (St Helena)
<option value="ECS"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ECS'): ?> selected="selected"<?php endif; ?>>Sucre (Ecuador)
<option value="SDD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SDD'): ?> selected="selected"<?php endif; ?>>Sudanese Dinar (Sudan)
<option value="SRG"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SRG'): ?> selected="selected"<?php endif; ?>>Surinam Guilder (Suriname)
<option value="SEK"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SEK'): ?> selected="selected"<?php endif; ?>>Swedish Krona (Sweden)
<option value="CHF"<?php if ($this->_tpl_vars['module_data']['param03'] == 'CHF'): ?> selected="selected"<?php endif; ?>>Swiss Franc (Switzerland)
<option value="SYP"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SYP'): ?> selected="selected"<?php endif; ?>>Syrian Pound (Syrian Arab Republic)
<option value="TJS"<?php if ($this->_tpl_vars['module_data']['param03'] == 'TJR'): ?> selected="selected"<?php endif; ?>>Tajikistani somoni (Tajikistan)
<option value="BDT"<?php if ($this->_tpl_vars['module_data']['param03'] == 'BDT'): ?> selected="selected"<?php endif; ?>>Taka (Bangladesh)
<option value="WST"<?php if ($this->_tpl_vars['module_data']['param03'] == 'WST'): ?> selected="selected"<?php endif; ?>>Tala (Samoa)
<option value="TZS"<?php if ($this->_tpl_vars['module_data']['param03'] == 'TZS'): ?> selected="selected"<?php endif; ?>>Tanzanian Shilling (United Republic Of Tanzania)
<option value="KZT"<?php if ($this->_tpl_vars['module_data']['param03'] == 'KZT'): ?> selected="selected"<?php endif; ?>>Tenge (Kazakhstan)
<option value="TPE"<?php if ($this->_tpl_vars['module_data']['param03'] == 'TPE'): ?> selected="selected"<?php endif; ?>>Timor Escudo (East Timor)
<option value="SIT"<?php if ($this->_tpl_vars['module_data']['param03'] == 'SIT'): ?> selected="selected"<?php endif; ?>>Tolar (Slovenia)
<option value="TTD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'TTD'): ?> selected="selected"<?php endif; ?>>Trinidad and Tobago Dollar (Trinidad And Tobago)
<option value="MNT"<?php if ($this->_tpl_vars['module_data']['param03'] == 'MNT'): ?> selected="selected"<?php endif; ?>>Tugrik (Mongolia)
<option value="TND"<?php if ($this->_tpl_vars['module_data']['param03'] == 'TND'): ?> selected="selected"<?php endif; ?>>Tunisian Dinar (Tunisia)
<option value="TRL"<?php if ($this->_tpl_vars['module_data']['param03'] == 'TRL'): ?> selected="selected"<?php endif; ?>>Turkish Lira (Turkey)
<option value="UGX"<?php if ($this->_tpl_vars['module_data']['param03'] == 'UGX'): ?> selected="selected"<?php endif; ?>>Uganda Shilling (Uganda)
<option value="ECV"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ECV'): ?> selected="selected"<?php endif; ?>>Unidad de Valor Constante (Ecuador)
<option value="CLF"<?php if ($this->_tpl_vars['module_data']['param03'] == 'CLF'): ?> selected="selected"<?php endif; ?>>Unidades de fomento (Chile)
<option value="USN"<?php if ($this->_tpl_vars['module_data']['param03'] == 'USN'): ?> selected="selected"<?php endif; ?>>US Dollar (Next day) (United States)
<option value="USS"<?php if ($this->_tpl_vars['module_data']['param03'] == 'USS'): ?> selected="selected"<?php endif; ?>>US Dollar (Same day) (United States)
<option value="USD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'USD'): ?> selected="selected"<?php endif; ?>>US Dollar (United States)
<option value="UZS"<?php if ($this->_tpl_vars['module_data']['param03'] == 'UZS'): ?> selected="selected"<?php endif; ?>>Uzbekistan Sum (Uzbekistan)
<option value="VUV"<?php if ($this->_tpl_vars['module_data']['param03'] == 'VUV'): ?> selected="selected"<?php endif; ?>>Vatu (Vanuatu)
<option value="KRW"<?php if ($this->_tpl_vars['module_data']['param03'] == 'KRW'): ?> selected="selected"<?php endif; ?>>Won (Republic Of Korea)
<option value="YER"<?php if ($this->_tpl_vars['module_data']['param03'] == 'YER'): ?> selected="selected"<?php endif; ?>>Yemeni Rial (Yemen)
<option value="JPY"<?php if ($this->_tpl_vars['module_data']['param03'] == 'JPY'): ?> selected="selected"<?php endif; ?>>Yen (Japan)
<option value="CNY"<?php if ($this->_tpl_vars['module_data']['param03'] == 'CNY'): ?> selected="selected"<?php endif; ?>>Yuan Renminbi (China)
<option value="ZWD"<?php if ($this->_tpl_vars['module_data']['param03'] == 'ZWD'): ?> selected="selected"<?php endif; ?>>Zimbabwe Dollar (Zimbabwe)
<option value="PLN"<?php if ($this->_tpl_vars['module_data']['param03'] == 'PLN'): ?> selected="selected"<?php endif; ?>>Zloty (Poland)
</select>
</td>
</tr>

<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_cc_testlive_mode']; ?>
:</td>
<td>
<select name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[testmode]">
<option value="Y"<?php if ($this->_tpl_vars['module_data']['testmode'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_cc_testlive_test']; ?>
</option>
<option value="N"<?php if ($this->_tpl_vars['module_data']['testmode'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_cc_testlive_live']; ?>
</option>
</select>
<br /><font class="SmallText"><?php echo $this->_tpl_vars['lng']['lbl_paypal_test_mode_note']; ?>
</font>
</td>
</tr>

<!--
<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_paypal_transaction_type']; ?>
:</td>
<td>
<select name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[param05]">
<option value="S"<?php if ($this->_tpl_vars['module_data']['param05'] == 'S'): ?> selected="selected"<?php endif; ?>>Sale</option>
<option value="A"<?php if ($this->_tpl_vars['module_data']['param05'] == 'A'): ?> selected="selected"<?php endif; ?>>Authorization and Capture</option>
</select>
</td>
</tr>
-->

<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_cc_order_prefix']; ?>
:</td>
<td><input type="text" name="<?php echo $this->_tpl_vars['conf_prefix']; ?>
[param06]" size="36" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['module_data']['param06'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>

</table>