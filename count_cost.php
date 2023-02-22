<?php
require 'countries_id.php'; //номера стран

$country = COUNTRY_ID[mb_strtoupper($_POST['country'])]; //страна получателя
$delivery = $_POST['delivery']; //тип доставки
$mass = 0.1; // масса посылки в граммах
$valuation = 0; //Объявленная ценность, в рублях
$vat = 1; //НДС 20% (1 — с НДС, 0 — без НДС)

// Формирование ссылки с GET параметрами
$url = "https://postprice.ru/engine/international/api.php?country=" . $country;
if ($mass) {
    $url .= "&mass=" . $mass;
}
if ($valuation) {
    $url .= "&valuation=" . $valuation;
}
if ($vat) {
    $url .= "&vat=" . $vat;
}

// Инициализация сеанса cURL
$ch = curl_init();
// Установка URL
curl_setopt($ch, CURLOPT_URL, $url);
// Установка CURLOPT_RETURNTRANSFER (вернуть ответ в виде строки)
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// Выполнение запроса cURL
$output = curl_exec($ch);
// закрытие сеанса curl для освобождения системных ресурсов
curl_close($ch);

$output = json_decode($output, true);
if ($output['code'] == 100) {
    if ($delivery == 'avia') {
        if ($output['pkg_avia'] == 0) {
            echo json_encode(array('type' => 'yes', 'message' => "Ошибка: данный тип посылки не отправляется в страну-получателя!"));
        } else {
            echo json_encode(array('type' => 'yes', 'message' => "Стоимость посылки (авиа): " . $output['pkg_avia'] . " руб."));
        }
    } else {
        if ($output['pkg'] == 0) {
            echo json_encode(array('type' => 'yes', 'message' => "Ошибка: данный тип посылки не отправляется в страну-получателя!"));
        } else {
            echo json_encode(array('type' => 'yes', 'message' => "Стоимость посылки: " . $output['pkg'] . " руб."));
        }
    }
} else if ($output['code'] == 101) {
    echo json_encode(array('type' => 'yes', 'message' => "Ошибка: Некорректное значение массы отправления"));
} else if ($output['code'] == 103) {
    echo json_encode(array('type' => 'yes', 'message' => "Ошибка: Некорректная страна-получатель"));
} else if ($output['code'] == 105) {
    echo json_encode(array('type' => 'yes', 'message' => "Ошибка: Исчерпан лимит запросов"));
} else {
    echo json_encode(array('type' => 'yes', 'message' => "Непредвиденная ошибка. Попробуйте ещё раз!"));
}

http_response_code(200);