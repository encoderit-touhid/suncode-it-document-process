<?php
class encoderit_create_custom_table
{
    public static function countryList() {
        return array(
            "AF" => "Afghanistan",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "BQ" => "British Antarctic Territory",
            "IO" => "British Indian Ocean Territory",
            "VG" => "British Virgin Islands",
            "BN" => "Brunei",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CT" => "Canton and Enderbury Islands",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos [Keeling] Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo - Brazzaville",
            "CD" => "Congo - Kinshasa",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "CI" => "Côte d’Ivoire",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "NQ" => "Dronning Maud Land",
            "DD" => "East Germany",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "FQ" => "French Southern and Antarctic Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GG" => "Guernsey",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and McDonald Islands",
            "HN" => "Honduras",
            "HK" => "Hong Kong SAR China",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IM" => "Isle of Man",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JE" => "Jersey",
            "JT" => "Johnston Island",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Laos",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macau SAR China",
            "MK" => "Macedonia",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "FX" => "Metropolitan France",
            "MX" => "Mexico",
            "FM" => "Micronesia",
            "MI" => "Midway Islands",
            "MD" => "Moldova",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "ME" => "Montenegro",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar [Burma]",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NT" => "Neutral Zone",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "KP" => "North Korea",
            "VD" => "North Vietnam",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PC" => "Pacific Islands Trust Territory",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territories",
            "PA" => "Panama",
            "PZ" => "Panama Canal Zone",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "YD" => "People's Democratic Republic of Yemen",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn Islands",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RO" => "Romania",
            "RU" => "Russia",
            "RW" => "Rwanda",
            "RE" => "Réunion",
            "BL" => "Saint Barthélemy",
            "SH" => "Saint Helena",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "MF" => "Saint Martin",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "RS" => "Serbia",
            "CS" => "Serbia and Montenegro",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "KR" => "South Korea",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syria",
            "ST" => "São Tomé and Príncipe",
            "TW" => "Taiwan",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UM" => "U.S. Minor Outlying Islands",
            "PU" => "U.S. Miscellaneous Pacific Islands",
            "VI" => "U.S. Virgin Islands",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "SU" => "Union of Soviet Socialist Republics",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "ZZ" => "Unknown or Invalid Region",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VA" => "Vatican City",
            "VE" => "Venezuela",
            "VN" => "Vietnam",
            "WK" => "Wake Island",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe",
            "AX" => "Åland Islands",
            "KRG"=>"Kurdistan Region of Iraq"
            );
    } 
    public static function create_custom_tables()
    {
        add_option('ENCODER_IT_STRIPE_PK','pk_test_51OD1o3HXs2mM51TXR04wpLYzxxWNpOQWZr8Y84oV0Bp5aP1sB0gVic7JqBdrOgQmqYAwT7a9TOfq4UBG5ioifu9F00VwcHhkCb');
        add_option('ENCODER_IT_STRIPE_SK','sk_test_51OD1o3HXs2mM51TXAPMu48pbSpxilR2QjxiXEipq60TE8y96wg51zs9qPSDZomhDtYGcmwIFPboEgFaHi1SINsNZ00FZ8b7i8R');
        add_option('ENCODER_IT_PAYPAL_CLIENT','AVT1TGV_xT-FR1XRXZdKgsyoXIhHf_N4-j26F0W6bYXgLcv4r2jJLu7Bsa1aabiU-0pVGrDFUIdOpvrQ');

        self::create_encoderit_custom_form_services_table();
        self::create_encoderit_encoderit_custom_form_table();
        self::create_encoderit_country_with_code_table();
        self::create_encoderit_service_with_country_table();
        self::create_encoderit_fixed_service_with_country_table();
        global $wpdb;
        $table_name=$wpdb->prefix . 'encoderit_country_with_code';
        $countryList=self::countryList();

            foreach($countryList as $key=>$value)
            {
                $wpdb->insert(
                    $table_name,
                    array(
                        'country_name' => $value,
                        'country_code' => $key,
                    )
                );
            }
    }
    public  static function drop_custom_tables()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'encoderit_country_with_code';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");

    }
    public static function  create_encoderit_custom_form_services_table()
    {
        global $wpdb;
       
        $table_name = $wpdb->prefix . 'encoderit_custom_form_services';

       $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS  $table_name (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `service_name` VARCHAR(100) NOT NULL,
            `is_active` TINYINT NOT NULL DEFAULT '1' COMMENT '1 Active , 0 DeActive',
            `created_at` DATETIME NULL DEFAULT NULL,
            `updated_at` DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql ); 
    }
    public static function create_encoderit_service_with_country_table()
    {
        global $wpdb;
       
        $table_name = $wpdb->prefix . 'encoderit_service_with_country';

       $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS  $table_name (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `service_id` BIGINT NOT NULL,
            `country_id` BIGINT NOT NULL,
            `price` FLOAT NOT NULL,
            `is_active` TINYINT NOT NULL DEFAULT '1' COMMENT '1 Active , 0 DeActive 3 Remove',
            `created_at` DATETIME NULL DEFAULT NULL,
            `updated_at` DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    public static function create_encoderit_encoderit_custom_form_table()
    {
        global $wpdb;
         
        $table_name = $wpdb->prefix . 'encoderit_custom_form';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS  $table_name (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `user_id` BIGINT NOT NULL,
            `country_id` BIGINT NOT NULL, 
            `person_number` MEDIUMINT NOT NULL,
            `description` TEXT NULL DEFAULT NULL,
            `services` TEXT NULL DEFAULT NULL,    
            `files_by_user` JSON NULL DEFAULT NULL,
            `files_by_admin` JSON NULL DEFAULT NULL, 
            `payment_method` VARCHAR(100) NULL, 
            `transaction_number` VARCHAR(100) NULL,
            `total_price` FLOAT NOT NULL,
            `is_cancelled` TINYINT NULL DEFAULT '0', 
            `created_at` TIMESTAMP NULL DEFAULT NULL, 
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_by` BIGINT NOT NULL DEFAULT '0',
             PRIMARY KEY (`id`)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    public static function create_encoderit_country_with_code_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'encoderit_country_with_code';

        $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE IF NOT EXISTS  $table_name (
                `id` BIGINT NOT NULL AUTO_INCREMENT,
                `country_name` VARCHAR(100) NULL, 
                `country_code` VARCHAR(100) NULL,
                PRIMARY KEY (`id`)
            ) $charset_collate";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    public static function create_encoderit_fixed_service_with_country_table()
    {
        global $wpdb;
       
        $table_name = $wpdb->prefix . 'encoderit_fixed_service_with_country';

       $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS  $table_name (
            `id` BIGINT NOT NULL AUTO_INCREMENT,
            `country_id` BIGINT NOT NULL,
            `service_json` TEXT NULL DEFAULT NULL,
            `is_active` TINYINT NOT NULL DEFAULT '1' COMMENT '1 Active , 0 DeActive',
            `created_at` DATETIME NULL DEFAULT NULL,
            `updated_at` DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}