<?php

require_once "database/db.php";


// Get all coaches from the database

$coaches = $conn->query(
    "SELECT
        coach_id,
        first_name,
        last_name,
        email,
        phone,
        qualification,
        specialization,
        experience_years
     FROM coaches
     ORDER BY first_name ASC, last_name ASC"
);

?>


<?php include 'includes/header.php'; ?>
<?php include 'includes/navigationbar.php'; ?>


<!-- ================================================= -->
<!-- PAGE HEADER -->
<!-- ================================================= -->

<section class="bg-primary text-white py-5">

    <div class="container">

        <h1>Our Coaches</h1>

        <p class="lead mb-0">
            Meet the coaches supporting tennis development
            in the Mogale community.
        </p>

    </div>

</section>


<!-- ================================================= -->
<!-- COACHES -->
<!-- ================================================= -->

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2>Meet Our Coaching Team</h2>

            <p class="text-muted">

                Our coaches help players develop their tennis
                skills, confidence and understanding of the game.

            </p>

        </div>


        <div class="row g-4">


            <?php if ($coaches && $coaches->num_rows > 0): ?>


                <?php while ($coach = $coaches->fetch_assoc()): ?>


                    <div class="col-md-6 col-lg-4">


                        <div class="card h-100 shadow-sm">


                            <!-- COACH IMAGE -->

                            <div
                                class="d-flex justify-content-center align-items-center bg-light"
                                style="height: 220px;"
                            >

                                <div
                                    class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                                    style="width: 130px; height: 130px; font-size: 45px;"
                                >

                                    <?php

                                    echo strtoupper(
                                        substr(
                                            $coach["first_name"],
                                            0,
                                            1
                                        )
                                    );

                                    echo strtoupper(
                                        substr(
                                            $coach["last_name"],
                                            0,
                                            1
                                        )
                                    );

                                    ?>

                                </div>

                            </div>


                            <!-- COACH INFORMATION -->

                            <div class="card-body text-center">


                                <h4 class="card-title">

                                    <?php

                                    echo htmlspecialchars(
                                        $coach["first_name"]
                                    );

                                    ?>

                                    <?php

                                    echo htmlspecialchars(
                                        $coach["last_name"]
                                    );

                                    ?>

                                </h4>


                                <p class="text-primary fw-bold">

                                    <?php

                                    echo htmlspecialchars(
                                        $coach["specialization"]
                                    );

                                    ?>

                                </p>


                                <hr>


                                <p>

                                    <strong>
                                        Qualification:
                                    </strong>

                                    <br>

                                    <?php

                                    echo htmlspecialchars(
                                        $coach["qualification"]
                                    );

                                    ?>

                                </p>


                                <p>

                                    <strong>
                                        Experience:
                                    </strong>

                                    <br>

                                    <?php

                                    echo htmlspecialchars(
                                        $coach["experience_years"]
                                    );

                                    ?>

                                    years

                                </p>


                                <p>

                                    <i class="bi bi-envelope"></i>

                                    <?php

                                    echo htmlspecialchars(
                                        $coach["email"]
                                    );

                                    ?>

                                </p>


                                <p>

                                    <i class="bi bi-telephone"></i>

                                    <?php

                                    echo htmlspecialchars(
                                        $coach["phone"]
                                    );

                                    ?>

                                </p>


                            </div>

                        </div>

                    </div>


                <?php endwhile; ?>


            <?php else: ?>


                <!-- NO COACHES -->

                <div class="col-12">

                    <div class="alert alert-info text-center">

                        <h4>No coaches available</h4>

                        <p class="mb-0">

                            Our coaching team information
                            will be available soon.

                        </p>

                    </div>

                </div>


            <?php endif; ?>


        </div>

    </div>

</section>


<!-- ================================================= -->
<!-- JOIN / CONTACT CTA -->
<!-- ================================================= -->

<section class="bg-light py-5">

    <div class="container text-center">

        <h2>Interested in Tennis Coaching?</h2>

        <p class="lead">

            Contact Mogale Tennis Association to learn more
            about our coaching programmes.

        </p>


        <a
            href="contact.php"
            class="btn btn-primary"
        >

            Contact Us

        </a>

    </div>

</section>


<?php include 'includes/footer.php'; ?>