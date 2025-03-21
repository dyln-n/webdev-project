function enableAllEdits(button) {
    let row = button.closest('tr');
    let spans = row.querySelectorAll('.editable-text');
    let inputs = row.querySelectorAll('.edit-input');

    if (!inputs[0].classList.contains('hidden')) {
        saveEdits(row);
        return;
    }

    spans.forEach(span => span.classList.add('hidden'));
    inputs.forEach(input => {
        input.classList.remove('hidden');
        input.focus();
    });

    document.addEventListener("click", function(event) {
        if (!row.contains(event.target) && !event.target.closest('button')) {
            saveEdits(row);
        }
    }, { once: true });
}

function saveEdits(row) {
    let spans = row.querySelectorAll('.editable-text');
    let inputs = row.querySelectorAll('.edit-input');
    let productId = row.dataset.id;
    let updatedData = {
        name: inputs[0].value,
        price: inputs[1].value,
        stock: inputs[2].value
    };

    axios.put(`/seller/products/${productId}`, updatedData, {
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (response.data.success) {
            spans[0].innerText = updatedData.name;
            spans[1].innerText = `$${updatedData.price}`;
            spans[2].innerText = updatedData.stock;

            spans.forEach(span => span.classList.remove('hidden'));
            inputs.forEach(input => input.classList.add('hidden'));
        } else {
            alert("Update failed.");
        }
    })
    .catch(error => console.error("Error:", error));
}


window.enableAllEdits = enableAllEdits;
window.saveEdits = saveEdits;
