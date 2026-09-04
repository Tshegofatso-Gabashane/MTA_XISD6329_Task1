<?php include 'includes/header.php'; ?>
<?php include 'includes/navigationbar.php'; ?>

<?php

require_once "database/db.php";



// Get total coaches
$result = $conn->query(
    "SELECT COUNT(*) AS total FROM coaches"
);

$coaches_count = 0;

if ($result) {
    $row = $result->fetch_assoc();
    $coaches_count = $row["total"];
}


// Get total facilities
$result = $conn->query(
    "SELECT COUNT(*) AS total FROM complexes"
);

$facilities_count = 0;

if ($result) {
    $row = $result->fetch_assoc();
    $facilities_count = $row["total"];
}

// Get upcoming events
$events_result = $conn->query(
    "SELECT * FROM events
     WHERE event_date >= CURDATE()
     ORDER BY event_date ASC
     LIMIT 3"
);

?>


<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6">
                <h1>Welcome to Mogale Tennis Association</h1>

                <p class="lead">
                    Developing Future Champions Through Tennis Excellence.
                </p>

                <a href="register.php" class="btn btn-warning btn-lg">
                    Become a Member
                </a>

                <a href="events.php" class="btn btn-outline-light btn-lg">
                    Upcoming Events
                </a>

            </div>

            <div class="col-lg-6 text-center">

                <img src="assests/images/hero-tennis.PNG"
                class="img-fluid"
                alt="Tennis Player">

            </div>

        </div>
    </div>
</section>


<section class="py-5 bg-light">

    <div class="container">

        <div class="row text-center g-4">


            <div class="col-md-4">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <i
                            class="bi bi-person-badge-fill text-primary"
                            style="font-size: 40px;"
                        ></i>

                        <h2 class="mt-2">
                            <?php echo $coaches_count; ?>
                        </h2>

                        <p class="text-muted mb-0">
                            Qualified Coaches
                        </p>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <i
                            class="bi bi-building text-primary"
                            style="font-size: 40px;"
                        ></i>

                        <h2 class="mt-2">
                            <?php echo $facilities_count; ?>
                        </h2>

                        <p class="text-muted mb-0">
                            Tennis Facilities
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                Upcoming Events
            </h2>

            <p class="text-muted">
                Stay updated with Mogale Tennis Association activities.
            </p>

        </div>


        <div class="row g-4">

            <?php if ($events_result && $events_result->num_rows > 0): ?>

                <?php while ($event = $events_result->fetch_assoc()): ?>

                    <div class="col-md-4">

                        <div class="card shadow-sm h-100">

                            <div class="card-body">

                                <h5 class="card-title fw-bold">
                                    <?php echo htmlspecialchars($event["event_name"]); ?>
                                </h5>

                                <p class="mb-2">
                                    <strong>Date:</strong>
                                    <?php echo htmlspecialchars($event["event_date"]); ?>
                                </p>

                                <?php if (isset($event["location"])): ?>

                                    <p class="mb-2">
                                        <strong>Location:</strong>
                                        <?php echo htmlspecialchars($event["location"]); ?>
                                    </p>

                                <?php endif; ?>


                                <?php if (isset($event["description"])): ?>

                                    <p class="text-muted">
                                        <?php echo htmlspecialchars($event["description"]); ?>
                                    </p>

                                <?php endif; ?>

                                <p></p>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="col-12 text-center">

                    <p class="text-muted">
                        No upcoming events available at the moment.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>



<section class="py-5 bg-light">

<div class="container">

<div class="row">

<div class="col-md-4">

<div class="card shadow">

<div class="card-body text-center">


<p>
<img src="assests/images/event1.jpg" class="card-img-top">
</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body text-center">


<p>

<img src="assests/images/event2.jpg" class="card-img-top">

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body text-center">


<p>

<img src="assests/images/event3.jpg" class="card-img-top">

</p>

</div>

</div>

</div>

</div>

</div>

</section>


<!-- About Section -->

<section class="py-5">

<div class="container">

<div class="text-center mb-5">

<h2>About Mogale Tennis Association</h2>

<p>

The Mogale Tennis Association is committed to promoting tennis
development through coaching, tournaments and community participation.

</p>

</div>

</div>

</section>


<!-- Services -->

<section class="py-5 bg-light">

<div class="container">

<div class="row">

<div class="col-md-4">

<div class="card shadow">

<div class="card-body text-center">

<h4>Professional Coaching</h4>

<p>
Qualified coaches helping players of all ages improve their game.
</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body text-center">

<h4>Tournaments</h4>

<p>

Competitive tournaments for juniors and seniors.

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body text-center">

<h4>Community Tennis</h4>

<p>

Growing tennis throughout the Mogale region.

</p>

</div>

</div>

</div>

</div>

</div>

</section>


<?php include 'includes/footer.php'; ?>