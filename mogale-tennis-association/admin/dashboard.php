<?php
require_once "admin-check.php";
require_once "../database/db.php";


// =====================================================
// GET DASHBOARD COUNTS
// =====================================================

$members_count = 0;
$coaches_count = 0;
$events_count = 0;
$facilities_count = 0;
$payments_count = 0;
$messages_count = 0;


// Members

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM members"
);

if ($result) {

    $row = $result->fetch_assoc();

    $members_count = $row["total"];

}


// Coaches

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM coaches"
);

if ($result) {

    $row = $result->fetch_assoc();

    $coaches_count = $row["total"];

}


// Events

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM events"
);

if ($result) {

    $row = $result->fetch_assoc();

    $events_count = $row["total"];

}


// Facilities

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM complexes"
);

if ($result) {

    $row = $result->fetch_assoc();

    $facilities_count = $row["total"];

}


// Payments

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM payments"
);

if ($result) {

    $row = $result->fetch_assoc();

    $payments_count = $row["total"];

}


// Messages

$result = $conn->query(
    "SELECT COUNT(*) AS total FROM messages"
);

if ($result) {

    $row = $result->fetch_assoc();

    $messages_count = $row["total"];

}


// =====================================================
// RECENT MESSAGES
// =====================================================

$recent_messages = $conn->query(
    "SELECT
        message_id,
        name,
        email,
        subject,
        created_at
     FROM messages
     ORDER BY created_at DESC
     LIMIT 5"
);

?>


<?php include '../includes/header.php'; ?>
<?php include '../includes/admin-navigationbar.php'; ?>



<!-- ================================================= -->
<!-- DASHBOARD HEADER -->
<!-- ================================================= -->

<section class="bg-primary text-white py-5">

    <div class="container">

        <h1>Admin Dashboard</h1>

        <p class="lead mb-0">

            Manage the Mogale Tennis Association
            website and member information.

        </p>

    </div>

</section>


<!-- ================================================= -->
<!-- DASHBOARD -->
<!-- ================================================= -->

<section class="py-5">

    <div class="container">


        <!-- ================================================= -->
        <!-- STATISTICS -->
        <!-- ================================================= -->

        <div class="row g-4 mb-5">


            <!-- MEMBERS -->

            <div class="col-md-6 col-lg-4">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div
                            class="d-flex justify-content-between
                            align-items-center"
                        >

                            <div>

                                <h6 class="text-muted">
                                    Members
                                </h6>

                                <h2>
                                    <?php echo $members_count; ?>
                                </h2>

                            </div>

                            <i
                                class="bi bi-people-fill text-primary"
                                style="font-size: 40px;"
                            ></i>

                        </div>


                        <a
                            href="members.php"
                            class="btn btn-outline-primary btn-sm mt-3"
                        >

                            Manage Members

                        </a>

                    </div>

                </div>

            </div>


            <!-- COACHES -->

            <div class="col-md-6 col-lg-4">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div
                            class="d-flex justify-content-between
                            align-items-center"
                        >

                            <div>

                                <h6 class="text-muted">
                                    Coaches
                                </h6>

                                <h2>
                                    <?php echo $coaches_count; ?>
                                </h2>

                            </div>

                            <i
                                class="bi bi-person-badge-fill text-primary"
                                style="font-size: 40px;"
                            ></i>

                        </div>


                        <a
                            href="coaches.php"
                            class="btn btn-outline-primary btn-sm mt-3"
                        >

                            Manage Coaches

                        </a>

                    </div>

                </div>

            </div>


            <!-- EVENTS -->

            <div class="col-md-6 col-lg-4">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div
                            class="d-flex justify-content-between
                            align-items-center"
                        >

                            <div>

                                <h6 class="text-muted">
                                    Events
                                </h6>

                                <h2>
                                    <?php echo $events_count; ?>
                                </h2>

                            </div>

                            <i
                                class="bi bi-calendar-event-fill text-primary"
                                style="font-size: 40px;"
                            ></i>

                        </div>


                        <a
                            href="events.php"
                            class="btn btn-outline-primary btn-sm mt-3"
                        >

                            Manage Events

                        </a>

                    </div>

                </div>

            </div>


            <!-- FACILITIES -->

            <div class="col-md-6 col-lg-4">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div
                            class="d-flex justify-content-between
                            align-items-center"
                        >

                            <div>

                                <h6 class="text-muted">
                                    Facilities
                                </h6>

                                <h2>
                                    <?php echo $facilities_count; ?>
                                </h2>

                            </div>

                            <i
                                class="bi bi-building-fill text-primary"
                                style="font-size: 40px;"
                            ></i>

                        </div>


                        <a
                            href="complexes.php"
                            class="btn btn-outline-primary btn-sm mt-3"
                        >

                            Manage Facilities

                        </a>

                    </div>

                </div>

            </div>


            <!-- PAYMENTS -->

            <div class="col-md-6 col-lg-4">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div
                            class="d-flex justify-content-between
                            align-items-center"
                        >

                            <div>

                                <h6 class="text-muted">
                                    Payments
                                </h6>

                                <h2>
                                    <?php echo $payments_count; ?>
                                </h2>

                            </div>

                            <i
                                class="bi bi-credit-card-fill text-primary"
                                style="font-size: 40px;"
                            ></i>

                        </div>


                        <a
                            href="payments.php"
                            class="btn btn-outline-primary btn-sm mt-3"
                        >

                            Manage Payments

                        </a>

                    </div>

                </div>

            </div>


            <!-- MESSAGES -->

            <div class="col-md-6 col-lg-4">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <div
                            class="d-flex justify-content-between
                            align-items-center"
                        >

                            <div>

                                <h6 class="text-muted">
                                    Messages
                                </h6>

                                <h2>
                                    <?php echo $messages_count; ?>
                                </h2>

                            </div>

                            <i
                                class="bi bi-envelope-fill text-primary"
                                style="font-size: 40px;"
                            ></i>

                        </div>


                        <a
                            href="messages.php"
                            class="btn btn-outline-primary btn-sm mt-3"
                        >

                            View Messages

                        </a>

                    </div>

                </div>

            </div>


        </div>


        <!-- ================================================= -->
        <!-- RECENT MESSAGES -->
        <!-- ================================================= -->

        <div class="card shadow-sm">


            <div class="card-header">

                <div
                    class="d-flex justify-content-between
                    align-items-center"
                >

                    <h3 class="mb-0">
                        Recent Messages
                    </h3>


                    <a
                        href="messages.php"
                        class="btn btn-primary btn-sm"
                    >

                        View All

                    </a>

                </div>

            </div>


            <div class="card-body">


                <?php if (
                    $recent_messages
                    && $recent_messages->num_rows > 0
                ): ?>


                    <div class="table-responsive">

                        <table class="table table-hover">

                            <thead>

                                <tr>

                                    <th>Name</th>

                                    <th>Email</th>

                                    <th>Subject</th>

                                    <th>Date</th>

                                </tr>

                            </thead>


                            <tbody>


                                <?php while (
                                    $msg =
                                    $recent_messages->fetch_assoc()
                                ): ?>


                                    <tr>

                                        <td>

                                            <?php

                                            echo htmlspecialchars(
                                                $msg["name"]
                                            );

                                            ?>

                                        </td>


                                        <td>

                                            <?php

                                            echo htmlspecialchars(
                                                $msg["email"]
                                            );

                                            ?>

                                        </td>


                                        <td>

                                            <?php

                                            echo htmlspecialchars(
                                                $msg["subject"]
                                            );

                                            ?>

                                        </td>


                                        <td>

                                            <?php

                                            echo date(
                                                "d M Y H:i",
                                                strtotime(
                                                    $msg["created_at"]
                                                )
                                            );

                                            ?>

                                        </td>

                                    </tr>


                                <?php endwhile; ?>


                            </tbody>

                        </table>

                    </div>


                <?php else: ?>


                    <div class="text-center py-4">

                        <p class="text-muted mb-0">

                            No contact messages have been
                            received yet.

                        </p>

                    </div>


                <?php endif; ?>


            </div>

        </div>


    </div>

</section>


<?php include '../includes/footer.php'; ?>