let isCartLoading = false;
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount(); // Update cart count when page loads

    // Get elements
    const loginBtn = document.getElementById('loginBtn');
    const closePopup = document.getElementById('close-login');
    const loginFormContainer = document.getElementById("login-form-container");
    const loginOverlay = document.getElementById("login-overlay");
    const loginForm = document.querySelector('.form-box.login-content');
    const registerForm = document.querySelector('.form-box.register');
    const forgotForm = document.querySelector('.form-box.forgot');
    const registerLink = document.querySelector('.register-link');
    const loginLink = document.querySelector('.login-link');
    const forgotLink = document.querySelector('.forgot-link');
    const createAccountLink = document.querySelector('.create-link');
    const loginFormCon = document.querySelector('#login-form');
    const cartOverlay = document.getElementById("cart-overlay");
    const cartSidebar = document.getElementById("cart-sidebar");
    const cartButton = document.querySelector(".cart img");
    const closeCart = document.getElementById("close-cart");
    const checkoutBtn = document.getElementById("checkout-btn");

    if (checkoutBtn) {
        checkoutBtn.addEventListener("click", function () {
            let cart = JSON.parse(localStorage.getItem("cart")) || [];

            if (!cart || cart.length === 0) {
                if (!document.getElementById("empty-cart-alert")) {
                    let alertDiv = document.createElement("div");
                    alertDiv.id = "empty-cart-alert";
                    alertDiv.textContent = "Your cart is empty!";
                    alertDiv.style.color = "red";
                    document.body.appendChild(alertDiv);
                }
                return;
            }

            fetch("checkout.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ cart: cart }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert("Checkout failed: " + data.error);
                } else {
                    openRazorpayPayment(data);
                }
            })
            .catch(error => {
                console.error("Error during checkout:", error);
            });
        });
    }

    function openRazorpayPayment(orderData) {
        var options = {
            "key": orderData.key,
            "amount": orderData.amount,
            "currency": orderData.currency,
            "name": "Your Rental Website",
            "description": "Secure Payment",
            "order_id": orderData.order_id,
            "handler": function (response) {
                alert("Payment successful! Payment ID: " + response.razorpay_payment_id);
                window.location.href = "success.php?payment_id=" + response.razorpay_payment_id;
            },
            "theme": {
                "color": "#3399cc"
            }
        };

        var rzp1 = new Razorpay(options);
        rzp1.open();
    }

    // Open cart overlay
    cartButton.addEventListener("click", () => {
        cartOverlay.style.display = "flex";
        loadCartItems();
    });

    // Close cart overlay
    closeCart.addEventListener("click", () => {
        cartOverlay.style.display = "none";
    });

    // Close cart on clicking outside the sidebar
    cartOverlay.addEventListener("click", (e) => {
        if (e.target === cartOverlay) {
            cartOverlay.style.display = "none";
        }
    });

    // Show login form when login button is clicked
    loginBtn.addEventListener("click", function () {
        loginOverlay.style.display = "block";
        loginFormContainer.style.display = "block";
        loginForm.style.display = "block";
        registerForm.style.display = "none";
        forgotForm.style.display = "none";
    });

    // Close login popup when close icon or overlay is clicked
    function closeLoginPopup() {
        loginFormContainer.style.display = 'none';
        loginOverlay.style.display = 'none';
    }

    closePopup.addEventListener('click', closeLoginPopup);
    loginOverlay.addEventListener('click', closeLoginPopup);

    // Close popup when pressing Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeLoginPopup();
        }
    });

    // Switch forms
    registerLink.addEventListener("click", (e) => {
        e.preventDefault();
        toggleForms('register');
    });

    loginLink.addEventListener("click", (e) => {
        e.preventDefault();
        toggleForms('login');
    });

    forgotLink.addEventListener("click", (e) => {
        e.preventDefault();
        toggleForms('forgot');
    });

    createAccountLink.addEventListener("click", (e) => {
        e.preventDefault();
        toggleForms('register');
    });

    function toggleForms(formType) {
        loginForm.style.display = formType === 'login' ? "block" : "none";
        registerForm.style.display = formType === 'register' ? "block" : "none";
        forgotForm.style.display = formType === 'forgot' ? "block" : "none";
    }
});

// AJAX Login Handling
loginFormCon.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(loginFormCon);
    fetch('../pages/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            window.location.href = '../pages/index.php';
        }
    })
    .catch(error => console.error('Error:', error));
});

// Handle registration form submission via AJAX
registerForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const registerData = new FormData(registerForm);
    fetch('../pages/register.php', {
        method: 'POST',
        body: registerData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            toggleForms('login');
        }
    })
    .catch(error => console.error('Error:', error));
});

// Handle forgot password form submission via AJAX
forgotForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const forgotData = new FormData(forgotForm);
    fetch('../pages/forgot.php', {
        method: 'POST',
        body: forgotData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === 'success') {
            toggleForms('login');
        }
    })
    .catch(error => console.error('Error:', error));
});

function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem("cart")) || []; // Get cart from localStorage
    let cartCountElement = document.getElementById("cart-count");

    if (cartCountElement) {
        cartCountElement.textContent = cart.length; // Update the cart count
    }
}


function loadCartItems() {
    if (isCartLoading) return; // Prevent multiple calls
    isCartLoading = true;

    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    $('#cart-items').html('');
    console.log("Loading cart items: ", cart);

    if (cart.length > 0) {
        let totalCartValue = 0;
        let fetchPromises = [];

        cart.forEach(item => {
            const productId = item.product_id;
            const dates = item.dates;

            fetchPromises.push(
                fetch(`cart.php?fetch_product_details&id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.product) {
                            const product = data.product;
                            const [startDate, endDate] = dates.split(' - ');
                            const rentDays = (new Date(endDate) - new Date(startDate)) / (1000 * 60 * 60 * 24);
                            const rent = product.price_per_day * rentDays;
                            totalCartValue += rent;

                            $('#cart-items').append(`
                                <div class="cart-item">
                                    <img src="${product.image_url}" alt="${product.name}" class="cart-item-image">
                                    <div class="cart-item-details">
                                        <p>${product.name} - ${dates}</p>
                                        <p>Price per day: ₹${product.price_per_day}</p>
                                        <p>Total Rent: ₹${rent}</p>
                                    </div>
                                    <button class="delete-cart-item" data-product-id="${item.product_id}" data-dates="${dates}">
                                    <ion-icon name="trash-outline"></ion-icon>
                                    </button>

                                </div>
                            `);
                        }
                    })
            );
        });

        Promise.all(fetchPromises).then(() => {
            $('#cart-items').append(`<p class="cart-total">Total Cart Value: ₹${totalCartValue}</p>`);
            isCartLoading = false; // Reset loading flag
        });
    } else {
        $('#cart-items').html('<p>Nothing to see here</p>');
        isCartLoading = false;
    }
    // Global event listener for delete buttons
document.addEventListener('click', function (e) {
    const deleteBtn = e.target.closest('.delete-cart-item');
    if (deleteBtn) {
        const productId = deleteBtn.getAttribute('data-product-id');
        const dates = deleteBtn.getAttribute('data-dates');  // Fetch date range
        removeFromCart(productId, dates);  // Pass both productId and dates
    }
});

}


// Function to add item to localStorage

function addToCart(productId, selectedDates) {
    // Check if cart already exists in localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Check for duplicates
    const isDuplicate = cart.some(item => item.product_id === productId);

    if (isDuplicate) {
        alert('This product is already in your cart.');
        return;
    }

    // Add the new item to the cart
    cart.push({ product_id: productId, dates: selectedDates });

    // Save updated cart to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));

    alert('Product added to cart!');
}    

// Remove item from the cart
function removeFromCart(productId, dates) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    console.log("Current Cart:", cart);

    // Ensure both productId and dates are consistent for comparison
    const updatedCart = cart.filter(item => {
        const isSameProduct = item.product_id.toString() === productId.toString();
        const isSameDate = (item.dates || "").trim() === (dates || "").trim();
        return !(isSameProduct && isSameDate);  // Remove only if both match
    });

    if (cart.length === updatedCart.length) {
        console.log("Product not found in cart.");
    } else {
        console.log("Product removed successfully.");
    }

    // Update localStorage after removal
    localStorage.setItem('cart', JSON.stringify(updatedCart));

    // Reload cart items and update count
    loadCartItems();
    updateCartCount();
}






