<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * smarty_modifier_telelphone_e164
 */
function smarty_modifier_telephone_e164( $pTelephoneNumber, $pCountryCodeIso2='US' ) {
	if( $ret = $pTelephoneNumber ) {

		global $gPhoneNumberUtil;
		if( empty( $gPhoneNumberUtil ) ) {
			spl_autoload_register(function ($class) {
				// replace namespace separators with directory separators in the relative 
				// class name, append with .php
				$class_path = str_replace('\\', '/', $class);
				
				$file =  EXTERNAL_LIBS_PATH . $class_path . '.php';

				// if the file exists, require it
				if (file_exists($file)) {
					require_once( $file );
				}
			});
			$gPhoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
		}
		if( is_object( $gPhoneNumberUtil ) ) {
			try {
				if( $parsedNumber = $gPhoneNumberUtil->parse( $pTelephoneNumber, $pCountryCodeIso2 ) ) {
					$ret = $gPhoneNumberUtil->format( $parsedNumber, \libphonenumber\PhoneNumberFormat::E164 );
				}
			} catch( Exception $e ) {
				bit_error_log( 'telephone_e164 failed: '.$pCountryCodeIso2.' '.$pTelephoneNumber );
			}
		}
	}
	return $ret;
}


//$phonenumber = preg_replace(['/\D/', '/^0(?!0)/', '/^\+?0*((3[578]?|42?)\d)0*/'], 
//                            ['',     '+49',       '+$1'], $pTelephoneNumber );

/*
function StripPhoneNumber($code, &$phoneNumber)
{
	$code = strtoupper($code);
	$code = preg_replace('/[^A-Z]/','',$code);
	$countryCode = "";
	$countries = array( 
		'code' => 'AF', 'name' => 'Afghanistan', 'd_code' => '+93',
		'code' => 'AL', 'name' => 'Albania', 'd_code' => '+355',
		'code' => 'DZ', 'name' => 'Algeria', 'd_code' => '+213',
		'code' => 'AS', 'name' => 'American Samoa', 'd_code' => '+1',
		'code' => 'AD', 'name' => 'Andorra', 'd_code' => '+376',
		'code' => 'AO', 'name' => 'Angola', 'd_code' => '+244',
		'code' => 'AI', 'name' => 'Anguilla', 'd_code' => '+1',
		'code' => 'AG', 'name' => 'Antigua', 'd_code' => '+1',
		'code' => 'AR', 'name' => 'Argentina', 'd_code' => '+54',
		'code' => 'AM', 'name' => 'Armenia', 'd_code' => '+374',
		'code' => 'AW', 'name' => 'Aruba', 'd_code' => '+297',
		'code' => 'AU', 'name' => 'Australia', 'd_code' => '+61',
		'code' => 'AT', 'name' => 'Austria', 'd_code' => '+43',
		'code' => 'AZ', 'name' => 'Azerbaijan', 'd_code' => '+994',
		'code' => 'BH', 'name' => 'Bahrain', 'd_code' => '+973',
		'code' => 'BD', 'name' => 'Bangladesh', 'd_code' => '+880',
		'code' => 'BB', 'name' => 'Barbados', 'd_code' => '+1',
		'code' => 'BY', 'name' => 'Belarus', 'd_code' => '+375',
		'code' => 'BE', 'name' => 'Belgium', 'd_code' => '+32',
		'code' => 'BZ', 'name' => 'Belize', 'd_code' => '+501',
		'code' => 'BJ', 'name' => 'Benin', 'd_code' => '+229',
		'code' => 'BM', 'name' => 'Bermuda', 'd_code' => '+1',
		'code' => 'BT', 'name' => 'Bhutan', 'd_code' => '+975',
		'code' => 'BO', 'name' => 'Bolivia', 'd_code' => '+591',
		'code' => 'BA', 'name' => 'Bosnia and Herzegovina', 'd_code' => '+387',
		'code' => 'BW', 'name' => 'Botswana', 'd_code' => '+267',
		'code' => 'BR', 'name' => 'Brazil', 'd_code' => '+55',
		'code' => 'IO', 'name' => 'British Indian Ocean Territory', 'd_code' => '+246',
		'code' => 'VG', 'name' => 'British Virgin Islands', 'd_code' => '+1',
		'code' => 'BN', 'name' => 'Brunei', 'd_code' => '+673',
		'code' => 'BG', 'name' => 'Bulgaria', 'd_code' => '+359',
		'code' => 'BF', 'name' => 'Burkina Faso', 'd_code' => '+226',
		'code' => 'MM', 'name' => 'Burma Myanmar', 'd_code' => '+95',
		'code' => 'BI', 'name' => 'Burundi', 'd_code' => '+257',
		'code' => 'KH', 'name' => 'Cambodia', 'd_code' => '+855',
		'code' => 'CM', 'name' => 'Cameroon', 'd_code' => '+237',
		'code' => 'CA', 'name' => 'Canada', 'd_code' => '+1',
		'code' => 'CV', 'name' => 'Cape Verde', 'd_code' => '+238',
		'code' => 'KY', 'name' => 'Cayman Islands', 'd_code' => '+1',
		'code' => 'CF', 'name' => 'Central African Republic', 'd_code' => '+236',
		'code' => 'TD', 'name' => 'Chad', 'd_code' => '+235',
		'code' => 'CL', 'name' => 'Chile', 'd_code' => '+56',
		'code' => 'CN', 'name' => 'China', 'd_code' => '+86',
		'code' => 'CO', 'name' => 'Colombia', 'd_code' => '+57',
		'code' => 'KM', 'name' => 'Comoros', 'd_code' => '+269',
		'code' => 'CK', 'name' => 'Cook Islands', 'd_code' => '+682',
		'code' => 'CR', 'name' => 'Costa Rica', 'd_code' => '+506',
		'code' => 'CI', 'name' => 'Côte d'Ivoire', 'd_code' => '+225',
		'code' => 'HR', 'name' => 'Croatia', 'd_code' => '+385',
		'code' => 'CU', 'name' => 'Cuba', 'd_code' => '+53',
		'code' => 'CY', 'name' => 'Cyprus', 'd_code' => '+357',
		'code' => 'CZ', 'name' => 'Czech Republic', 'd_code' => '+420',
		'code' => 'CD', 'name' => 'Democratic Republic of Congo', 'd_code' => '+243',
		'code' => 'DK', 'name' => 'Denmark', 'd_code' => '+45',
		'code' => 'DJ', 'name' => 'Djibouti', 'd_code' => '+253',
		'code' => 'DM', 'name' => 'Dominica', 'd_code' => '+1',
		'code' => 'DO', 'name' => 'Dominican Republic', 'd_code' => '+1',
		'code' => 'EC', 'name' => 'Ecuador', 'd_code' => '+593',
		'code' => 'EG', 'name' => 'Egypt', 'd_code' => '+20',
		'code' => 'SV', 'name' => 'El Salvador', 'd_code' => '+503',
		'code' => 'GQ', 'name' => 'Equatorial Guinea', 'd_code' => '+240',
		'code' => 'ER', 'name' => 'Eritrea', 'd_code' => '+291',
		'code' => 'EE', 'name' => 'Estonia', 'd_code' => '+372',
		'code' => 'ET', 'name' => 'Ethiopia', 'd_code' => '+251',
		'code' => 'FK', 'name' => 'Falkland Islands', 'd_code' => '+500',
		'code' => 'FO', 'name' => 'Faroe Islands', 'd_code' => '+298',
		'code' => 'FM', 'name' => 'Federated States of Micronesia', 'd_code' => '+691',
		'code' => 'FJ', 'name' => 'Fiji', 'd_code' => '+679',
		'code' => 'FI', 'name' => 'Finland', 'd_code' => '+358',
		'code' => 'FR', 'name' => 'France', 'd_code' => '+33',
		'code' => 'GF', 'name' => 'French Guiana', 'd_code' => '+594',
		'code' => 'PF', 'name' => 'French Polynesia', 'd_code' => '+689',
		'code' => 'GA', 'name' => 'Gabon', 'd_code' => '+241',
		'code' => 'GE', 'name' => 'Georgia', 'd_code' => '+995',
		'code' => 'DE', 'name' => 'Germany', 'd_code' => '+49',
		'code' => 'GH', 'name' => 'Ghana', 'd_code' => '+233',
		'code' => 'GI', 'name' => 'Gibraltar', 'd_code' => '+350',
		'code' => 'GR', 'name' => 'Greece', 'd_code' => '+30',
		'code' => 'GL', 'name' => 'Greenland', 'd_code' => '+299',
		'code' => 'GD', 'name' => 'Grenada', 'd_code' => '+1',
		'code' => 'GP', 'name' => 'Guadeloupe', 'd_code' => '+590',
		'code' => 'GU', 'name' => 'Guam', 'd_code' => '+1',
		'code' => 'GT', 'name' => 'Guatemala', 'd_code' => '+502',
		'code' => 'GN', 'name' => 'Guinea', 'd_code' => '+224',
		'code' => 'GW', 'name' => 'Guinea-Bissau', 'd_code' => '+245',
		'code' => 'GY', 'name' => 'Guyana', 'd_code' => '+592',
		'code' => 'HT', 'name' => 'Haiti', 'd_code' => '+509',
		'code' => 'HN', 'name' => 'Honduras', 'd_code' => '+504',
		'code' => 'HK', 'name' => 'Hong Kong', 'd_code' => '+852',
		'code' => 'HU', 'name' => 'Hungary', 'd_code' => '+36',
		'code' => 'IS', 'name' => 'Iceland', 'd_code' => '+354',
		'code' => 'IN', 'name' => 'India', 'd_code' => '+91',
		'code' => 'ID', 'name' => 'Indonesia', 'd_code' => '+62',
		'code' => 'IR', 'name' => 'Iran', 'd_code' => '+98',
		'code' => 'IQ', 'name' => 'Iraq', 'd_code' => '+964',
		'code' => 'IE', 'name' => 'Ireland', 'd_code' => '+353',
		'code' => 'IL', 'name' => 'Israel', 'd_code' => '+972',
		'code' => 'IT', 'name' => 'Italy', 'd_code' => '+39',
		'code' => 'JM', 'name' => 'Jamaica', 'd_code' => '+1',
		'code' => 'JP', 'name' => 'Japan', 'd_code' => '+81',
		'code' => 'JO', 'name' => 'Jordan', 'd_code' => '+962',
		'code' => 'KZ', 'name' => 'Kazakhstan', 'd_code' => '+7',
		'code' => 'KE', 'name' => 'Kenya', 'd_code' => '+254',
		'code' => 'KI', 'name' => 'Kiribati', 'd_code' => '+686',
		'code' => 'XK', 'name' => 'Kosovo', 'd_code' => '+381',
		'code' => 'KW', 'name' => 'Kuwait', 'd_code' => '+965',
		'code' => 'KG', 'name' => 'Kyrgyzstan', 'd_code' => '+996',
		'code' => 'LA', 'name' => 'Laos', 'd_code' => '+856',
		'code' => 'LV', 'name' => 'Latvia', 'd_code' => '+371',
		'code' => 'LB', 'name' => 'Lebanon', 'd_code' => '+961',
		'code' => 'LS', 'name' => 'Lesotho', 'd_code' => '+266',
		'code' => 'LR', 'name' => 'Liberia', 'd_code' => '+231',
		'code' => 'LY', 'name' => 'Libya', 'd_code' => '+218',
		'code' => 'LI', 'name' => 'Liechtenstein', 'd_code' => '+423',
		'code' => 'LT', 'name' => 'Lithuania', 'd_code' => '+370',
		'code' => 'LU', 'name' => 'Luxembourg', 'd_code' => '+352',
		'code' => 'MO', 'name' => 'Macau', 'd_code' => '+853',
		'code' => 'MK', 'name' => 'Macedonia', 'd_code' => '+389',
		'code' => 'MG', 'name' => 'Madagascar', 'd_code' => '+261',
		'code' => 'MW', 'name' => 'Malawi', 'd_code' => '+265',
		'code' => 'MY', 'name' => 'Malaysia', 'd_code' => '+60',
		'code' => 'MV', 'name' => 'Maldives', 'd_code' => '+960',
		'code' => 'ML', 'name' => 'Mali', 'd_code' => '+223',
		'code' => 'MT', 'name' => 'Malta', 'd_code' => '+356',
		'code' => 'MH', 'name' => 'Marshall Islands', 'd_code' => '+692',
		'code' => 'MQ', 'name' => 'Martinique', 'd_code' => '+596',
		'code' => 'MR', 'name' => 'Mauritania', 'd_code' => '+222',
		'code' => 'MU', 'name' => 'Mauritius', 'd_code' => '+230',
		'code' => 'YT', 'name' => 'Mayotte', 'd_code' => '+262',
		'code' => 'MX', 'name' => 'Mexico', 'd_code' => '+52',
		'code' => 'MD', 'name' => 'Moldova', 'd_code' => '+373',
		'code' => 'MC', 'name' => 'Monaco', 'd_code' => '+377',
		'code' => 'MN', 'name' => 'Mongolia', 'd_code' => '+976',
		'code' => 'ME', 'name' => 'Montenegro', 'd_code' => '+382',
		'code' => 'MS', 'name' => 'Montserrat', 'd_code' => '+1',
		'code' => 'MA', 'name' => 'Morocco', 'd_code' => '+212',
		'code' => 'MZ', 'name' => 'Mozambique', 'd_code' => '+258',
		'code' => 'NA', 'name' => 'Namibia', 'd_code' => '+264',
		'code' => 'NR', 'name' => 'Nauru', 'd_code' => '+674',
		'code' => 'NP', 'name' => 'Nepal', 'd_code' => '+977',
		'code' => 'NL', 'name' => 'Netherlands', 'd_code' => '+31',
		'code' => 'AN', 'name' => 'Netherlands Antilles', 'd_code' => '+599',
		'code' => 'NC', 'name' => 'New Caledonia', 'd_code' => '+687',
		'code' => 'NZ', 'name' => 'New Zealand', 'd_code' => '+64',
		'code' => 'NI', 'name' => 'Nicaragua', 'd_code' => '+505',
		'code' => 'NE', 'name' => 'Niger', 'd_code' => '+227',
		'code' => 'NG', 'name' => 'Nigeria', 'd_code' => '+234',
		'code' => 'NU', 'name' => 'Niue', 'd_code' => '+683',
		'code' => 'NF', 'name' => 'Norfolk Island', 'd_code' => '+672',
		'code' => 'KP', 'name' => 'North Korea', 'd_code' => '+850',
		'code' => 'MP', 'name' => 'Northern Mariana Islands', 'd_code' => '+1',
		'code' => 'NO', 'name' => 'Norway', 'd_code' => '+47',
		'code' => 'OM', 'name' => 'Oman', 'd_code' => '+968',
		'code' => 'PK', 'name' => 'Pakistan', 'd_code' => '+92',
		'code' => 'PW', 'name' => 'Palau', 'd_code' => '+680',
		'code' => 'PS', 'name' => 'Palestine', 'd_code' => '+970',
		'code' => 'PA', 'name' => 'Panama', 'd_code' => '+507',
		'code' => 'PG', 'name' => 'Papua New Guinea', 'd_code' => '+675',
		'code' => 'PY', 'name' => 'Paraguay', 'd_code' => '+595',
		'code' => 'PE', 'name' => 'Peru', 'd_code' => '+51',
		'code' => 'PH', 'name' => 'Philippines', 'd_code' => '+63',
		'code' => 'PL', 'name' => 'Poland', 'd_code' => '+48',
		'code' => 'PT', 'name' => 'Portugal', 'd_code' => '+351',
		'code' => 'PR', 'name' => 'Puerto Rico', 'd_code' => '+1',
		'code' => 'QA', 'name' => 'Qatar', 'd_code' => '+974',
		'code' => 'CG', 'name' => 'Republic of the Congo', 'd_code' => '+242',
		'code' => 'RE', 'name' => 'Réunion', 'd_code' => '+262',
		'code' => 'RO', 'name' => 'Romania', 'd_code' => '+40',
		'code' => 'RU', 'name' => 'Russia', 'd_code' => '+7',
		'code' => 'RW', 'name' => 'Rwanda', 'd_code' => '+250',
		'code' => 'BL', 'name' => 'Saint Barthélemy', 'd_code' => '+590',
		'code' => 'SH', 'name' => 'Saint Helena', 'd_code' => '+290',
		'code' => 'KN', 'name' => 'Saint Kitts and Nevis', 'd_code' => '+1',
		'code' => 'MF', 'name' => 'Saint Martin', 'd_code' => '+590',
		'code' => 'PM', 'name' => 'Saint Pierre and Miquelon', 'd_code' => '+508',
		'code' => 'VC', 'name' => 'Saint Vincent and the Grenadines', 'd_code' => '+1',
		'code' => 'WS', 'name' => 'Samoa', 'd_code' => '+685',
		'code' => 'SM', 'name' => 'San Marino', 'd_code' => '+378',
		'code' => 'ST', 'name' => 'São Tomé and Príncipe', 'd_code' => '+239',
		'code' => 'SA', 'name' => 'Saudi Arabia', 'd_code' => '+966',
		'code' => 'SN', 'name' => 'Senegal', 'd_code' => '+221',
		'code' => 'RS', 'name' => 'Serbia', 'd_code' => '+381',
		'code' => 'SC', 'name' => 'Seychelles', 'd_code' => '+248',
		'code' => 'SL', 'name' => 'Sierra Leone', 'd_code' => '+232',
		'code' => 'SG', 'name' => 'Singapore', 'd_code' => '+65',
		'code' => 'SK', 'name' => 'Slovakia', 'd_code' => '+421',
		'code' => 'SI', 'name' => 'Slovenia', 'd_code' => '+386',
		'code' => 'SB', 'name' => 'Solomon Islands', 'd_code' => '+677',
		'code' => 'SO', 'name' => 'Somalia', 'd_code' => '+252',
		'code' => 'ZA', 'name' => 'South Africa', 'd_code' => '+27',
		'code' => 'KR', 'name' => 'South Korea', 'd_code' => '+82',
		'code' => 'ES', 'name' => 'Spain', 'd_code' => '+34',
		'code' => 'LK', 'name' => 'Sri Lanka', 'd_code' => '+94',
		'code' => 'LC', 'name' => 'St. Lucia', 'd_code' => '+1',
		'code' => 'SD', 'name' => 'Sudan', 'd_code' => '+249',
		'code' => 'SR', 'name' => 'Suriname', 'd_code' => '+597',
		'code' => 'SZ', 'name' => 'Swaziland', 'd_code' => '+268',
		'code' => 'SE', 'name' => 'Sweden', 'd_code' => '+46',
		'code' => 'CH', 'name' => 'Switzerland', 'd_code' => '+41',
		'code' => 'SY', 'name' => 'Syria', 'd_code' => '+963',
		'code' => 'TW', 'name' => 'Taiwan', 'd_code' => '+886',
		'code' => 'TJ', 'name' => 'Tajikistan', 'd_code' => '+992',
		'code' => 'TZ', 'name' => 'Tanzania', 'd_code' => '+255',
		'code' => 'TH', 'name' => 'Thailand', 'd_code' => '+66',
		'code' => 'BS', 'name' => 'The Bahamas', 'd_code' => '+1',
		'code' => 'GM', 'name' => 'The Gambia', 'd_code' => '+220',
		'code' => 'TL', 'name' => 'Timor-Leste', 'd_code' => '+670',
		'code' => 'TG', 'name' => 'Togo', 'd_code' => '+228',
		'code' => 'TK', 'name' => 'Tokelau', 'd_code' => '+690',
		'code' => 'TO', 'name' => 'Tonga', 'd_code' => '+676',
		'code' => 'TT', 'name' => 'Trinidad and Tobago', 'd_code' => '+1',
		'code' => 'TN', 'name' => 'Tunisia', 'd_code' => '+216',
		'code' => 'TR', 'name' => 'Turkey', 'd_code' => '+90',
		'code' => 'TM', 'name' => 'Turkmenistan', 'd_code' => '+993',
		'code' => 'TC', 'name' => 'Turks and Caicos Islands', 'd_code' => '+1',
		'code' => 'TV', 'name' => 'Tuvalu', 'd_code' => '+688',
		'code' => 'UG', 'name' => 'Uganda', 'd_code' => '+256',
		'code' => 'UA', 'name' => 'Ukraine', 'd_code' => '+380',
		'code' => 'AE', 'name' => 'United Arab Emirates', 'd_code' => '+971',
		'code' => 'GB', 'name' => 'United Kingdom', 'd_code' => '+44',
		'code' => 'US', 'name' => 'United States', 'd_code' => '+1',
		'code' => 'UY', 'name' => 'Uruguay', 'd_code' => '+598',
		'code' => 'VI', 'name' => 'US Virgin Islands', 'd_code' => '+1',
		'code' => 'UZ', 'name' => 'Uzbekistan', 'd_code' => '+998',
		'code' => 'VU', 'name' => 'Vanuatu', 'd_code' => '+678',
		'code' => 'VA', 'name' => 'Vatican City', 'd_code' => '+39',
		'code' => 'VE', 'name' => 'Venezuela', 'd_code' => '+58',
		'code' => 'VN', 'name' => 'Vietnam', 'd_code' => '+84',
		'code' => 'WF', 'name' => 'Wallis and Futuna', 'd_code' => '+681',
		'code' => 'YE', 'name' => 'Yemen', 'd_code' => '+967',
		'code' => 'ZM', 'name' => 'Zambia', 'd_code' => '+260',
		'code' => 'ZW', 'name' => 'Zimbabwe', 'd_code' => '+263',
	);

	$code = strtoupper($code,
	$UsephoneNumber = " "."+";
	$UsephoneNumber = $UsephoneNumber.(preg_replace('/[^0-9]/','',$phoneNumber));

	for($index=0; $index<count($countries); $index++)
	{
		$array1 = $countries[$index];
		if($array1['code'] == $code)
		{
			$dCode = $array1['d_code'];
			$explode_ = explode($dCode, $UsephoneNumber, 2);
			if(count($explode_) == 2)
			{
				$phoneNumber = $explode_[1];
				$countryCode = $dCode;
			}
			break;
		}
	}

	return $countryCode;
}


function formatPhoneNumber($phoneNumber, $code="") {

	$countryCode = StripPhoneNumber($code, $phoneNumber);
	$phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);

	if(strlen($phoneNumber) > 10) {
		$countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
		$areaCode = substr($phoneNumber, -10, 3);
		$nextThree = substr($phoneNumber, -7, 3);
		$lastFour = substr($phoneNumber, -4, 4);

		$phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
	}
	else if(strlen($phoneNumber) == 10) {
		$areaCode = substr($phoneNumber, 0, 3);
		$nextThree = substr($phoneNumber, 3, 3);
		$lastFour = substr($phoneNumber, 6, 4);

		$phoneNumber = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
	}
	else if(strlen($phoneNumber) == 7) {
		$nextThree = substr($phoneNumber, 0, 3);
		$lastFour = substr($phoneNumber, 3, 4);

		$phoneNumber = $nextThree.'-'.$lastFour;
	}
	else
	{
		$sub = "";
		while(strlen($phoneNumber) >= 3)
		{
			if( strlen($phoneNumber) >= 3 && (strlen($phoneNumber) % 2 != 0) )
			{
				$sub = $sub.substr($phoneNumber, 0, 3)." ";
				$phoneNumber = substr($phoneNumber, 3);
			}
			else
			if( (strlen($phoneNumber) % 2 == 0) && strlen($phoneNumber) >= 2 )
			{
			   $sub = $sub.substr($phoneNumber, 0, 2)." ";
			   $phoneNumber = substr($phoneNumber, 2);
			}
		}

		if( strlen($phoneNumber) > 0)
		{
			$sub = $sub.$phoneNumber." ";
		}

		$phoneNumber = trim($sub," ");

	}

	return $countryCode." ".$phoneNumber;
}
*/
