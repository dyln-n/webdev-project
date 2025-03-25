document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("product-form");
    const modal = document.getElementById("product-modal");
    const modalTitle = document.getElementById("modal-title");
    const submitBtn = document.getElementById("modal-submit-btn");
    const closeBtns = document.querySelectorAll(".close-modal");
    const radios = document.querySelectorAll("input[name='selected_product']");
    const updateBtn = document.getElementById("update-btn");
    const addBtn = document.getElementById("add-btn");
    const deleteBtn = document.getElementById("delete-btn");

    const deleteModal = document.getElementById("delete-modal");
    const confirmDeleteBtn = document.getElementById("confirm-delete-btn");
    const cancelDeleteBtn = document.getElementById("cancel-delete-btn");

    let lastSelectedRadio = null;

    // Helpers
    function showModal(title) {
        modal.classList.remove("hidden");
        modalTitle.textContent = title;
    }

    function closeModal() {
        modal.classList.add("hidden");
        form.reset();
        clearErrors();
    }

    function clearErrors() {
        document.querySelectorAll(".error-message").forEach(e => e.remove());
        document.querySelectorAll(".border-red-500").forEach(e => e.classList.remove("border-red-500"));
    }

    function validateFormFields() {
        clearErrors();

        const name = form.name;
        const description = form.description;
        const price = form.price;
        const stock = form.stock;

        if (name.value.trim() === "") {
            showError(name, "Name is required.");
            return false;
        }
        if (description.value.trim() === "") {
            showError(description, "Description is required.");
            return false;
        }
        if (price.value.trim() === "" || isNaN(price.value) || parseFloat(price.value) < 0) {
            showError(price, "Price must be a number > 0");
            return false;
        }
        if (stock.value.trim() === "" || !/^\d+$/.test(stock.value) || parseInt(stock.value) < 1) {
            showError(stock, "Stock must be a positive integer");
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

    // Gradual validation
    ["name", "description", "price", "stock"].forEach((fieldId) => {
        const input = document.getElementById(fieldId);
        input.addEventListener("input", () => {
            const value = input.value.trim();
            const existingError = input.parentNode.querySelector(".error-message");

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

            if (!isValid) {
                input.classList.add("border-red-500");
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

    // Reset & button toggle
    function clearSelection() {
        form.reset();
        document.getElementById("product_id").value = "";
        lastSelectedRadio = null;
        radios.forEach(r => r.checked = false);

        updateBtn.disabled = true;
        deleteBtn.disabled = true;
        updateBtn.classList.add("opacity-50", "cursor-not-allowed");
        deleteBtn.classList.add("opacity-50", "cursor-not-allowed");

        addBtn.disabled = false;
        addBtn.classList.remove("opacity-50", "cursor-not-allowed");
    }

    // Handle radio selection
    radios.forEach(radio => {
    radio.addEventListener("change", () => {
        if (lastSelectedRadio === radio) {
            radio.checked = false;
            clearSelection();
            return;
        }

        lastSelectedRadio = radio;
        const row = radio.closest("tr");

        form.product_id.value = radio.value;
        form.name.value = row.querySelector(".product-name").dataset.fullName;
        form.description.value = row.querySelector(".product-description").dataset.fullDescription;
        form.price.value = row.querySelector(".product-price").textContent.trim();
        form.stock.value = row.querySelector(".product-stock").textContent.trim();

        updateBtn.disabled = false;
        deleteBtn.disabled = false;
        updateBtn.classList.remove("opacity-50", "cursor-not-allowed");
        deleteBtn.classList.remove("opacity-50", "cursor-not-allowed");

        addBtn.disabled = true;
        addBtn.classList.add("opacity-50", "cursor-not-allowed");
    });

    radio.addEventListener("dblclick", () => {
        radio.checked = false;
        clearSelection();
    });
    
});

    // Add button
    addBtn.addEventListener("click", () => {
        clearSelection();
        form.reset();
        form.action = "/seller/products";
        const methodInput = form.querySelector("input[name='_method']");
        if (methodInput) methodInput.remove();

        showModal("Add Product");
    });

    // Update button
    updateBtn.addEventListener("click", () => {
        if (!form.product_id.value) return;

        form.action = `/seller/products/${form.product_id.value}`;
        if (!form.querySelector("input[name='_method']")) {
            const method = document.createElement("input");
            method.type = "hidden";
            method.name = "_method";
            method.value = "PUT";
            form.appendChild(method);
        }

        showModal("Update Product");
    });

    // Submit
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        if (!validateFormFields()) return;

        const isUpdate = form.querySelector("input[name='_method']");
        const id = form.product_id.value;

        const formData = new FormData(form);
        const url = form.action;

        fetch(url, {
            method: isUpdate ? "POST" : "POST",
            headers: {
                "X-CSRF-TOKEN": form.querySelector("input[name='_token']").value
            },
            body: formData
        })
        .then(res => res.json())
        .then(result => {
            if (!result.success) {
                alert(result.message || "Error");
                return;
            }

            if (isUpdate) {
                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    row.querySelector(".product-name").textContent = form.name.value.slice(0, 15) + (form.name.value.length > 15 ? '…' : '');
                    row.querySelector(".product-name").dataset.fullName = form.name.value;
                    row.querySelector(".product-description").textContent = form.description.value.slice(0, 15) + (form.description.value.length > 15 ? '…' : '');
                    row.querySelector(".product-description").dataset.fullDescription = form.description.value;
                    row.querySelector(".product-price").textContent = parseFloat(form.price.value).toFixed(2);
                    row.querySelector(".product-stock").textContent = form.stock.value;
                }
            } else {
                window.location.reload(); // simplest way to refresh added row
            }

            closeModal();
            clearSelection();
        })
        .catch(err => {
            console.error(err);
            alert("Server error.");
        });
    });

    // Delete
    deleteBtn.addEventListener("click", () => {
        if (!form.product_id.value) return;
        deleteModal.classList.remove("hidden");
    });

    confirmDeleteBtn.addEventListener("click", () => {
        const id = form.product_id.value;
        fetch(`/seller/products/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector("input[name='_token']").value,
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(result => {
            if (!result.success) {
                alert("Delete failed.");
                return;
            }
            document.querySelector(`tr[data-id="${id}"]`)?.remove();
            clearSelection();
            deleteModal.classList.add("hidden");
        })
        .catch(err => {
            console.error(err);
            alert("Delete error.");
        });
    });

    cancelDeleteBtn.addEventListener("click", () => {
        deleteModal.classList.add("hidden");
    });

    closeBtns.forEach(btn => {
        btn.addEventListener("click", closeModal);
    });
});
