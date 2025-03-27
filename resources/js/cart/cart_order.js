document.addEventListener("DOMContentLoaded", function () {
    const checkoutBtn = document.getElementById("checkout-btn");
    const modal = document.getElementById("checkout-modal");
    const cancelBtn = document.getElementById("cancel-checkout");
    const successModal = document.getElementById("order-success-modal");
    const goHomeBtn = document.getElementById("close-success");
    const form = document.getElementById("checkout-form");

    if (!form) return;

    const fields = Array.from(form.querySelectorAll("input"));

    if (checkoutBtn) {
        checkoutBtn.addEventListener("click", function (e) {
            e.preventDefault();
            modal.classList.remove("hidden");
        });
    }

    cancelBtn.addEventListener("click", () => modal.classList.add("hidden"));
    goHomeBtn.addEventListener("click", () => window.location.href = "/");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        // 移除所有错误提示
        fields.forEach(input => {
            const errorSpan = input.nextElementSibling;
            if (errorSpan && errorSpan.classList.contains("text-red-600")) {
                errorSpan.remove();
            }
        });

        const firstEmpty = fields.find(input => input.value.trim() === "");
        if (firstEmpty) {
            const errorMsg = document.createElement("div");
            errorMsg.classList.add("text-red-600", "text-sm", "mt-1");
            errorMsg.textContent = `Please fill out: ${firstEmpty.placeholder}`;
            firstEmpty.insertAdjacentElement("afterend", errorMsg);
            firstEmpty.focus();
            return;
        }

        const payload = Object.fromEntries(new FormData(form));

        fetch("/checkout", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add("hidden");
                    successModal.classList.remove("hidden");
                } else {
                    alert(data.message || "Checkout failed");
                }
            })
            .catch(err => {
                alert("An error occurred. Try again.");
            });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const goHomeBtn = document.getElementById("go-home");
    if (goHomeBtn) {
        goHomeBtn.addEventListener("click", () => {
            window.location.href = "/";
        });
    }
});
