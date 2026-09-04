<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a
            class="navbar-brand fw-bold"
            href="/mogale-tennis-association/admin/dashboard.php"
        >
            MTA Admin
        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#adminNav"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="adminNav"
        >

            <ul class="navbar-nav me-auto">

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/mogale-tennis-association/admin/dashboard.php"
                    >
                        Dashboard
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/mogale-tennis-association/admin/members.php"
                    >
                        Members
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/mogale-tennis-association/admin/coaches.php"
                    >
                        Coaches
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/mogale-tennis-association/admin/events.php"
                    >
                        Events
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/mogale-tennis-association/admin/complexes.php"
                    >
                        Facilities
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/mogale-tennis-association/admin/payments.php"
                    >
                        Payments
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="/mogale-tennis-association/admin/messages.php"
                    >
                        Messages
                    </a>

                </li>

            </ul>


            <span class="navbar-text me-3">

                <?php

                echo htmlspecialchars(
                    $_SESSION["admin_name"] ?? "Administrator"
                );

                ?>

            </span>


            <a
                href="/mogale-tennis-association/admin/logout.php"
                class="btn btn-warning btn-sm"
            >

                Logout

            </a>

        </div>

    </div>

</nav>