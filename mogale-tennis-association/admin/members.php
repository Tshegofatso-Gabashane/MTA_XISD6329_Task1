<?php
require_once "admin-check.php";
require_once "../database/db.php";

$message = "";
$message_type = "";


// =====================================================
// DELETE MEMBER
// =====================================================

if (isset($_GET["delete"])) {

    $member_id = intval($_GET["delete"]);

    $stmt = $conn->prepare(
        "DELETE FROM members WHERE member_id = ?"
    );

    $stmt->bind_param("i", $member_id);

    if ($stmt->execute()) {

        $message = "Member deleted successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to delete member.";
        $message_type = "danger";

    }

    $stmt->close();
}


// =====================================================
// ADD MEMBER
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["add_member"])) {

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $dob = $_POST["date_of_birth"];
    $gender = $_POST["gender"];
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $membership = $_POST["membership_type"];
    $password = $_POST["password"];


    $check = $conn->prepare(
        "SELECT member_id FROM members WHERE email = ?"
    );

    $check->bind_param("s", $email);

    $check->execute();

    $result = $check->get_result();


    if ($result->num_rows > 0) {

        $message = "A member with this email already exists.";
        $message_type = "danger";

    } else {

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


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
            $dob,
            $gender,
            $email,
            $phone,
            $address,
            $membership,
            $hashed_password
        );


        if ($stmt->execute()) {

            $message = "Member added successfully.";
            $message_type = "success";

        } else {

            $message = "Unable to add member.";
            $message_type = "danger";

        }

        $stmt->close();

    }

    $check->close();
}


// =====================================================
// EDIT MEMBER
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["edit_member"])) {

    $member_id = intval($_POST["member_id"]);

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $dob = $_POST["date_of_birth"];
    $gender = $_POST["gender"];
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $membership = $_POST["membership_type"];


    $stmt = $conn->prepare(
        "UPDATE members
         SET
            first_name = ?,
            last_name = ?,
            date_of_birth = ?,
            gender = ?,
            email = ?,
            phone = ?,
            address = ?,
            membership_type = ?
         WHERE member_id = ?"
    );


    $stmt->bind_param(
        "ssssssssi",
        $first_name,
        $last_name,
        $dob,
        $gender,
        $email,
        $phone,
        $address,
        $membership,
        $member_id
    );


    if ($stmt->execute()) {

        $message = "Member updated successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to update member.";
        $message_type = "danger";

    }

    $stmt->close();
}

// =====================================================
// GET MEMBER FOR EDITING
// =====================================================

$edit_member = null;

if (isset($_GET["edit"])) {

    $edit_id = intval($_GET["edit"]);

    $stmt = $conn->prepare(
        "SELECT
            member_id,
            first_name,
            last_name,
            date_of_birth,
            gender,
            email,
            phone,
            address,
            membership_type
         FROM members
         WHERE member_id = ?"
    );

    $stmt->bind_param("i", $edit_id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $edit_member = $result->fetch_assoc();

    }

    $stmt->close();
}

// =====================================================
// SEARCH
// =====================================================

$search = "";

if (isset($_GET["search"])) {

    $search = trim($_GET["search"]);

}


if ($search !== "") {

    $stmt = $conn->prepare(
        "SELECT
            member_id,
            first_name,
            last_name,
            date_of_birth,
            gender,
            email,
            phone,
            address,
            membership_type,
            created_at
         FROM members
         WHERE first_name LIKE ?
            OR last_name LIKE ?
            OR email LIKE ?
            OR membership_type LIKE ?
         ORDER BY created_at DESC"
    );


    $search_term = "%" . $search . "%";


    $stmt->bind_param(
        "ssss",
        $search_term,
        $search_term,
        $search_term,
        $search_term
    );


    $stmt->execute();

    $members = $stmt->get_result();

} else {

    $members = $conn->query(
        "SELECT
            member_id,
            first_name,
            last_name,
            date_of_birth,
            gender,
            email,
            phone,
            address,
            membership_type,
            created_at
         FROM members
         ORDER BY created_at DESC"
    );

}

?>


<?php include '../includes/header.php'; ?>
<?php include '../includes/admin-navigationbar.php'; ?>


<section class="bg-primary text-white py-5">

    <div class="container">

        <h1>Manage Members</h1>

        <p class="lead">
            View and manage registered Mogale Tennis Association members.
        </p>

    </div>

</section>


<section class="py-5">

    <div class="container">


        <!-- MESSAGE -->

        <?php if (!empty($message)): ?>

            <div class="alert alert-<?php echo $message_type; ?>">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <!-- PAGE HEADING -->

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2>Registered Members</h2>

                <p class="text-muted mb-0">
                    Members currently registered in the system.
                </p>

            </div>


            <!-- ADD MEMBER BUTTON -->

            <button
                type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#addMemberModal"
            >

                <i class="bi bi-person-plus"></i>

                Add Member

            </button>

        </div>


        <!-- SEARCH -->

        <form
            method="GET"
            action="members.php"
            class="mb-4"
        >

            <div class="input-group">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search by name, email or membership type..."
                    value="<?php echo htmlspecialchars($search); ?>"
                >

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="bi bi-search"></i>

                    Search

                </button>


                <?php if ($search !== ""): ?>

                    <a
                        href="members.php"
                        class="btn btn-secondary"
                    >

                        Clear

                    </a>

                <?php endif; ?>

            </div>

        </form>


        <!-- MEMBERS TABLE -->

        <div class="card shadow">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>

                                <th>Name</th>

                                <th>Email</th>

                                <th>Phone</th>

                                <th>Membership</th>

                                <th>Registered</th>

                                <th>Actions</th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php if ($members->num_rows > 0): ?>


                            <?php while (
                                $member =
                                $members->fetch_assoc()
                            ): ?>


                                <tr>


                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $member["member_id"]
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <strong>

                                            <?php

                                            echo htmlspecialchars(
                                                $member["first_name"]
                                                . " "
                                                . $member["last_name"]
                                            );

                                            ?>

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            <?php

                                            echo htmlspecialchars(
                                                $member["gender"]
                                            );

                                            ?>

                                        </small>

                                    </td>


                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $member["email"]
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $member["phone"]
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <span class="badge bg-primary">

                                            <?php

                                            echo htmlspecialchars(
                                                $member["membership_type"]
                                            );

                                            ?>

                                        </span>

                                    </td>


                                    <td>

                                        <?php

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $member["created_at"]
                                            )
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <a
                                            href="members.php?edit=<?php echo $member["member_id"]; ?>"
                                            class="btn btn-sm btn-warning"
                                        >

                                            Edit

                                        </a>


                                        <a
                                            href="members.php?delete=<?php echo $member["member_id"]; ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this member?');"
                                        >

                                            Delete

                                        </a>

                                    </td>


                                </tr>


                            <?php endwhile; ?>


                        <?php else: ?>


                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-4"
                                >

                                    No members found.

                                </td>

                            </tr>


                        <?php endif; ?>


                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- ================================================= -->
<!-- ADD MEMBER MODAL -->
<!-- ================================================= -->

<div
    class="modal fade"
    id="addMemberModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Add New Member
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form method="POST">


                <div class="modal-body">


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label">
                                First Name
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Last Name
                            </label>

                            <input
                                type="text"
                                name="last_name"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Date of Birth
                            </label>

                            <input
                                type="date"
                                name="date_of_birth"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Gender
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


                        <div class="col-md-6">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Address
                            </label>

                            <textarea
                                name="address"
                                class="form-control"
                                rows="3"
                                required
                            ></textarea>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Membership Type
                            </label>

                            <select
                                name="membership_type"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Membership
                                </option>

                                <option value="Junior">
                                    Junior
                                </option>

                                <option value="Senior">
                                    Senior
                                </option>

                                <option value="Coach">
                                    Coach
                                </option>

                                <option value="Social">
                                    Social
                                </option>

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Temporary Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required
                            >

                        </div>


                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        name="add_member"
                        class="btn btn-success"
                    >
                        Add Member
                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

<?php if ($edit_member !== null): ?>

<div
    class="modal fade show"
    id="editMemberModal"
    tabindex="-1"
    style="display: block;"
    aria-modal="true"
    role="dialog"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Edit Member
                </h5>

                <a
                    href="members.php"
                    class="btn-close"
                ></a>

            </div>


            <form method="POST">

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="member_id"
                        value="<?php echo $edit_member["member_id"]; ?>"
                    >


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label">
                                First Name
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_member["first_name"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Last Name
                            </label>

                            <input
                                type="text"
                                name="last_name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_member["last_name"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Date of Birth
                            </label>

                            <input
                                type="date"
                                name="date_of_birth"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_member["date_of_birth"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Gender
                            </label>

                            <select
                                name="gender"
                                class="form-select"
                                required
                            >

                                <option
                                    value="Male"
                                    <?php
                                    if ($edit_member["gender"] === "Male") {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Male
                                </option>


                                <option
                                    value="Female"
                                    <?php
                                    if ($edit_member["gender"] === "Female") {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Female
                                </option>


                                <option
                                    value="Other"
                                    <?php
                                    if ($edit_member["gender"] === "Other") {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Other
                                </option>

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_member["email"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_member["phone"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Address
                            </label>

                            <textarea
                                name="address"
                                class="form-control"
                                rows="3"
                                required
                            ><?php echo htmlspecialchars($edit_member["address"]); ?></textarea>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Membership Type
                            </label>

                            <select
                                name="membership_type"
                                class="form-select"
                                required
                            >

                                <option
                                    value="Junior"
                                    <?php
                                    if ($edit_member["membership_type"] === "Junior") {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Junior
                                </option>


                                <option
                                    value="Senior"
                                    <?php
                                    if ($edit_member["membership_type"] === "Senior") {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Senior
                                </option>


                                <option
                                    value="Coach"
                                    <?php
                                    if ($edit_member["membership_type"] === "Coach") {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Coach
                                </option>


                                <option
                                    value="Social"
                                    <?php
                                    if ($edit_member["membership_type"] === "Social") {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Social
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <a
                        href="members.php"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        name="edit_member"
                        class="btn btn-primary"
                    >
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<div class="modal-backdrop fade show"></div>

<?php endif; ?>

<?php include '../includes/footer.php'; ?>