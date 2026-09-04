<?php

require_once "database/db.php";

$message = "";
$message_type = "";


// =====================================================
// MEMBERSHIP REGISTRATION
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["register_member"])
) {

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $date_of_birth = $_POST["date_of_birth"];
    $gender = trim($_POST["gender"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $membership_type = trim($_POST["membership_type"]);
    $password = $_POST["password"];


    // -------------------------------------------------
    // BASIC VALIDATION
    // -------------------------------------------------

    if (
        empty($first_name)
        || empty($last_name)
        || empty($date_of_birth)
        || empty($gender)
        || empty($email)
        || empty($phone)
        || empty($address)
        || empty($membership_type)
        || empty($password)
    ) {

        $message = "Please complete all required fields.";
        $message_type = "danger";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "danger";

    } else {


        // -------------------------------------------------
        // CHECK IF EMAIL ALREADY EXISTS
        // -------------------------------------------------

        $check = $conn->prepare(
            "SELECT member_id
             FROM members
             WHERE email = ?"
        );

        $check->bind_param("s", $email);

        $check->execute();

        $result = $check->get_result();


        if ($result->num_rows > 0) {

            $message =
                "A member with this email address already exists.";

            $message_type = "warning";

        } else {


            // -------------------------------------------------
            // HASH PASSWORD
            // -------------------------------------------------

            $hashed_password =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );


            // -------------------------------------------------
            // INSERT MEMBER
            // -------------------------------------------------

            $stmt = $conn->prepare(
                "INSERT INTO members
                (
                    first_name,
                    last_name,
                    date_of_birth,
                    gender,
                    email,
                    phone,
                    address,
                    membership_type,
                    password
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );


            $stmt->bind_param(
                "sssssssss",
                $first_name,
                $last_name,
                $date_of_birth,
                $gender,
                $email,
                $phone,
                $address,
                $membership_type,
                $hashed_password
            );


            if ($stmt->execute()) {

                $message =
                    "Your membership application has been submitted successfully.";

                $message_type = "success";


                // Clear form values after successful registration

                $first_name = "";
                $last_name = "";
                $date_of_birth = "";
                $gender = "";
                $email = "";
                $phone = "";
                $address = "";
                $membership_type = "";

            } else {

                $message =
                    "Unable to submit your membership application.";

                $message_type = "danger";

            }


            $stmt->close();

        }


        $check->close();

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

        <h1>Membership</h1>

        <p class="lead mb-0">

            Join Mogale Tennis Association and become
            part of our growing tennis community.

        </p>

    </div>

</section>


<!-- ================================================= -->
<!-- MEMBERSHIP INFORMATION -->
<!-- ================================================= -->

<section class="py-5">

    <div class="container">


        <div class="row g-4 mb-5">


            <div class="col-md-4">

                <div class="card h-100 shadow-sm">

                    <div class="card-body text-center">

                        <i
                            class="bi bi-people-fill text-primary"
                            style="font-size: 40px;"
                        ></i>

                        <h4 class="mt-3">
                            Join the Community
                        </h4>

                        <p class="text-muted">

                            Become part of a tennis community
                            focused on participation,
                            development and growth.

                        </p>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card h-100 shadow-sm">

                    <div class="card-body text-center">

                        <i
                            class="bi bi-trophy-fill text-primary"
                            style="font-size: 40px;"
                        ></i>

                        <h4 class="mt-3">
                            Develop Your Skills
                        </h4>

                        <p class="text-muted">

                            Take part in tennis programmes,
                            training and events.

                        </p>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card h-100 shadow-sm">

                    <div class="card-body text-center">

                        <i
                            class="bi bi-calendar-event-fill text-primary"
                            style="font-size: 40px;"
                        ></i>

                        <h4 class="mt-3">
                            Take Part in Events
                        </h4>

                        <p class="text-muted">

                            Participate in tournaments,
                            activities and community programmes.

                        </p>

                    </div>

                </div>

            </div>


        </div>


        <!-- ================================================= -->
        <!-- MESSAGE -->
        <!-- ================================================= -->

        <?php if (!empty($message)): ?>

            <div class="alert alert-<?php echo $message_type; ?>">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <!-- ================================================= -->
        <!-- REGISTRATION FORM -->
        <!-- ================================================= -->

        <div class="row justify-content-center">

            <div class="col-lg-9">

                <div class="card shadow">


                    <div class="card-header bg-primary text-white">

                        <h3 class="mb-0">
                            Membership Application
                        </h3>

                    </div>


                    <div class="card-body p-4">


                        <form method="POST">


                            <div class="row g-3">


                                <!-- FIRST NAME -->

                                <div class="col-md-6">

                                    <label class="form-label">
                                        First Name *
                                    </label>

                                    <input
                                        type="text"
                                        name="first_name"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($first_name ?? ""); ?>"
                                        required
                                    >

                                </div>


                                <!-- LAST NAME -->

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Last Name *
                                    </label>

                                    <input
                                        type="text"
                                        name="last_name"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($last_name ?? ""); ?>"
                                        required
                                    >

                                </div>


                                <!-- DATE OF BIRTH -->

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Date of Birth *
                                    </label>

                                    <input
                                        type="date"
                                        name="date_of_birth"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($date_of_birth ?? ""); ?>"
                                        required
                                    >

                                </div>


                                <!-- GENDER -->

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Gender *
                                    </label>

                                    <select
                                        name="gender"
                                        class="form-select"
                                        required
                                    >

                                        <option value="">
                                            Select Gender
                                        </option>

                                        <option value="Male">
                                            Male
                                        </option>

                                        <option value="Female">
                                            Female
                                        </option>

                                        <option value="Other">
                                            Other
                                        </option>

                                    </select>

                                </div>


                                <!-- EMAIL -->

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Email Address *
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
                                        Phone Number *
                                    </label>

                                    <input
                                        type="tel"
                                        name="phone"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($phone ?? ""); ?>"
                                        required
                                    >

                                </div>


                                <!-- ADDRESS -->

                                <div class="col-12">

                                    <label class="form-label">
                                        Address *
                                    </label>

                                    <textarea
                                        name="address"
                                        class="form-control"
                                        rows="3"
                                        required
                                    ><?php echo htmlspecialchars($address ?? ""); ?></textarea>

                                </div>


                                <!-- MEMBERSHIP TYPE -->

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Membership Type *
                                    </label>

                                    <select
                                        name="membership_type"
                                        class="form-select"
                                        required
                                    >

                                        <option value="">
                                            Select Membership Type
                                        </option>

                                        <option value="Junior">
                                            Junior
                                        </option>

                                        <option value="Senior">
                                            Senior
                                        </option>

                                        <option value="Social">
                                            Social
                                        </option>

                                        <option value="Coach">
                                            Coach
                                        </option>

                                    </select>

                                </div>


                                <!-- PASSWORD -->

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Password *
                                    </label>

                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control"
                                        minlength="6"
                                        required
                                    >

                                    <small class="text-muted">

                                        Password must contain at least
                                        6 characters.

                                    </small>

                                </div>


                                <!-- SUBMIT -->

                                <div class="col-12 mt-4">


                                    <button
                                        type="submit"
                                        name="register_member"
                                        class="btn btn-primary btn-lg w-100"
                                    >

                                        <i class="bi bi-person-plus"></i>

                                        Submit Membership Application

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
<!-- CONTACT CTA -->
<!-- ================================================= -->

<section class="bg-light py-5">

    <div class="container text-center">

        <h2>Need More Information?</h2>

        <p class="lead">

            Contact Mogale Tennis Association if you
            have questions about membership.

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