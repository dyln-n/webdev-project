document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("product-form");
    const modal = document.getElementById("product-modal");
    const modalTitle = document.getElementById("modal-title");
    const submitBtn = document.getElementById("modal-submit-btn");
    const closeBtns = document.querySelectorAll(".close-modal");
    const addBtn = document.getElementById("add-btn");
    const deleteModal = document.getElementById("delete-modal");
const confirmDeleteBtn = document.getElementById("confirm-delete-btn");
const cancelDeleteBtn = document.getElementById("cancel-delete-btn");
const modalDeleteBtn = document.getElementById("modal-delete-btn");

modalDeleteBtn.addEventListener("click", () => {
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
        deleteModal.classList.add("hidden");
        modal.classList.add("hidden");
        form.reset();
    })
    .catch(err => {
        console.error(err);
        alert("Delete error.");
    });
});

cancelDeleteBtn.addEventListener("click", () => {
    deleteModal.classList.add("hidden");
});

    // Show modal with title
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

    function showError(field, message) {
        field.classList.add("border-red-500");
        const error = document.createElement("p");
        error.className = "text-red-500 text-sm mt-1 error-message";
        error.textContent = message;
        field.parentNode.appendChild(error);
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

    addBtn.addEventListener("click", () => {
        form.reset();
        form.action = "/seller/products";
        const methodInput = form.querySelector("input[name='_method']");
        if (methodInput) methodInput.remove();
        form.product_id.value = "";
        showModal("Add Product");
    });

document.querySelectorAll('.action-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const row = btn.closest('tr');
        const id = row.dataset.id;

        // 填充表单字段
        form.product_id.value = id;
        form.name.value = row.querySelector(".product-name").dataset.fullName;
        form.description.value = row.querySelector(".product-description").dataset.fullDescription;
        form.price.value = row.querySelector(".product-price").textContent.trim();
        form.stock.value = row.querySelector(".product-stock").textContent.trim();
        form.category_id.value = row.dataset.categoryId;

        // 设置图片预览
        const imagePath = row.dataset.imagePath;
        const previewWrapper = document.getElementById("image-preview-wrapper");
        const previewImg = document.getElementById("product-preview-img");

        if (imagePath) {
            previewImg.src = '/' + imagePath;
            previewWrapper.classList.remove('hidden');
        } else {
            previewImg.src = '';
            previewWrapper.classList.add('hidden');
        }

        // 设置表单 action 和 method
        form.action = `/seller/products/${id}`;
        if (!form.querySelector("input[name='_method']")) {
            const method = document.createElement("input");
            method.type = "hidden";
            method.name = "_method";
            method.value = "PUT";
            form.appendChild(method);
        }

        showModal("Update Product");
    });
});


    form.addEventListener("submit", function (e) {
        e.preventDefault();
        if (!validateFormFields()) return;

        const isUpdate = form.querySelector("input[name='_method']");
        const id = form.product_id?.value;
        const url = form.action;
        const formData = new FormData(form);
        const imageInput = form.querySelector('input[type="file"][name="image"]');

        if (imageInput && imageInput.files.length > 0) {
            const file = imageInput.files[0];
            if (file.size > 2 * 1024 * 1024) {
                alert("Image must be smaller than 2MB");
                return;
            }
            formData.append('image', file);
        }

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": form.querySelector("input[name='_token']").value,
                "Accept": "application/json"
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
                    row.querySelector(".product-name").textContent = form.name.value;
                    row.querySelector(".product-name").dataset.fullName = form.name.value;
                    const desc = form.description.value;
                    row.querySelector(".product-description").textContent = desc.length > 60 ? desc.slice(0, 60) + '…' : desc;
                    row.querySelector(".product-description").dataset.fullDescription = desc;
                    row.querySelector(".product-price").textContent = parseFloat(form.price.value).toFixed(2);
                    row.querySelector(".product-stock").textContent = form.stock.value;
                }
            } else {
                window.location.reload();
            }

            closeModal();
        })
        .catch(err => {
            console.error(err);
            alert("Server error.");
        });
    });

    closeBtns.forEach(btn => {
        btn.addEventListener("click", closeModal);
    });
});
