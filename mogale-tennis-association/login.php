<?php

session_start();

require_once "database/db.php";

$message = "";
$message_type = "";


// =====================================================
// LOGIN
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["login"])
) {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    if (empty($email) || empty($password)) {

        $message = "Please enter your email and password.";
        $message_type = "danger";

    } else {


        // Find member by email

        $stmt = $conn->prepare(
            "SELECT
                member_id,
                first_name,
                last_name,
                email,
                password,
                membership_type
             FROM members
             WHERE email = ?
             LIMIT 1"
        );


        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();


        if ($result->num_rows === 1) {

            $member = $result->fetch_assoc();


            // Check password

            if (
                password_verify(
                    $password,
                    $member["password"]
                )
            ) {

                // Store member information in session

                $_SESSION["member_id"] =
                    $member["member_id"];

                $_SESSION["member_name"] =
                    $member["first_name"]
                    . " "
                    . $member["last_name"];

                $_SESSION["member_email"] =
                    $member["email"];

                $_SESSION["membership_type"] =
                    $member["membership_type"];


                // Redirect to member dashboard

                header(
                    "Location: member_dashboard.php"
                );

                exit;


            } else {

                $message =
                    "Incorrect email or password.";

                $message_type = "danger";

            }


        } else {

            $message =
                "Incorrect email or password.";

            $message_type = "danger";

        }


        $stmt->close();

    }

}

?>


<?php include 'includes/header.php'; ?>
<?php include 'includes/navigationbar.php'; ?>


<!-- ================================================= -->
<!-- LOGIN PAGE -->
<!-- ================================================= -->

<section class="bg-primary text-white py-5">

    <div class="container text-center">

        <h1>Member Login</h1>

        <p class="lead mb-0">

            Access your Mogale Tennis Association
            member account.

        </p>

    </div>

</section>


<section class="py-5">

    <div class="container">


        <div class="row justify-content-center">

            <div class="col-md-6 col-lg-5">


                <div class="card shadow">


                    <div class="card-body p-4">


                        <div class="text-center mb-4">

                            <h2>Welcome Back</h2>

                            <p class="text-muted">

                                Login to your member account.

                            </p>

                        </div>


                        <!-- MESSAGE -->

                        <?php if (!empty($message)): ?>

                            <div
                                class="alert alert-<?php echo $message_type; ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $message
                                );

                                ?>

                            </div>

                        <?php endif; ?>


                        <!-- LOGIN FORM -->

                        <form method="POST">


                            <!-- EMAIL -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Email Address

                                </label>


                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    required
                                >

                            </div>


                            <!-- PASSWORD -->

                            <div class="mb-3">

                                <label class="form-label">

                                    Password

                                </label>


                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    required
                                >

                            </div>


                            <!-- LOGIN -->

                            <button
                                type="submit"
                                name="login"
                                class="btn btn-primary w-100"
                            >

                                <i class="bi bi-box-arrow-in-right"></i>

                                Login

                            </button>


                        </form>


                        <hr>


                        <div class="text-center">

                            <p class="mb-2">

                                Don't have an account?

                            </p>


                            <a
                                href="membership.php"
                                class="btn btn-outline-primary"
                            >

                                Become a Member

                            </a>

                        </div>


                    </div>

                </div>


            </div>

        </div>


    </div>

</section>


<?php include 'includes/footer.php'; ?>