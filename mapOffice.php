<?php

require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

// подключаем iblock
if(CModule::IncludeModule('iblock'))
{	
	// запрашиваем данные из инфоблока с id 4
	$arFilter = [
		'IBLOCK_ID' => 4
	];
	/**
	* PROPERTY_NAME_OFFICE	- название
	* PROPERTY_TELEFONE		- телефон
	* PROPERTY_EMAIL		- емаил
	* PROPERTY_ADRESS 		- адрес
	* PROPERTY_CITY			- город
	* PROPERTY_LATITUDE		- широта
	* PROPERTY_LONGITUDE 	- долгота
	*/
	$arSelectFields = ['ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_NAME_OFFICE', 'PROPERTY_TELEFONE', 'PROPERTY_EMAIL',  'PROPERTY_ADRESS', 'PROPERTY_CITY', 'PROPERTY_LATITUDE', 'PROPERTY_LONGITUDE'];
	$addresses = CIBlockElement::GetList([], $arFilter, false, false, $arSelectFields);

	// создаем всплывающие окна для карты
	while ($address = $addresses->fetch()) {
		$res[] = [
            'type' => 'Feature',
            'id' => $address['ID'],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [
                   $address['PROPERTY_LATITUDE_VALUE'],
                   $address['PROPERTY_LONGITUDE_VALUE']
                ]
            ],
            'properties' => [
				'balloonContentHeader' => '<font size=3>Офис: ' . $address['PROPERTY_NAME_OFFICE_VALUE'] . '</font>',
                'balloonContentBody' => '<p> г. ' . $address['PROPERTY_CITY_VALUE'] . ' '. $address['PROPERTY_ADRESS_VALUE'] . '</p>',
				'balloonContentFooter' => '<font size=1>Телефон: </font> <strong>' . $address['PROPERTY_TELEFONE_VALUE'] . '</strong><br/><font size=1>E-mail: </font> <strong>' . $address['PROPERTY_EMAIL_VALUE'] . '</strong>',
				'clusterCaption' => '<strong>' . $address['PROPERTY_NAME_OFFICE_VALUE'] . '</strong> ',
				'hintContent' => '<strong>Офис: ' . $address['PROPERTY_NAME_OFFICE_VALUE'] . '</strong>'
            ]
        ];

	}
}
// окончательно формируем и отправляем ответ
$result = [
    'type' => 'FeatureCollection',
    'features' => $res
];
$result = json_encode($result);
echo $result;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");