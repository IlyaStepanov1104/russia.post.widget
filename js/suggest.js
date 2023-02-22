ymaps.ready(init);

function init() {
    // Задаем собственный провайдер поисковых подсказок и максимальное количество результатов.
    var suggestView = new ymaps.SuggestView('country', {provider: provider, results: 3});
}
