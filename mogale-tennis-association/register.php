<?php

require_once "database/db.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $first_name = trim($_POST["firstname"]);
    $last_name = trim($_POST["lastname"]);
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $membership = $_POST["membership"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];


    // Check that passwords match

    if ($password !== $confirm_password) {

        $message = "Passwords do not match.";
        $message_type = "danger";

    } else {

        // Check whether email already exists

        $check = $conn->prepare(
            "SELECT member_id FROM members WHERE email = ?"
        );

        $check->bind_param("s", $email);

        $check->execute();

        $result = $check->get_result();


        if ($result->num_rows > 0) {

            $message = "An account with this email already exists.";
            $message_type = "danger";

        } else {

            // Securely hash the password

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            // Insert member

            $stmt = $conn->prepare(
                "INSERT INTO members
                (first_name, last_name, date_of_birth, gender, email,
                phone, address, membership_type, password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sssssssss",
                $first_name,
                $last_name,
                $dob,
                $gender,
                $email,
                $phone,
                $address,
                $membership,
                $hashed_password
            );


            if ($stmt->execute()) {

                $message = "Registration successful! You can now log in.";
                $message_type = "success";

            } else {

                $message = "Registration failed. Please try again.";
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

<!-- Registration Banner -->
<section class="bg-primary text-white py-5">
    <div class="container text-center">
        <h1>Member Registration</h1>
        <p class="lead">Complete the form below to join Mogale Tennis Association.</p>
    </div>
</section>

<!-- Registration Form -->
<section class="py-5">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow">

<div class="card-body p-5">

<?php if (!empty($message)): ?>

    <div class="alert alert-<?php echo $message_type; ?>">

        <?php echo htmlspecialchars($message); ?>

    </div>

<?php endif; ?>

<form action="" method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">First Name</label>

<input type="text"
class="form-control"
name="firstname"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Last Name</label>

<input type="text"
class="form-control"
name="lastname"
required>

</div>

</div>

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">Date of Birth</label>

<input type="date"
class="form-control"
name="dob"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Gender</label>

<select
class="form-select"
name="gender"
required>

<option value="">Select</option>

<option>Male</option>

<option>Female</option>

</select>

</div>

</div>

<div class="mb-3">

<label class="form-label">

Email Address

</label>

<input
type="email"
class="form-control"
name="email"
required>

</div>

<div class="mb-3">

<label class="form-label">

Phone Number

</label>

<input
type="text"
class="form-control"
name="phone"
required>

</div>

<div class="mb-3">

<label class="form-label">

Physical Address

</label>

<textarea
class="form-control"
name="address"
rows="3"
required></textarea>

</div>

<div class="mb-3">

<label class="form-label">

Membership Category

</label>

<select
class="form-select"
name="membership"
required>

<option value="">Select</option>

<option>Junior</option>

<option>Senior</option>

<option>Coach</option>

</select>

</div>

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Password

</label>

<input
type="password"
class="form-control"
name="password"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Confirm Password

</label>

<input
type="password"
class="form-control"
name="confirm_password"
required>

</div>

</div>

<div class="text-center">

<button
type="submit"
class="btn btn-warning btn-lg">

Register

</button>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</section>

<?php include 'includes/footer.php'; ?>