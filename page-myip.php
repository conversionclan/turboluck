<?php // Template name: Tool - My IP ?>

<?php get_header(); ?>

<div class="content content-page" id="myip">
	<main class="site-main">
	
		<div class="myip-header review-header">
			<div class="myip-header-inner">
				<div class="myip-header-part myip-header-left myip-header-ip">
					<div class="myip-title">
						<?php _e( 'Your IP address is', 'bento' ); ?>:
					</div>
					<div class="myip-ip-group">
						<div class="myip-ip">
							
							<?php 
							// From http://itman.in/en/how-to-get-client-ip-address-in-php/
							function tl_get_ip_address() { 
								
								// check for shared internet/ISP IP
								if (!empty($_SERVER['HTTP_CLIENT_IP']) && tl_validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
									return $_SERVER['HTTP_CLIENT_IP'];
								}

								// check for IPs passing through proxies
								if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
									// check if multiple ips exist in var
									if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
										$iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
										foreach ($iplist as $ip) {
											if (tl_validate_ip($ip))
												return $ip;
										}
									} else {
										if (tl_validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
											return $_SERVER['HTTP_X_FORWARDED_FOR'];
									}
								}
								
								if (!empty($_SERVER['HTTP_X_FORWARDED']) && tl_validate_ip($_SERVER['HTTP_X_FORWARDED']))
									return $_SERVER['HTTP_X_FORWARDED'];
								if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && tl_validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
									return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
								if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && tl_validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
									return $_SERVER['HTTP_FORWARDED_FOR'];
								if (!empty($_SERVER['HTTP_FORWARDED']) && tl_validate_ip($_SERVER['HTTP_FORWARDED']))
									return $_SERVER['HTTP_FORWARDED'];

								// return unreliable ip since all else failed
								return $_SERVER['REMOTE_ADDR'];
								
							}

							// Ensures an ip address is both a valid IP and does not fall within a private network range.
							function tl_validate_ip($ip) {
								
								if (strtolower($ip) === 'unknown')
									return false;

								// generate ipv4 network address
								$ip = ip2long($ip);

								// if the ip is set and not equivalent to 255.255.255.255
								if ($ip !== false && $ip !== -1) {
									// Make sure to get unsigned long representation of ip due to discrepancies between 32 and 64 bit OSes and signed numbers (ints default to signed in PHP)
									$ip = sprintf('%u', $ip);
									// do private network range checking
									if ($ip >= 0 && $ip <= 50331647) return false;
									if ($ip >= 167772160 && $ip <= 184549375) return false;
									if ($ip >= 2130706432 && $ip <= 2147483647) return false;
									if ($ip >= 2851995648 && $ip <= 2852061183) return false;
									if ($ip >= 2886729728 && $ip <= 2887778303) return false;
									if ($ip >= 3221225984 && $ip <= 3221226239) return false;
									if ($ip >= 3232235520 && $ip <= 3232301055) return false;
									if ($ip >= 4294967040) return false;
								}
								
								return true;
								
							}
							
							$myip = tl_get_ip_address();
							echo $myip;
							
							?>
							
						</div>
						<div class="my-ip-copy">
							<div class="my-ip-copy-icon">
								<div class="icon-copy-square icon-copy-square-1">
								</div>
								<div class="icon-copy-square icon-copy-square-2">
								</div>
							</div>
							<div class="my-ip-copy-text">
								<?php _e( ' click to copy', 'bento' ); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="myip-header-part myip-header-right myip-details">
					<div class="myip-detail myip-detail-ipv6">
						<div class="myip-detail-name">
							<?php _e( 'IPv6', 'bento' ); ?>:
						</div>
						<div class="myip-detail-value">
							<?php 
							if ( strpos( $myip, ":" ) === false ) { 
								_e( 'Not detected', 'bento' );
							} else { 
								_e( 'Yes', 'bento' ); 
							}
							?>
						</div>
					</div>
					<div class="myip-detail-isp">
						<div class="myip-detail-name">
							<?php _e( 'ISP', 'bento' ); ?>:
						</div>
						<div class="myip-detail-value">
							<?php 
							$geo_details = json_decode( file_get_contents( "http://ipinfo.io/{$myip}" ) );
							$ip_org = $geo_details->org;
							$ip_org_repl = preg_replace( '/AS[^\s]*\s/', '', $ip_org );
							echo $ip_org_repl;
							?>
						</div>
					</div>
					<div class="myip-detail-country">
						<div class="myip-detail-name">
							<?php _e( 'Country', 'bento' ); ?>:
						</div>
						<div class="myip-detail-value">
							<?php 
							$geo_details = json_decode( file_get_contents( "http://ipinfo.io/{$myip}" ) );
							$ip_country = $geo_details->country; 
							
							function tl_code_to_country( $code ){
								$code = strtoupper($code);
								$countryList = array(
									'AF' => 'Afghanistan',
									'AX' => 'Aland Islands',
									'AL' => 'Albania',
									'DZ' => 'Algeria',
									'AS' => 'American Samoa',
									'AD' => 'Andorra',
									'AO' => 'Angola',
									'AI' => 'Anguilla',
									'AQ' => 'Antarctica',
									'AG' => 'Antigua and Barbuda',
									'AR' => 'Argentina',
									'AM' => 'Armenia',
									'AW' => 'Aruba',
									'AU' => 'Australia',
									'AT' => 'Austria',
									'AZ' => 'Azerbaijan',
									'BS' => 'Bahamas the',
									'BH' => 'Bahrain',
									'BD' => 'Bangladesh',
									'BB' => 'Barbados',
									'BY' => 'Belarus',
									'BE' => 'Belgium',
									'BZ' => 'Belize',
									'BJ' => 'Benin',
									'BM' => 'Bermuda',
									'BT' => 'Bhutan',
									'BO' => 'Bolivia',
									'BA' => 'Bosnia and Herzegovina',
									'BW' => 'Botswana',
									'BV' => 'Bouvet Island (Bouvetoya)',
									'BR' => 'Brazil',
									'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
									'VG' => 'British Virgin Islands',
									'BN' => 'Brunei Darussalam',
									'BG' => 'Bulgaria',
									'BF' => 'Burkina Faso',
									'BI' => 'Burundi',
									'KH' => 'Cambodia',
									'CM' => 'Cameroon',
									'CA' => 'Canada',
									'CV' => 'Cape Verde',
									'KY' => 'Cayman Islands',
									'CF' => 'Central African Republic',
									'TD' => 'Chad',
									'CL' => 'Chile',
									'CN' => 'China',
									'CX' => 'Christmas Island',
									'CC' => 'Cocos (Keeling) Islands',
									'CO' => 'Colombia',
									'KM' => 'Comoros the',
									'CD' => 'Congo',
									'CG' => 'Congo the',
									'CK' => 'Cook Islands',
									'CR' => 'Costa Rica',
									'CI' => 'Cote d\'Ivoire',
									'HR' => 'Croatia',
									'CU' => 'Cuba',
									'CY' => 'Cyprus',
									'CZ' => 'Czech Republic',
									'DK' => 'Denmark',
									'DJ' => 'Djibouti',
									'DM' => 'Dominica',
									'DO' => 'Dominican Republic',
									'EC' => 'Ecuador',
									'EG' => 'Egypt',
									'SV' => 'El Salvador',
									'GQ' => 'Equatorial Guinea',
									'ER' => 'Eritrea',
									'EE' => 'Estonia',
									'ET' => 'Ethiopia',
									'FO' => 'Faroe Islands',
									'FK' => 'Falkland Islands (Malvinas)',
									'FJ' => 'Fiji the Fiji Islands',
									'FI' => 'Finland',
									'FR' => 'France, French Republic',
									'GF' => 'French Guiana',
									'PF' => 'French Polynesia',
									'TF' => 'French Southern Territories',
									'GA' => 'Gabon',
									'GM' => 'Gambia the',
									'GE' => 'Georgia',
									'DE' => 'Germany',
									'GH' => 'Ghana',
									'GI' => 'Gibraltar',
									'GR' => 'Greece',
									'GL' => 'Greenland',
									'GD' => 'Grenada',
									'GP' => 'Guadeloupe',
									'GU' => 'Guam',
									'GT' => 'Guatemala',
									'GG' => 'Guernsey',
									'GN' => 'Guinea',
									'GW' => 'Guinea-Bissau',
									'GY' => 'Guyana',
									'HT' => 'Haiti',
									'HM' => 'Heard Island and McDonald Islands',
									'VA' => 'Holy See (Vatican City State)',
									'HN' => 'Honduras',
									'HK' => 'Hong Kong',
									'HU' => 'Hungary',
									'IS' => 'Iceland',
									'IN' => 'India',
									'ID' => 'Indonesia',
									'IR' => 'Iran',
									'IQ' => 'Iraq',
									'IE' => 'Ireland',
									'IM' => 'Isle of Man',
									'IL' => 'Israel',
									'IT' => 'Italy',
									'JM' => 'Jamaica',
									'JP' => 'Japan',
									'JE' => 'Jersey',
									'JO' => 'Jordan',
									'KZ' => 'Kazakhstan',
									'KE' => 'Kenya',
									'KI' => 'Kiribati',
									'KP' => 'Korea',
									'KR' => 'Korea',
									'KW' => 'Kuwait',
									'KG' => 'Kyrgyz Republic',
									'LA' => 'Lao',
									'LV' => 'Latvia',
									'LB' => 'Lebanon',
									'LS' => 'Lesotho',
									'LR' => 'Liberia',
									'LY' => 'Libyan Arab Jamahiriya',
									'LI' => 'Liechtenstein',
									'LT' => 'Lithuania',
									'LU' => 'Luxembourg',
									'MO' => 'Macao',
									'MK' => 'Macedonia',
									'MG' => 'Madagascar',
									'MW' => 'Malawi',
									'MY' => 'Malaysia',
									'MV' => 'Maldives',
									'ML' => 'Mali',
									'MT' => 'Malta',
									'MH' => 'Marshall Islands',
									'MQ' => 'Martinique',
									'MR' => 'Mauritania',
									'MU' => 'Mauritius',
									'YT' => 'Mayotte',
									'MX' => 'Mexico',
									'FM' => 'Micronesia',
									'MD' => 'Moldova',
									'MC' => 'Monaco',
									'MN' => 'Mongolia',
									'ME' => 'Montenegro',
									'MS' => 'Montserrat',
									'MA' => 'Morocco',
									'MZ' => 'Mozambique',
									'MM' => 'Myanmar',
									'NA' => 'Namibia',
									'NR' => 'Nauru',
									'NP' => 'Nepal',
									'AN' => 'Netherlands Antilles',
									'NL' => 'Netherlands the',
									'NC' => 'New Caledonia',
									'NZ' => 'New Zealand',
									'NI' => 'Nicaragua',
									'NE' => 'Niger',
									'NG' => 'Nigeria',
									'NU' => 'Niue',
									'NF' => 'Norfolk Island',
									'MP' => 'Northern Mariana Islands',
									'NO' => 'Norway',
									'OM' => 'Oman',
									'PK' => 'Pakistan',
									'PW' => 'Palau',
									'PS' => 'Palestinian Territory',
									'PA' => 'Panama',
									'PG' => 'Papua New Guinea',
									'PY' => 'Paraguay',
									'PE' => 'Peru',
									'PH' => 'Philippines',
									'PN' => 'Pitcairn Islands',
									'PL' => 'Poland',
									'PT' => 'Portugal, Portuguese Republic',
									'PR' => 'Puerto Rico',
									'QA' => 'Qatar',
									'RE' => 'Reunion',
									'RO' => 'Romania',
									'RU' => 'Russian Federation',
									'RW' => 'Rwanda',
									'BL' => 'Saint Barthelemy',
									'SH' => 'Saint Helena',
									'KN' => 'Saint Kitts and Nevis',
									'LC' => 'Saint Lucia',
									'MF' => 'Saint Martin',
									'PM' => 'Saint Pierre and Miquelon',
									'VC' => 'Saint Vincent and the Grenadines',
									'WS' => 'Samoa',
									'SM' => 'San Marino',
									'ST' => 'Sao Tome and Principe',
									'SA' => 'Saudi Arabia',
									'SN' => 'Senegal',
									'RS' => 'Serbia',
									'SC' => 'Seychelles',
									'SL' => 'Sierra Leone',
									'SG' => 'Singapore',
									'SK' => 'Slovakia (Slovak Republic)',
									'SI' => 'Slovenia',
									'SB' => 'Solomon Islands',
									'SO' => 'Somalia, Somali Republic',
									'ZA' => 'South Africa',
									'GS' => 'South Georgia and the South Sandwich Islands',
									'ES' => 'Spain',
									'LK' => 'Sri Lanka',
									'SD' => 'Sudan',
									'SR' => 'Suriname',
									'SJ' => 'Svalbard & Jan Mayen Islands',
									'SZ' => 'Swaziland',
									'SE' => 'Sweden',
									'CH' => 'Switzerland, Swiss Confederation',
									'SY' => 'Syrian Arab Republic',
									'TW' => 'Taiwan',
									'TJ' => 'Tajikistan',
									'TZ' => 'Tanzania',
									'TH' => 'Thailand',
									'TL' => 'Timor-Leste',
									'TG' => 'Togo',
									'TK' => 'Tokelau',
									'TO' => 'Tonga',
									'TT' => 'Trinidad and Tobago',
									'TN' => 'Tunisia',
									'TR' => 'Turkey',
									'TM' => 'Turkmenistan',
									'TC' => 'Turks and Caicos Islands',
									'TV' => 'Tuvalu',
									'UG' => 'Uganda',
									'UA' => 'Ukraine',
									'AE' => 'United Arab Emirates',
									'GB' => 'United Kingdom',
									'US' => 'United States of America',
									'UM' => 'United States Minor Outlying Islands',
									'VI' => 'United States Virgin Islands',
									'UY' => 'Uruguay, Eastern Republic of',
									'UZ' => 'Uzbekistan',
									'VU' => 'Vanuatu',
									'VE' => 'Venezuela',
									'VN' => 'Vietnam',
									'WF' => 'Wallis and Futuna',
									'EH' => 'Western Sahara',
									'YE' => 'Yemen',
									'ZM' => 'Zambia',
									'ZW' => 'Zimbabwe'
								);
								if( !$countryList[$code] ) {
									return $code;
								} else { 
									return $countryList[$code];
								}
							}
								
							echo tl_code_to_country( $ip_country );
							
							?>
						</div>
					</div>
					<div class="myip-detail-city">
						<div class="myip-detail-name">
							<?php _e( 'City', 'bento' ); ?>:
						</div>
						<div class="myip-detail-value">
							<?php 
							$ip_city = $geo_details->city; 
							echo $ip_city;
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="myip-body">
			<div class="bnt-container">
				<h1>
					<?php the_title(); ?>
				</h1>
				<div class="myip-excerpt">
					<?php echo get_the_excerpt(); ?>
				</div>
				<div class="myip-content">
					<?php the_content(); ?>
				</div>
			</div>
		</div>

	</main>
</div>
        
<?php get_footer(); ?>