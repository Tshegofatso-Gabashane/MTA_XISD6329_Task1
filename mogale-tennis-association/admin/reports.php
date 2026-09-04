<?php
require_once "admin-check.php";
require_once "../database/db.php";


// Total members

$member_query = $conn->query(
    "SELECT COUNT(*) AS total FROM members"
);

$member_data = $member_query->fetch_assoc();


// Total coaches

$coach_query = $conn->query(
    "SELECT COUNT(*) AS total FROM coaches"
);

$coach_data = $coach_query->fetch_assoc();


// Total events

$event_query = $conn->query(
    "SELECT COUNT(*) AS total FROM events"
);

$event_data = $event_query->fetch_assoc();


// Total facilities

$facility_query = $conn->query(
    "SELECT COUNT(*) AS total FROM complexes"
);

$facility_data = $facility_query->fetch_assoc();


// Total payments

$payment_query = $conn->query(
    "SELECT
        COUNT(*) AS total_payments,
        COALESCE(SUM(amount), 0) AS total_amount
     FROM payments
     WHERE payment_status = 'Paid'"
);

$payment_data = $payment_query->fetch_assoc();


// Membership breakdown

$membership_query = $conn->query(
    "SELECT membership_type, COUNT(*) AS total
     FROM members
     GROUP BY membership_type
     ORDER BY total DESC"
);


// Payment status breakdown

$status_query = $conn->query(
    "SELECT payment_status, COUNT(*) AS total
     FROM payments
     GROUP BY payment_status"
);

?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/admin-navigationbar.php'; ?>


<section class="bg-primary text-white py-5">

    <div class="container">

        <h1>Reports</h1>

        <p class="lead">
            Management information and statistics for
            Mogale Tennis Association.
        </p>

    </div>

</section>


<section class="py-5">

    <div class="container">

        <h2 class="mb-4">
            Association Overview
        </h2>


        <!-- Summary cards -->

        <div class="row g-4 mb-5">


            <!-- Members -->

            <div class="col-md-6 col-lg-3">

                <div class="card shadow h-100">

                    <div class="card-body text-center">

                        <i
                            class="bi bi-people fs-1"
                        ></i>

                        <h2 class="mt-3">

                            <?php
                            echo $member_data["total"];
                            ?>

                        </h2>

                        <p class="text-muted mb-0">
                            Registered Members
                        </p>

                    </div>

                </div>

            </div>


            <!-- Coaches -->

            <div class="col-md-6 col-lg-3">

                <div class="card shadow h-100">

                    <div class="card-body text-center">

                        <i
                            class="bi bi-person-badge fs-1"
                        ></i>

                        <h2 class="mt-3">

                            <?php
                            echo $coach_data["total"];
                            ?>

                        </h2>

                        <p class="text-muted mb-0">
                            Coaches
                        </p>

                    </div>

                </div>

            </div>


            <!-- Events -->

            <div class="col-md-6 col-lg-3">

                <div class="card shadow h-100">

                    <div class="card-body text-center">

                        <i
                            class="bi bi-calendar-event fs-1"
                        ></i>

                        <h2 class="mt-3">

                            <?php
                            echo $event_data["total"];
                            ?>

                        </h2>

                        <p class="text-muted mb-0">
                            Events
                        </p>

                    </div>

                </div>

            </div>


            <!-- Facilities -->

            <div class="col-md-6 col-lg-3">

                <div class="card shadow h-100">

                    <div class="card-body text-center">

                        <i
                            class="bi bi-building fs-1"
                        ></i>

                        <h2 class="mt-3">

                            <?php
                            echo $facility_data["total"];
                            ?>

                        </h2>

                        <p class="text-muted mb-0">
                            Facilities
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <!-- Payment summary -->

        <div class="card shadow mb-5">

            <div class="card-body">

                <h3 class="mb-4">
                    Payment Summary
                </h3>

                <div class="row">

                    <div class="col-md-6">

                        <h4>

                            R<?php

                            echo number_format(
                                $payment_data["total_amount"],
                                2
                            );

                            ?>

                        </h4>

                        <p class="text-muted">
                            Total paid membership fees
                        </p>

                    </div>


                    <div class="col-md-6">

                        <h4>

                            <?php

                            echo $payment_data["total_payments"];

                            ?>

                        </h4>

                        <p class="text-muted">
                            Paid transactions
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <div class="row g-4">


            <!-- Membership report -->

            <div class="col-lg-6">

                <div class="card shadow h-100">

                    <div class="card-body">

                        <h3 class="mb-4">
                            Members by Membership Type
                        </h3>


                        <?php if (
                            $membership_query->num_rows > 0
                        ): ?>

                            <div class="table-responsive">

                                <table class="table">

                                    <thead>

                                        <tr>

                                            <th>
                                                Membership Type
                                            </th>

                                            <th>
                                                Members
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                    <?php while (
                                        $membership =
                                        $membership_query->fetch_assoc()
                                    ): ?>

                                        <tr>

                                            <td>

                                                <?php

                                                echo htmlspecialchars(
                                                    $membership[
                                                        "membership_type"
                                                    ]
                                                );

                                                ?>

                                            </td>

                                            <td>

                                                <?php

                                                echo $membership[
                                                    "total"
                                                ];

                                                ?>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                    </tbody>

                                </table>

                            </div>

                        <?php else: ?>

                            <p class="text-muted">
                                No membership data available.
                            </p>

                        <?php endif; ?>

                    </div>

                </div>

            </div>


            <!-- Payment status -->

            <div class="col-lg-6">

                <div class="card shadow h-100">

                    <div class="card-body">

                        <h3 class="mb-4">
                            Payment Status
                        </h3>


                        <?php if (
                            $status_query->num_rows > 0
                        ): ?>

                            <div class="table-responsive">

                                <table class="table">

                                    <thead>

                                        <tr>

                                            <th>
                                                Status
                                            </th>

                                            <th>
                                                Number
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                    <?php while (
                                        $status =
                                        $status_query->fetch_assoc()
                                    ): ?>

                                        <tr>

                                            <td>

                                                <?php

                                                echo htmlspecialchars(
                                                    $status[
                                                        "payment_status"
                                                    ]
                                                );

                                                ?>

                                            </td>

                                            <td>

                                                <?php

                                                echo $status[
                                                    "total"
                                                ];

                                                ?>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                    </tbody>

                                </table>

                            </div>

                        <?php else: ?>

                            <p class="text-muted">
                                No payment data available.
                            </p>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<?php include '../includes/footer.php'; ?>