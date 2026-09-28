document.addEventListener("DOMContentLoaded", function () {
	// Stop if the registration form is not on this page.
	var form = document.getElementById("registrationForm");

	if (!form) {
		return;
	}

	form.addEventListener("submit", function (event) {
		// Check every required field before submitting.
		var isValid = true;
		var emailRegex = /^[^\s@]+@[^\s@]+.[^\s@]+$/;
		var phoneRegex = /^[0-9+\-\s]{7,15}$/;
		var passwordRegex = /^(?=.*[0-9]).{8,}$/;

		clearErrors();

		var name = getValue("customer_name");
		var email = getValue("customer_email");
		var password = getValue("customer_pass");
		var country = getValue("customer_country");
		var city = getValue("customer_city");
		var contact = getValue("customer_contact");
		var address = getValue("customer_address");

		if (name === "") {
			showError("customer_name", "Full name is required.");
			isValid = false;
		}

		if (email === "") {
			showError("customer_email", "Email is required.");
			isValid = false;
		} else if (!emailRegex.test(email)) {
			showError("customer_email", "Enter a valid email address.");
			isValid = false;
		}

		if (password === "") {
			showError("customer_pass", "Password is required.");
			isValid = false;
		} else if (!passwordRegex.test(password)) {
			showError("customer_pass", "Use at least 8 characters and one digit.");
			isValid = false;
		}

		if (country === "") {
			showError("customer_country", "Country is required.");
			isValid = false;
		}

		if (city === "") {
			showError("customer_city", "City is required.");
			isValid = false;
		}

		if (contact === "") {
			showError("customer_contact", "Contact number is required.");
			isValid = false;
		} else if (!phoneRegex.test(contact)) {
			showError("customer_contact", "Enter a valid contact number.");
			isValid = false;
		}

		if (address === "") {
			showError("customer_address", "Address is required.");
			isValid = false;
		}

		if (!isValid) {
			event.preventDefault();
		}
	});
});

function getValue(id) {
	// Read a field and remove extra spaces.
	return document.getElementById(id).value.trim();
}

function showError(id, message) {
	// Show an error next to a field.
	document.getElementById(id + "_error").textContent = message;
}

function clearErrors() {
	// Remove old errors before checking the form again.
	var errors = document.querySelectorAll(".field-error");

	for (var i = 0; i < errors.length; i++) {
		errors[i].textContent = "";
	}
}
