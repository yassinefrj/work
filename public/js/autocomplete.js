function addressAutocomplete() {

    const MIN_ADDRESS_LENGTH = 3;
    const DEBOUNCE_DELAY = 300;

    const inputElement = document.getElementById("address");
    const inputContainerElement = document.getElementById("input-container");
    const containerElement = document.getElementById("autocomplete-container");

    let currentTimeout;
    let currentPromiseReject;
    var currentItems;

    inputElement.addEventListener("input", function (e) {
        const currentValue = inputElement.value;

        if (currentTimeout) {
            clearTimeout(currentTimeout);
        }

        if (currentPromiseReject) {
            currentPromiseReject({
                canceled: true
            });
        }

        if (!currentValue || currentValue.length < MIN_ADDRESS_LENGTH) {
            return false;
        }

        currentTimeout = setTimeout(() => {
            currentTimeout = null;
            const promise = new Promise((resolve, reject) => {
                currentPromiseReject = reject;
                const apiKey = "64f2927952ba4bed866732f6b039fc3a";
                var url = `https://api.geoapify.com/v1/geocode/autocomplete?text=${encodeURIComponent(currentValue)}&format=json&limit=5&apiKey=${apiKey}`;

                fetch(url)
                    .then(response => {
                        currentPromiseReject = null;
                        if (response.ok) {
                            response.json().then(data => resolve(data));
                        } else {
                            response.json().then(data => reject(data));
                        }
                    });
            });

            promise.then((data) => {
                currentItems = data.results;
                const autocompleteItemsElement = document.createElement("div");
                autocompleteItemsElement.setAttribute("class", "autocomplete-items");
                inputContainerElement.appendChild(autocompleteItemsElement);

                data.results.forEach((result, index) => {
                    const itemElement = document.createElement("div");
                    itemElement.innerHTML = result.formatted;
                    autocompleteItemsElement.appendChild(itemElement);
                    itemElement.addEventListener("click", function (e) {
                        inputElement.value = currentItems[index].formatted;
                        closeDropDownList();
                    });
                });
            }, (err) => {
                if (!err.canceled) {
                    console.log(err);
                }
            });
        }, DEBOUNCE_DELAY);
    });

    document.addEventListener("click", function(e) {
        if(e.target !== inputElement) {
            closeDropDownList();
        } else if (!inputContainerElement.querySelector(".autocomplete-items")) {
            var event = document.createEvent('Event');
            event.initEvent('input', true, true);
            inputElement.dispatchEvent(event);
          }
    });

    function closeDropDownList() {
        var autocompleteItemsElement = inputContainerElement.querySelector(".autocomplete-items");
        if (autocompleteItemsElement) {
            inputContainerElement.removeChild(autocompleteItemsElement);
        }
    }
}

addressAutocomplete();
