// Create a Stripe client
const stripe = Stripe("pk_test_51NUlWFHOSHSSNQyw0WymDQenoovMjwufARlE79AJ2GRGha3iK1UlnyeNWaPoBN5aP8MIgQUcrh6RwGteCwqG4Hug00IluluCkZ");

// Create an instance of Elements
var elements = stripe.elements(client_secret);

const paymentElementOptions = {
    layout: "tabs",
};

const linkAuthenticationElement = elements.create("linkAuthentication");
linkAuthenticationElement.mount("#link-authentication-element");

const paymentElement = elements.create("payment", paymentElementOptions);
paymentElement.mount("#payment-element");

document.getElementById("submit").addEventListener("click", async function (event) {

    event.preventDefault();
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);

    const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: "http://ezwallet.test/user/payment-success?amount="+params.get('amount'),
            receipt_email: "bonghjack@gmail.com",
        },
    });

    if (error.type === "card_error" || error.type === "validation_error") {
        showMessage(error.message);
    } else {
        showMessage("An unexpected error occurred.");
    }

    setLoading(false);
});