<?php

require_once "database/db.php";


// Get tennis complexes from database

$complexes = $conn->query(
    "SELECT
        complex_id,
        complex_name,
        location,
        number_of_courts,
        court_type,
        facilities,
        contact_phone,
        description
     FROM complexes
     ORDER BY complex_name ASC"
);

?>


<?php include 'includes/header.php'; ?>
<?php include 'includes/navigationbar.php'; ?>


<!-- ================================================= -->
<!-- PAGE HEADER -->
<!-- ================================================= -->

<section class="bg-primary text-white py-5">

    <div class="container">

        <h1>Our Facilities</h1>

        <p class="lead mb-0">

            Explore tennis facilities available through
            Mogale Tennis Association.

        </p>

    </div>

</section>


<!-- ================================================= -->
<!-- FACILITIES -->
<!-- ================================================= -->

<section class="py-5">

    <div class="container">


        <div class="text-center mb-5">

            <h2>Tennis Facilities</h2>

            <p class="text-muted">

                Find information about our tennis complexes,
                courts and available facilities.

            </p>

        </div>


        <div class="row g-4">


            <?php if ($complexes && $complexes->num_rows > 0): ?>


                <?php while ($complex = $complexes->fetch_assoc()): ?>


                    <div class="col-md-6 col-lg-4">


                        <div class="card h-100 shadow-sm">


                            <!-- FACILITY HEADER -->

                            <div class="card-header bg-primary text-white">

                                <h4 class="mb-0">

                                    <?php

                                    echo htmlspecialchars(
                                        $complex["complex_name"]
                                    );

                                    ?>

                                </h4>

                            </div>


                            <div class="card-body">


                                <!-- LOCATION -->

                                <p>

                                    <i class="bi bi-geo-alt"></i>

                                    <strong>
                                        Location:
                                    </strong>

                                    <br>

                                    <?php

                                    echo htmlspecialchars(
                                        $complex["location"]
                                    );

                                    ?>

                                </p>


                                <hr>


                                <!-- NUMBER OF COURTS -->

                                <p>

                                    <i class="bi bi-grid-3x3-gap"></i>

                                    <strong>
                                        Number of Courts:
                                    </strong>

                                    <br>

                                    <?php

                                    echo htmlspecialchars(
                                        $complex["number_of_courts"]
                                    );

                                    ?>

                                </p>


                                <!-- COURT TYPE -->

                                <p>

                                    <i class="bi bi-circle"></i>

                                    <strong>
                                        Court Type:
                                    </strong>

                                    <br>

                                    <?php

                                    echo htmlspecialchars(
                                        $complex["court_type"]
                                    );

                                    ?>

                                </p>


                                <!-- FACILITIES -->

                                <p>

                                    <i class="bi bi-building"></i>

                                    <strong>
                                        Available Facilities:
                                    </strong>

                                </p>


                                <div class="mb-3">

                                    <?php

                                    echo nl2br(
                                        htmlspecialchars(
                                            $complex["facilities"]
                                        )
                                    );

                                    ?>

                                </div>


                                <!-- DESCRIPTION -->

                                <p>

                                    <strong>
                                        About the Facility:
                                    </strong>

                                </p>


                                <p class="text-muted">

                                    <?php

                                    echo nl2br(
                                        htmlspecialchars(
                                            $complex["description"]
                                        )
                                    );

                                    ?>

                                </p>


                            </div>


                            <!-- CONTACT -->

                            <div class="card-footer bg-white">


                                <p class="mb-2">

                                    <i class="bi bi-telephone"></i>

                                    <strong>
                                        Contact:
                                    </strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $complex["contact_phone"]
                                    );

                                    ?>

                                </p>


                                <a
                                    href="contact.php"
                                    class="btn btn-outline-primary w-100"
                                >

                                    Contact Us

                                </a>


                            </div>


                        </div>

                    </div>


                <?php endwhile; ?>


            <?php else: ?>


                <div class="col-12">

                    <div class="alert alert-info text-center">

                        <h4>No facilities available</h4>

                        <p class="mb-0">

                            Information about our tennis facilities
                            will be available soon.

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

        <h2>Looking for a Tennis Facility?</h2>

        <p class="lead">

            Contact Mogale Tennis Association for more
            information about our facilities and programmes.

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