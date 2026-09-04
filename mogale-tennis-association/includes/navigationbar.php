<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<nav class="navbar navbar-expand-lg navbar-dark shadow sticky-top">

    <div class="container">

        <a class="navbar-brand fw-bold" href="home.php">

            <img
                src="assests/images/MTA logo.jpg"
                alt="Mogale Tennis Association Logo"
                height="50"
                class="me-2"
            >

            Mogale Tennis Association

        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="/mogale-tennis-association/home.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/mogale-tennis-association/about.php">
                        About
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/mogale-tennis-association/membership.php">
                        Membership
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/mogale-tennis-association/coaches.php">
                        Coaches
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/mogale-tennis-association/events.php">
                        Events
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/mogale-tennis-association/complexes.php">
                        Facilities
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/mogale-tennis-association/contact.php">
                        Contact
                    </a>
                </li>

                <?php if (isset($_SESSION["member_id"])): ?>

    <li class="nav-item ms-lg-3">

        <a
            class="nav-link"
            href="/mogale-tennis-association/member_dashboard.php"
        >

            Welcome,
            <?php
            echo htmlspecialchars(
                $_SESSION["member_name"]
            );
            ?>

        </a>

    </li>


    <li class="nav-item">

        <a
            class="btn btn-warning"
            href="/mogale-tennis-association/logout.php"
        >

            Logout

        </a>

    </li>


<?php else: ?>

    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">

        <a
            class="btn btn-warning"
            href="/mogale-tennis-association/login.php"
        >

            Login

        </a>

    </li>

<?php endif; ?>

            </ul>

        </div>

    </div>

</nav>