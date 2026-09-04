<?php
require_once "admin-check.php";
require_once "../database/db.php";

$message = "";
$message_type = "";


// =====================================================
// DELETE COACH
// =====================================================

if (isset($_GET["delete"])) {

    $coach_id = intval($_GET["delete"]);

    $stmt = $conn->prepare(
        "DELETE FROM coaches WHERE coach_id = ?"
    );

    $stmt->bind_param("i", $coach_id);

    if ($stmt->execute()) {

        $message = "Coach deleted successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to delete coach.";
        $message_type = "danger";

    }

    $stmt->close();
}


// =====================================================
// ADD COACH
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["add_coach"])
) {

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $qualification = trim($_POST["qualification"]);
    $specialization = trim($_POST["specialization"]);
    $experience_years = intval($_POST["experience_years"]);


    $stmt = $conn->prepare(
        "INSERT INTO coaches
        (
            first_name,
            last_name,
            email,
            phone,
            qualification,
            specialization,
            experience_years
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );


    $stmt->bind_param(
        "ssssssi",
        $first_name,
        $last_name,
        $email,
        $phone,
        $qualification,
        $specialization,
        $experience_years
    );


    if ($stmt->execute()) {

        $message = "Coach added successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to add coach.";
        $message_type = "danger";

    }

    $stmt->close();
}


// =====================================================
// EDIT COACH
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["edit_coach"])
) {

    $coach_id = intval($_POST["coach_id"]);

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $qualification = trim($_POST["qualification"]);
    $specialization = trim($_POST["specialization"]);
    $experience_years = intval($_POST["experience_years"]);


    $stmt = $conn->prepare(
        "UPDATE coaches
         SET
            first_name = ?,
            last_name = ?,
            email = ?,
            phone = ?,
            qualification = ?,
            specialization = ?,
            experience_years = ?
         WHERE coach_id = ?"
    );


    $stmt->bind_param(
        "ssssssii",
        $first_name,
        $last_name,
        $email,
        $phone,
        $qualification,
        $specialization,
        $experience_years,
        $coach_id
    );


    if ($stmt->execute()) {

        $message = "Coach updated successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to update coach.";
        $message_type = "danger";

    }

    $stmt->close();
}


// =====================================================
// GET COACH FOR EDITING
// =====================================================

$edit_coach = null;

if (isset($_GET["edit"])) {

    $edit_id = intval($_GET["edit"]);

    $stmt = $conn->prepare(
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
         WHERE coach_id = ?"
    );

    $stmt->bind_param("i", $edit_id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $edit_coach = $result->fetch_assoc();

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
            coach_id,
            first_name,
            last_name,
            email,
            phone,
            qualification,
            specialization,
            experience_years
         FROM coaches
         WHERE first_name LIKE ?
            OR last_name LIKE ?
            OR specialization LIKE ?
            OR qualification LIKE ?
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

    $coaches = $stmt->get_result();

} else {

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
         ORDER BY created_at DESC"
    );

}

?>


<?php include '../includes/header.php'; ?>
<?php include '../includes/admin-navigationbar.php'; ?>


<section class="bg-primary text-white py-5">

    <div class="container">

        <h1>Manage Coaches</h1>

        <p class="lead">
            View and manage the Mogale Tennis Association coaching team.
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


        <!-- HEADING -->

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2>Coaching Team</h2>

                <p class="text-muted mb-0">
                    Coaches currently registered in the system.
                </p>

            </div>


            <button
                type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#addCoachModal"
            >

                <i class="bi bi-person-plus"></i>

                Add Coach

            </button>

        </div>


        <!-- SEARCH -->

        <form
            method="GET"
            action="coaches.php"
            class="mb-4"
        >

            <div class="input-group">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search coach, qualification or specialization..."
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
                        href="coaches.php"
                        class="btn btn-secondary"
                    >

                        Clear

                    </a>

                <?php endif; ?>

            </div>

        </form>


        <!-- COACHES -->

        <div class="row g-4">


            <?php if ($coaches->num_rows > 0): ?>


                <?php while (
                    $coach = $coaches->fetch_assoc()
                ): ?>


                    <div class="col-md-6 col-lg-4">


                        <div class="card shadow h-100">


                            <div class="card-body">


                                <div class="text-center mb-3">

                                    <i
                                        class="bi bi-person-circle"
                                        style="font-size: 70px;"
                                    ></i>

                                </div>


                                <h4 class="text-center">

                                    <?php

                                    echo htmlspecialchars(
                                        $coach["first_name"]
                                        . " "
                                        . $coach["last_name"]
                                    );

                                    ?>

                                </h4>


                                <p class="text-center text-muted">

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

                                    <?php

                                    echo htmlspecialchars(
                                        $coach["experience_years"]
                                    );

                                    ?>

                                    years

                                </p>


                                <p>

                                    <strong>
                                        Email:
                                    </strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $coach["email"]
                                    );

                                    ?>

                                </p>


                                <p>

                                    <strong>
                                        Phone:
                                    </strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $coach["phone"]
                                    );

                                    ?>

                                </p>


                                <div class="d-flex gap-2 mt-4">


                                    <a
                                        href="coaches.php?edit=<?php echo $coach["coach_id"]; ?>"
                                        class="btn btn-warning btn-sm flex-fill"
                                    >

                                        Edit

                                    </a>


                                    <a
                                        href="coaches.php?delete=<?php echo $coach["coach_id"]; ?>"
                                        class="btn btn-danger btn-sm flex-fill"
                                        onclick="return confirm('Are you sure you want to delete this coach?');"
                                    >

                                        Delete

                                    </a>


                                </div>


                            </div>

                        </div>

                    </div>


                <?php endwhile; ?>


            <?php else: ?>


                <div class="col-12">

                    <div class="alert alert-info text-center">

                        No coaches found.

                    </div>

                </div>


            <?php endif; ?>


        </div>

    </div>

</section>



<!-- ================================================= -->
<!-- ADD COACH MODAL -->
<!-- ================================================= -->

<div
    class="modal fade"
    id="addCoachModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Add New Coach
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
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
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
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Qualification
                            </label>

                            <input
                                type="text"
                                name="qualification"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Specialization
                            </label>

                            <input
                                type="text"
                                name="specialization"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Years of Experience
                            </label>

                            <input
                                type="number"
                                name="experience_years"
                                class="form-control"
                                min="0"
                                value="0"
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
                        name="add_coach"
                        class="btn btn-success"
                    >

                        Add Coach

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>



<!-- ================================================= -->
<!-- EDIT COACH MODAL -->
<!-- ================================================= -->

<?php if ($edit_coach !== null): ?>

<div
    class="modal fade show"
    id="editCoachModal"
    tabindex="-1"
    style="display: block;"
    aria-modal="true"
    role="dialog"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Edit Coach
                </h5>

                <a
                    href="coaches.php"
                    class="btn-close"
                ></a>

            </div>


            <form method="POST">


                <div class="modal-body">


                    <input
                        type="hidden"
                        name="coach_id"
                        value="<?php echo $edit_coach["coach_id"]; ?>"
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
                                value="<?php echo htmlspecialchars($edit_coach["first_name"]); ?>"
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
                                value="<?php echo htmlspecialchars($edit_coach["last_name"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_coach["email"]); ?>"
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
                                value="<?php echo htmlspecialchars($edit_coach["phone"]); ?>"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Qualification
                            </label>

                            <input
                                type="text"
                                name="qualification"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_coach["qualification"]); ?>"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Specialization
                            </label>

                            <input
                                type="text"
                                name="specialization"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_coach["specialization"]); ?>"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Years of Experience
                            </label>

                            <input
                                type="number"
                                name="experience_years"
                                class="form-control"
                                min="0"
                                value="<?php echo htmlspecialchars($edit_coach["experience_years"]); ?>"
                            >

                        </div>


                    </div>

                </div>


                <div class="modal-footer">

                    <a
                        href="coaches.php"
                        class="btn btn-secondary"
                    >

                        Cancel

                    </a>


                    <button
                        type="submit"
                        name="edit_coach"
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