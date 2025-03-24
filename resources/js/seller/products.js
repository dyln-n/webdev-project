document.addEventListener("DOMContentLoaded", () => {
    const radios = document.querySelectorAll("input[name='selected_product']");
    const form = document.getElementById("product-form");

    const addBtn = document.getElementById("add-btn");
    const updateBtn = document.getElementById("update-btn");
    const deleteBtn = document.getElementById("delete-btn");

    let lastSelectedRadio = null;

    // Utility: disable button with gray visual
    function disableButton(btn) {
        btn.disabled = true;
        btn.classList.add("opacity-50", "cursor-not-allowed", "pointer-events-none");
    }

    // Utility: enable button
    function enableButton(btn) {
        btn.disabled = false;
        btn.classList.remove("opacity-50", "cursor-not-allowed", "pointer-events-none");
    }

    // Initial button state on load
    disableButton(updateBtn);
    disableButton(deleteBtn);
    enableButton(addBtn);

    // Handle product selection
    radios.forEach(radio => {
        radio.addEventListener("change", () => {
            if (lastSelectedRadio === radio) {
                radio.checked = false;
                clearSelection();
                return;
            }

            lastSelectedRadio = radio;

            const row = radio.closest("tr");
            const id = radio.value;

            document.getElementById("product_id").value = id;
            document.getElementById("name").value = row.querySelector(".product-name").textContent.trim();
            document.getElementById("description").value = row.querySelector(".product-description").textContent.trim();
            document.getElementById("price").value = row.querySelector(".product-price").textContent.trim();
            document.getElementById("stock").value = row.querySelector(".product-stock").textContent.trim();

            // Enable update/delete, disable add
            enableButton(updateBtn);
            enableButton(deleteBtn);
            disableButton(addBtn);
        });

        radio.addEventListener("dblclick", () => {
            radio.checked = false;
            clearSelection();
        });
    });

    function validateFormFields() {
    // Clear previous messages
    document.querySelectorAll(".error-message").forEach(e => e.remove());
    document.querySelectorAll(".border-red-500").forEach(e => e.classList.remove("border-red-500"));

    const name = document.getElementById("name");
    const description = document.getElementById("description");
    const price = document.getElementById("price");
    const stock = document.getElementById("stock");

    // Step-by-step field validation (from top to bottom)
    if (name.value.trim() === "") {
        showError(name, "Name is required.");
        return false;
    }

    if (description.value.trim() === "") {
        showError(description, "Description is required.");
        return false;
    }

    if (price.value.trim() === "" || isNaN(price.value) || parseFloat(price.value) < 0) {
        showError(price, "Price is required.");
        return false;
    }

    if (stock.value.trim() === "" || !/^\d+$/.test(stock.value) || parseInt(stock.value) < 1) {
        showError(stock, "Stock is required.");
        return false;
    }

    return true;
}

function showError(field, message) {
    field.classList.add("border-red-500");

    const error = document.createElement("p");
    error.className = "text-red-500 text-sm mt-1 error-message";
    error.textContent = message;

    field.parentNode.appendChild(error);
}

    // Update logic
    updateBtn?.addEventListener("click", async function () {
        if (!validateFormFields()) return;

        const id = document.getElementById("product_id").value;
        if (!id) return;

        const formData = new FormData(form);
        formData.append("_method", "PUT");

        try {
            const response = await fetch(`/seller/products/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                },
                body: formData,
            });

            const result = await response.json();

            if (!result.success) {
                alert("Update failed: " + (result.message || "Unknown error"));
                return;
            }

            const row = document.querySelector(`tr[data-id="${id}"]`);
            row.querySelector(".product-name").textContent = form.name.value;
            row.querySelector(".product-description").textContent = form.description.value;
            row.querySelector(".product-price").textContent = parseFloat(form.price.value).toFixed(2);
            row.querySelector(".product-stock").textContent = form.stock.value;

            document.getElementById("product_id").value = "";
            form.reset();
            document.querySelectorAll("input[name='selected_product']").forEach(r => r.checked = false);

            // Reset buttons after update
            disableButton(updateBtn);
            disableButton(deleteBtn);
            enableButton(addBtn);

        } catch (error) {
            console.error("Update failed:", error);
            alert("Network error during update.");
        }
    });

    // Add logic
addBtn?.addEventListener("click", function (e) {
    e.preventDefault();

    if (!validateFormFields()) return;

    form.action = "/seller/products";
    form.method = "POST";

    const methodInput = form.querySelector("input[name='_method']");
    if (methodInput) methodInput.remove();

    form.submit();
});

    // Delete logic
    deleteBtn?.addEventListener("click", async function () {
        const id = document.getElementById("product_id").value;
        if (!id) return;

        if (!confirm("Are you sure you want to delete this product?")) return;

        try {
            const response = await fetch(`/seller/products/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                },
            });

            const result = await response.json();

            if (!result.success) {
                alert("Delete failed: " + (result.message || "Unknown error"));
                return;
            }

            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) row.remove();

            document.getElementById("product_id").value = "";
            form.reset();
            clearSelection();

        } catch (error) {
            console.error("Delete failed:", error);
            alert("Error deleting product.");
        }
    });

    // Reset selection and buttons
    function clearSelection() {
        form.reset();
        document.getElementById("product_id").value = "";

        document.querySelectorAll("input[name='selected_product']").forEach(r => r.checked = false);
        lastSelectedRadio = null;

        disableButton(updateBtn);
        disableButton(deleteBtn);
        enableButton(addBtn);
    }
});

["name", "description", "price", "stock"].forEach((fieldId) => {
    const input = document.getElementById(fieldId);

    input.addEventListener("input", () => {
        const value = input.value.trim();

        // Remove existing error if input is valid
        let isValid = true;
        let message = "";

        if (fieldId === "price") {
            if (value === "" || isNaN(value) || parseFloat(value) < 0) {
                isValid = false;
                message = "Price must be a number > 0";
            }
        } else if (fieldId === "stock") {
            if (value === "" || !/^\d+$/.test(value) || parseInt(value) < 1) {
                isValid = false;
                message = "Stock must be a positive integer";
            }
        } else {
            if (value === "") {
                isValid = false;
                message = `${fieldId.charAt(0).toUpperCase() + fieldId.slice(1)} is required.`;
            }
        }

        const existingError = input.parentNode.querySelector(".error-message");

        if (!isValid) {
            input.classList.add("border-red-500");

            // If no existing error message, create one
            if (!existingError) {
                const error = document.createElement("p");
                error.className = "text-red-500 text-sm mt-1 error-message";
                error.textContent = message;
                input.parentNode.appendChild(error);
            } else {
                existingError.textContent = message;
            }
        } else {
            input.classList.remove("border-red-500");
            if (existingError) existingError.remove();
        }
    });
    
});



