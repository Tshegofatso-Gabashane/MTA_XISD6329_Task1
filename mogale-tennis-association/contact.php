<?php

require_once "database/db.php";

$message = "";
$message_type = "";


// =====================================================
// CONTACT FORM
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["send_message"])
) {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $subject = trim($_POST["subject"]);
    $user_message = trim($_POST["message"]);


    // =================================================
    // VALIDATION
    // =================================================

    if (
        empty($name)
        || empty($email)
        || empty($subject)
        || empty($user_message)
    ) {

        $message =
            "Please complete all required fields.";

        $message_type = "danger";

    } elseif (
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $message =
            "Please enter a valid email address.";

        $message_type = "danger";

    } else {

        // =================================================
// SAVE MESSAGE TO DATABASE
// =================================================

$stmt = $conn->prepare(
    "INSERT INTO messages
    (
        name,
        email,
        phone,
        subject,
        message
    )
    VALUES (?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    "sssss",
    $name,
    $email,
    $phone,
    $subject,
    $user_message
);


if ($stmt->execute()) {

    $message =
        "Thank you, " .
        htmlspecialchars($name) .
        ". Your message has been received. " .
        "The Mogale Tennis Association team will contact you.";

    $message_type = "success";


    // Clear form

    $name = "";
    $email = "";
    $phone = "";
    $subject = "";
    $user_message = "";

} else {

    $message =
        "Your message could not be submitted. Please try again.";

    $message_type = "danger";

}


$stmt->close();
        $message_type = "success";


        // Clear form

        $name = "";
        $email = "";
        $phone = "";
        $subject = "";
        $user_message = "";

    }

}

?>


<?php include 'includes/header.php'; ?>
<?php include 'includes/navigationbar.php'; ?>


<!-- ================================================= -->
<!-- PAGE HEADER -->
<!-- ================================================= -->

<section class="bg-primary text-white py-5">

    <div class="container">

        <h1>Contact Us</h1>

        <p class="lead mb-0">

            Get in touch with Mogale Tennis Association.

        </p>

    </div>

</section>


<!-- ================================================= -->
<!-- CONTACT SECTION -->
<!-- ================================================= -->

<section class="py-5">

    <div class="container">


        <div class="row g-5">


            <!-- ========================================= -->
            <!-- CONTACT INFORMATION -->
            <!-- ========================================= -->

            <div class="col-lg-5">


                <h2>Get In Touch</h2>

                <p class="text-muted">

                    Whether you have a question about membership,
                    coaching, events or our facilities, we would
                    be happy to hear from you.

                </p>


                <!-- PHONE -->

                <div class="d-flex mb-4">

                    <div class="me-3">

                        <i
                            class="bi bi-telephone-fill text-primary"
                            style="font-size: 25px;"
                        ></i>

                    </div>


                    <div>

                        <h5>Phone</h5>

                        <p class="text-muted mb-0">

                            Contact the Mogale Tennis
                            Association office.

                        </p>

                    </div>

                </div>


                <!-- EMAIL -->

                <div class="d-flex mb-4">

                    <div class="me-3">

                        <i
                            class="bi bi-envelope-fill text-primary"
                            style="font-size: 25px;"
                        ></i>

                    </div>


                    <div>

                        <h5>Email</h5>

                        <p class="text-muted mb-0">

                            Send us an email for enquiries
                            and information.

                        </p>

                    </div>

                </div>


                <!-- LOCATION -->

                <div class="d-flex mb-4">

                    <div class="me-3">

                        <i
                            class="bi bi-geo-alt-fill text-primary"
                            style="font-size: 25px;"
                        ></i>

                    </div>


                    <div>

                        <h5>Location</h5>

                        <p class="text-muted mb-0">

                            Mogale City and surrounding
                            communities.

                        </p>

                    </div>

                </div>


            </div>



            <!-- ========================================= -->
            <!-- CONTACT FORM -->
            <!-- ========================================= -->

            <div class="col-lg-7">


                <div class="card shadow-sm">


                    <div class="card-body p-4">


                        <h3 class="mb-4">
                            Send Us a Message
                        </h3>


                        <!-- MESSAGE -->

                        <?php if (!empty($message)): ?>

                            <div
                                class="alert alert-<?php echo $message_type; ?>"
                            >

                                <?php

                                echo $message;

                                ?>

                            </div>

                        <?php endif; ?>


                        <form method="POST">


                            <div class="row g-3">


                                <!-- NAME -->

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Name *

                                    </label>


                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($name ?? ""); ?>"
                                        required
                                    >

                                </div>


                                <!-- EMAIL -->

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Email *

                                    </label>


                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($email ?? ""); ?>"
                                        required
                                    >

                                </div>


                                <!-- PHONE -->

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Phone

                                    </label>


                                    <input
                                        type="tel"
                                        name="phone"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($phone ?? ""); ?>"
                                    >

                                </div>


                                <!-- SUBJECT -->

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Subject *

                                    </label>


                                    <input
                                        type="text"
                                        name="subject"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($subject ?? ""); ?>"
                                        required
                                    >

                                </div>


                                <!-- MESSAGE -->

                                <div class="col-12">

                                    <label class="form-label">

                                        Message *

                                    </label>


                                    <textarea
                                        name="message"
                                        class="form-control"
                                        rows="6"
                                        required
                                    ><?php echo htmlspecialchars($user_message ?? ""); ?></textarea>

                                </div>


                                <!-- SUBMIT -->

                                <div class="col-12">

                                    <button
                                        type="submit"
                                        name="send_message"
                                        class="btn btn-primary"
                                    >

                                        <i class="bi bi-send"></i>

                                        Send Message

                                    </button>

                                </div>


                            </div>


                        </form>


                    </div>

                </div>


            </div>


        </div>


    </div>

</section>


<!-- ================================================= -->
<!-- CALL TO ACTION -->
<!-- ================================================= -->

<section class="bg-light py-5">

    <div class="container text-center">

        <h2>Join Mogale Tennis Association</h2>

        <p class="lead">

            Interested in becoming a member?

        </p>


        <a
            href="membership.php"
            class="btn btn-primary"
        >

            Apply for Membership

        </a>

    </div>

</section>


<?php include 'includes/footer.php'; ?>