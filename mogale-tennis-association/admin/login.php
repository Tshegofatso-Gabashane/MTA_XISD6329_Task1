<?php

session_start();

require_once "../database/db.php";

$message = "";
$message_type = "";


// =====================================================
// ADMIN LOGIN
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["admin_login"])
) {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    if (
        empty($email)
        || empty($password)
    ) {

        $message =
            "Please enter your email and password.";

        $message_type = "danger";

    } else {


        $stmt = $conn->prepare(
            "SELECT
                admin_id,
                first_name,
                last_name,
                email,
                password
             FROM admins
             WHERE email = ?
             LIMIT 1"
        );


        $stmt->bind_param(
            "s",
            $email
        );

        $stmt->execute();

        $result = $stmt->get_result();


        if ($result->num_rows === 1) {

            $admin = $result->fetch_assoc();


            if (
                password_verify(
                    $password,
                    $admin["password"]
                )
            ) {


                // Create admin session

                $_SESSION["admin_id"] =
                    $admin["admin_id"];

                $_SESSION["admin_name"] =
                    $admin["first_name"]
                    . " "
                    . $admin["last_name"];

                $_SESSION["admin_email"] =
                    $admin["email"];


                // Go to dashboard

                header(
                    "Location: dashboard.php"
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


<?php include '../includes/header.php'; ?>


<section class="bg-primary text-white py-5">

    <div class="container text-center">

        <h1>Administrator Login</h1>

        <p class="lead mb-0">

            Mogale Tennis Association
            Administration

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

                            <h2>Admin Login</h2>

                            <p class="text-muted">

                                Sign in to manage the
                                association system.

                            </p>

                        </div>


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


                        <form method="POST">


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


                            <button
                                type="submit"
                                name="admin_login"
                                class="btn btn-primary w-100"
                            >

                                Login as Administrator

                            </button>


                        </form>


                    </div>

                </div>


            </div>

        </div>

    </div>

</section>


<?php include '../includes/footer.php'; ?>