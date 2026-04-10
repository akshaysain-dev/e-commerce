// address.js (or whatever your file is)

function toggleNewAddress(isNew) {
    const field = document.getElementById('new-address-field');
    const textarea = document.getElementById('new_address_textarea');
    
    if (isNew) {
        field.style.display = 'block';
        textarea.setAttribute('required', 'required');
        textarea.focus();
    } else {
        field.style.display = 'none';
        textarea.removeAttribute('required');
        textarea.value = '';
    }
}

function selectSavedAddress() {
    document.getElementById('useSaved').click();
}

function selectNewAddress() {
    document.getElementById('addNew').click();
}

function updateFormAction(method) {
    const submitBtn = document.querySelector('#checkout-form button[type="submit"]');
    
    submitBtn.classList.remove('btn-primary', 'btn-success', 'btn-info');

    if (method === 'STRIPE') {
        submitBtn.innerText = 'Pay Now with Stripe';
        submitBtn.classList.add('btn-success');
    } else if (method === 'PAYPAL') {
        submitBtn.innerText = 'Pay Now with PayPal';
        submitBtn.classList.add('btn-info');
    } else if (method === 'RAZORPAY') {
        submitBtn.innerText = 'Pay Now with RAZORPAY';
        submitBtn.classList.add('btn-info');
    } else {
        submitBtn.innerText = 'Place Order Now';
        submitBtn.classList.add('btn-primary');
    }
}

// ✅ Expose to global scope so inline onclick="" can reach them
window.toggleNewAddress = toggleNewAddress;
window.selectSavedAddress = selectSavedAddress;
window.selectNewAddress = selectNewAddress;
window.updateFormAction = updateFormAction;