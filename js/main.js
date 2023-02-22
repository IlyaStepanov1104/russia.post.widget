async function handleFormSubmit(event) {
    event.preventDefault()
    const formData = new FormData();
    formData.append('country', document.getElementById('country').value);
    formData.append('delivery', document.getElementById('delivery').value);
    let result = document.getElementById("widget_post_result");
    let response = await fetch("count_cost.php",
        {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            body: formData
        })
    if (response.ok) {
        let json = await response.json();
        result.innerHTML = json.message;
    } else {
        result.innerHTML = "Ошибка: " + response.status;
    }
}


let form = document.getElementById("widget_post_form");
form.addEventListener('submit', handleFormSubmit, false);


