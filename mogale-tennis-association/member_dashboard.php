<?php

session_start();


// =====================================================
// CHECK LOGIN
// =====================================================

if (!isset($_SESSION["member_id"])) {

    header("Location: login.php");

    exit;

}

?>


<?php include 'includes/header.php'; ?>
<?php include 'includes/navigationbar.php'; ?>


<!-- ================================================= -->
<!-- DASHBOARD -->
<!-- ================================================= -->

<section class="bg-primary text-white py-5">

    <div class="container">

        <h1>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["member_name"]); ?>
        </h1>

        <p class="lead mb-0">

            Welcome to your Mogale Tennis Association
            member dashboard.

        </p>

    </div>

</section>


<section class="py-5">

    <div class="container">


        <div class="row g-4">


            <!-- MEMBER INFORMATION -->

            <div class="col-md-6">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <h3>My Account</h3>

                        <hr>


                        <p>

                            <strong>Name:</strong>

                            <br>

                            <?php

                            echo htmlspecialchars(
                                $_SESSION["member_name"]
                            );

                            ?>

                        </p>


                        <p>

                            <strong>Email:</strong>

                            <br>

                            <?php

                            echo htmlspecialchars(
                                $_SESSION["member_email"]
                            );

                            ?>

                        </p>


                        <p>

                            <strong>Membership Type:</strong>

                            <br>

                            <?php

                            echo htmlspecialchars(
                                $_SESSION["membership_type"]
                            );

                            ?>

                        </p>

                    </div>

                </div>

            </div>


            <!-- QUICK LINKS -->

            <div class="col-md-6">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <h3>Quick Links</h3>

                        <hr>


                        <a
                            href="events.php"
                            class="btn btn-outline-primary w-100 mb-2"
                        >

                            View Events

                        </a>


                        <a
                            href="coaches.php"
                            class="btn btn-outline-primary w-100 mb-2"
                        >

                            Meet Our Coaches

                        </a>


                        <a
                            href="complexes.php"
                            class="btn btn-outline-primary w-100 mb-2"
                        >

                            View Facilities

                        </a>


                        <a
                            href="logout.php"
                            class="btn btn-danger w-100"
                        >

                            Logout

                        </a>

                    </div>

                </div>

            </div>


        </div>


    </div>

</section>


<?php include 'includes/footer.php'; ?>