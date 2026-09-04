<?php

require_once "database/db.php";


// Get events from database

$events = $conn->query(
    "SELECT
        event_id,
        event_name,
        event_type,
        event_date,
        start_time,
        end_time,
        venue,
        description
     FROM events
     ORDER BY event_date ASC, start_time ASC"
);

?>


<?php include 'includes/header.php'; ?>
<?php include 'includes/navigationbar.php'; ?>


<!-- ================================================= -->
<!-- PAGE HEADER -->
<!-- ================================================= -->

<section class="bg-primary text-white py-5">

    <div class="container">

        <h1>Events</h1>

        <p class="lead mb-0">
            Discover upcoming tennis events, tournaments,
            training programmes and community activities.
        </p>

    </div>

</section>


<!-- ================================================= -->
<!-- EVENTS -->
<!-- ================================================= -->

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2>Upcoming Tennis Events</h2>

            <p class="text-muted">

                Stay up to date with activities happening
                through Mogale Tennis Association.

            </p>

        </div>


        <div class="row g-4">


            <?php if ($events && $events->num_rows > 0): ?>


                <?php while ($event = $events->fetch_assoc()): ?>


                    <div class="col-md-6 col-lg-4">


                        <div class="card h-100 shadow-sm">


                            <!-- EVENT TYPE -->

                            <div class="card-header bg-primary text-white">

                                <strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $event["event_type"]
                                    );

                                    ?>

                                </strong>

                            </div>


                            <div class="card-body">


                                <h4 class="card-title">

                                    <?php

                                    echo htmlspecialchars(
                                        $event["event_name"]
                                    );

                                    ?>

                                </h4>


                                <hr>


                                <!-- DATE -->

                                <p>

                                    <i class="bi bi-calendar-event"></i>

                                    <strong>
                                        Date:
                                    </strong>

                                    <br>

                                    <?php

                                    echo date(
                                        "d F Y",
                                        strtotime(
                                            $event["event_date"]
                                        )
                                    );

                                    ?>

                                </p>


                                <!-- TIME -->

                                <p>

                                    <i class="bi bi-clock"></i>

                                    <strong>
                                        Time:
                                    </strong>

                                    <br>

                                    <?php

                                    echo date(
                                        "H:i",
                                        strtotime(
                                            $event["start_time"]
                                        )
                                    );

                                    ?>

                                    -

                                    <?php

                                    echo date(
                                        "H:i",
                                        strtotime(
                                            $event["end_time"]
                                        )
                                    );

                                    ?>

                                </p>


                                <!-- VENUE -->

                                <p>

                                    <i class="bi bi-geo-alt"></i>

                                    <strong>
                                        Venue:
                                    </strong>

                                    <br>

                                    <?php

                                    echo htmlspecialchars(
                                        $event["venue"]
                                    );

                                    ?>

                                </p>


                                <!-- DESCRIPTION -->

                                <p class="text-muted">

                                    <?php

                                    echo nl2br(
                                        htmlspecialchars(
                                            $event["description"]
                                        )
                                    );

                                    ?>

                                </p>


                            </div>


                            <div class="card-footer bg-white">

                                <a
                                    href="contact.php"
                                    class="btn btn-outline-primary w-100"
                                >

                                    Contact Us About This Event

                                </a>

                            </div>


                        </div>

                    </div>


                <?php endwhile; ?>


            <?php else: ?>


                <div class="col-12">

                    <div class="alert alert-info text-center">

                        <h4>No upcoming events</h4>

                        <p class="mb-0">

                            Please check back soon for upcoming
                            Mogale Tennis Association activities.

                        </p>

                    </div>

                </div>


            <?php endif; ?>


        </div>

    </div>

</section>


<!-- ================================================= -->
<!-- CALL TO ACTION -->
<!-- ================================================= -->

<section class="bg-light py-5">

    <div class="container text-center">

        <h2>Want to Take Part?</h2>

        <p class="lead">

            Contact Mogale Tennis Association for more
            information about our events and programmes.

        </p>


        <a
            href="contact.php"
            class="btn btn-primary"
        >

            Contact Mogale Tennis

        </a>

    </div>

</section>


<?php include 'includes/footer.php'; ?>