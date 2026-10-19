/* ================= MOBILE MENU ================= */

function toggleMenu() {

    const nav = document.getElementById("navLinks");

    nav.classList.toggle("active");

}


/* ================= CLOSE MENU ================= */

document.querySelectorAll(".nav-links a").forEach(function(link) {

    link.addEventListener("click", function() {

        document
            .getElementById("navLinks")
            .classList.remove("active");

    });

});


/* ================= CONTACT FORM ================= */

document
    .getElementById("contactForm")
    .addEventListener("submit", function(event) {

        event.preventDefault();

        alert(
            "Thank you for contacting Mogale Tennis Association! " +
            "We will get back to you soon."
        );

        this.reset();

    });
    // =========================================
// LOGIN FUNCTION
// =========================================

const loginForm = document.getElementById("loginForm");

if (loginForm) {

    loginForm.addEventListener("submit", function(event) {

        event.preventDefault();

        const email = document.getElementById("loginEmail").value.trim();
        const password = document.getElementById("loginPassword").value.trim();

        // Check if fields are empty
        if (email === "" || password === "") {

            alert("Please enter your email and password.");

            return;
        }

        // Demo login details
        const correctEmail = "admin@mogaletennis.co.za";
        const correctPassword = "12345";

        // Check login details
        if (email === correctEmail && password === correctPassword) {

            alert(
                "Hello Admin! 🎾\n\n" +
                "Congratulations on signing in successfully!\n\n" +
                "Welcome to Mogale Tennis Association."
            );

            localStorage.setItem("loggedIn", "true");

            window.location.href = "index.html";

        } else {

            alert(
                "Incorrect email or password.\n\n" +
                "Please try again."
            );

        }

    });

}