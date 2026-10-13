/* Mogale Tennis Association - shared page interactions */
document.addEventListener("DOMContentLoaded", function () {
    const nav = document.getElementById("navLinks");

    // Mobile navigation: safely do nothing on pages without this menu.
    window.toggleMenu = function () {
        if (nav) nav.classList.toggle("active");
    };

    if (nav) {
        nav.querySelectorAll("a").forEach(function (link) {
            link.addEventListener("click", function () {
                nav.classList.remove("active");
            });
        });
    }

    // Front-end demonstration only: this form does not send data to a server.
    const contactForm = document.getElementById("contactForm");
    if (contactForm) {
        contactForm.addEventListener("submit", function (event) {
            event.preventDefault();
            alert("Thank you for contacting Mogale Tennis Association! This demo form has received your submission, but it does not send an email yet.");
            contactForm.reset();
        });
    }

    // Demo login only. Do not use these credentials for a real account.
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();
            const emailField = document.getElementById("loginEmail");
            const passwordField = document.getElementById("loginPassword");
            if (!emailField || !passwordField) {
                alert("The login form is missing a required field. Please refresh the page.");
                return;
            }

            const email = emailField.value.trim();
            const password = passwordField.value;
            if (!email || !password) {
                alert("Please enter your email and password.");
                return;
            }

            if (email === "admin@mogaletennis.co.za" && password === "12345") {
                localStorage.setItem("loggedIn", "true");
                alert("Demo sign-in successful. Welcome to Mogale Tennis Association!");
                window.location.href = "index.html";
            } else {
                alert("Incorrect demo email or password. Please try again.");
            }
        });
    }
});
