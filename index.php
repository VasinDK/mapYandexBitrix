<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

// подключаем API яндекс карты
$APPLICATION->AddHeadString('<script src="https://api-maps.yandex.ru/2.1/?apikey=8fc7c264-1617-480b-8f63-9bffff484ecc&lang=ru_RU" type="text/javascript"></script>',true);
$APPLICATION->SetTitle("Офисы");
?>

<!-- место размещения карты -->
<div id="map" style="max-width: 100%; height: 500px"></div>

<script type="text/javascript">
    // Функция ymaps.ready() будет вызвана, когда загрузятся все компоненты API, а также когда будет готово DOM-дерево.
    ymaps.ready(init);
    function init(){
        // Создание карты.
        var myMap = new ymaps.Map("map", {
            // Координаты центра карты. Порядок: «широта, долгота».
            center: [55.76, 37.64],
            // Уровень масштабирования от 0 (весь мир) до 19.
            zoom: 10
        }, {
            searchControlProvider: 'yandex#search'
        }),
        objectManager = new ymaps.ObjectManager({
            // Чтобы метки начали кластеризоваться, выставляем опцию.
            clusterize: true,
            // ObjectManager принимает те же опции, что и кластеризатор.
            gridSize: 32,
            clusterDisableClickZoom: true
        });

		// Чтобы задать опции одиночным объектам и кластерам, обратимся к дочерним коллекциям ObjectManager.
		objectManager.objects.options.set('preset', 'islands#greenDotIcon');
		objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
		myMap.geoObjects.add(objectManager);

		$.ajax({
			url: "/local/mapOffice/mapOffice.php"
		}).done(function(data) {
			objectManager.add(data);
		});
    }
</script>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>