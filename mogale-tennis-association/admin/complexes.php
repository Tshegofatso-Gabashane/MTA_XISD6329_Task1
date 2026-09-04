<?php
require_once "admin-check.php";
require_once "../database/db.php";

$message = "";
$message_type = "";


// =====================================================
// DELETE COMPLEX
// =====================================================

if (isset($_GET["delete"])) {

    $complex_id = intval($_GET["delete"]);

    $stmt = $conn->prepare(
        "DELETE FROM complexes WHERE complex_id = ?"
    );

    $stmt->bind_param("i", $complex_id);

    if ($stmt->execute()) {

        $message = "Tennis complex deleted successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to delete tennis complex.";
        $message_type = "danger";

    }

    $stmt->close();
}


// =====================================================
// ADD COMPLEX
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["add_complex"])
) {

    $complex_name = trim($_POST["complex_name"]);
    $location = trim($_POST["location"]);
    $number_of_courts = intval($_POST["number_of_courts"]);
    $court_type = trim($_POST["court_type"]);
    $facilities = trim($_POST["facilities"]);
    $contact_phone = trim($_POST["contact_phone"]);
    $description = trim($_POST["description"]);


    $stmt = $conn->prepare(
        "INSERT INTO complexes
        (
            complex_name,
            location,
            number_of_courts,
            court_type,
            facilities,
            contact_phone,
            description
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );


    $stmt->bind_param(
        "ssissss",
        $complex_name,
        $location,
        $number_of_courts,
        $court_type,
        $facilities,
        $contact_phone,
        $description
    );


    if ($stmt->execute()) {

        $message = "Tennis complex added successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to add tennis complex.";
        $message_type = "danger";

    }

    $stmt->close();
}


// =====================================================
// EDIT COMPLEX
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["edit_complex"])
) {

    $complex_id = intval($_POST["complex_id"]);

    $complex_name = trim($_POST["complex_name"]);
    $location = trim($_POST["location"]);
    $number_of_courts = intval($_POST["number_of_courts"]);
    $court_type = trim($_POST["court_type"]);
    $facilities = trim($_POST["facilities"]);
    $contact_phone = trim($_POST["contact_phone"]);
    $description = trim($_POST["description"]);


    $stmt = $conn->prepare(
        "UPDATE complexes
         SET
            complex_name = ?,
            location = ?,
            number_of_courts = ?,
            court_type = ?,
            facilities = ?,
            contact_phone = ?,
            description = ?
         WHERE complex_id = ?"
    );


    $stmt->bind_param(
        "ssissssi",
        $complex_name,
        $location,
        $number_of_courts,
        $court_type,
        $facilities,
        $contact_phone,
        $description,
        $complex_id
    );


    if ($stmt->execute()) {

        $message = "Tennis complex updated successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to update tennis complex.";
        $message_type = "danger";

    }

    $stmt->close();
}


// =====================================================
// GET COMPLEX FOR EDITING
// =====================================================

$edit_complex = null;

if (isset($_GET["edit"])) {

    $edit_id = intval($_GET["edit"]);

    $stmt = $conn->prepare(
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
         WHERE complex_id = ?"
    );

    $stmt->bind_param("i", $edit_id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $edit_complex = $result->fetch_assoc();

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
            complex_id,
            complex_name,
            location,
            number_of_courts,
            court_type,
            facilities,
            contact_phone,
            description
         FROM complexes
         WHERE complex_name LIKE ?
            OR location LIKE ?
            OR court_type LIKE ?
            OR facilities LIKE ?
         ORDER BY complex_name ASC"
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

    $complexes = $stmt->get_result();

} else {

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

}

?>


<?php include '../includes/header.php'; ?>
<?php include '../includes/admin-navigationbar.php'; ?>


<!-- ================================================= -->
<!-- PAGE HEADER -->
<!-- ================================================= -->

<section class="bg-primary text-white py-5">

    <div class="container">

        <h1>Manage Facilities</h1>

        <p class="lead">
            Manage tennis complexes and playing facilities.
        </p>

    </div>

</section>


<!-- ================================================= -->
<!-- MAIN CONTENT -->
<!-- ================================================= -->

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

                <h2>Tennis Facilities</h2>

                <p class="text-muted mb-0">

                    Tennis complexes currently registered
                    in the system.

                </p>

            </div>


            <button
                type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#addComplexModal"
            >

                <i class="bi bi-building-add"></i>

                Add Facility

            </button>

        </div>


        <!-- SEARCH -->

        <form
            method="GET"
            action="complexes.php"
            class="mb-4"
        >

            <div class="input-group">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search facility, location or court type..."
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
                        href="complexes.php"
                        class="btn btn-secondary"
                    >

                        Clear

                    </a>

                <?php endif; ?>

            </div>

        </form>


        <!-- FACILITIES -->

        <div class="row g-4">


            <?php if ($complexes->num_rows > 0): ?>


                <?php while (
                    $complex = $complexes->fetch_assoc()
                ): ?>


                    <div class="col-md-6 col-lg-4">


                        <div class="card shadow h-100">


                            <div class="card-body">


                                <h4>

                                    <?php

                                    echo htmlspecialchars(
                                        $complex["complex_name"]
                                    );

                                    ?>

                                </h4>


                                <p class="text-muted">

                                    <i class="bi bi-geo-alt"></i>

                                    <?php

                                    echo htmlspecialchars(
                                        $complex["location"]
                                    );

                                    ?>

                                </p>


                                <hr>


                                <p>

                                    <strong>
                                        Number of Courts:
                                    </strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $complex["number_of_courts"]
                                    );

                                    ?>

                                </p>


                                <p>

                                    <strong>
                                        Court Type:
                                    </strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $complex["court_type"]
                                    );

                                    ?>

                                </p>


                                <p>

                                    <strong>
                                        Facilities:
                                    </strong>

                                    <?php

                                    echo nl2br(
                                        htmlspecialchars(
                                            $complex["facilities"]
                                        )
                                    );

                                    ?>

                                </p>


                                <p>

                                    <strong>
                                        Contact:
                                    </strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $complex["contact_phone"]
                                    );

                                    ?>

                                </p>


                                <p>

                                    <?php

                                    echo nl2br(
                                        htmlspecialchars(
                                            $complex["description"]
                                        )
                                    );

                                    ?>

                                </p>


                                <div class="d-flex gap-2 mt-4">


                                    <a
                                        href="complexes.php?edit=<?php echo $complex["complex_id"]; ?>"
                                        class="btn btn-warning btn-sm flex-fill"
                                    >

                                        Edit

                                    </a>


                                    <a
                                        href="complexes.php?delete=<?php echo $complex["complex_id"]; ?>"
                                        class="btn btn-danger btn-sm flex-fill"
                                        onclick="return confirm('Are you sure you want to delete this tennis complex?');"
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

                        No tennis facilities found.

                    </div>

                </div>


            <?php endif; ?>


        </div>

    </div>

</section>



<!-- ================================================= -->
<!-- ADD FACILITY MODAL -->
<!-- ================================================= -->

<div
    class="modal fade"
    id="addComplexModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Add Tennis Facility
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
                                Complex Name
                            </label>

                            <input
                                type="text"
                                name="complex_name"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Location
                            </label>

                            <input
                                type="text"
                                name="location"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Number of Courts
                            </label>

                            <input
                                type="number"
                                name="number_of_courts"
                                class="form-control"
                                min="1"
                                required
                            >

                        </div>


                        <div class="col-md-8">

                            <label class="form-label">
                                Court Type
                            </label>

                            <input
                                type="text"
                                name="court_type"
                                class="form-control"
                                placeholder="e.g. Hard court, Clay court"
                                required
                            >

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Facilities
                            </label>

                            <textarea
                                name="facilities"
                                class="form-control"
                                rows="3"
                                placeholder="e.g. Parking, changing rooms, lighting, toilets"
                            ></textarea>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Contact Phone
                            </label>

                            <input
                                type="text"
                                name="contact_phone"
                                class="form-control"
                            >

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                class="form-control"
                                rows="4"
                            ></textarea>

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
                        name="add_complex"
                        class="btn btn-success"
                    >

                        Add Facility

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>



<!-- ================================================= -->
<!-- EDIT FACILITY MODAL -->
<!-- ================================================= -->

<?php if ($edit_complex !== null): ?>

<div
    class="modal fade show"
    id="editComplexModal"
    tabindex="-1"
    style="display: block;"
    aria-modal="true"
    role="dialog"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Edit Tennis Facility
                </h5>


                <a
                    href="complexes.php"
                    class="btn-close"
                ></a>

            </div>


            <form method="POST">


                <div class="modal-body">


                    <input
                        type="hidden"
                        name="complex_id"
                        value="<?php echo $edit_complex["complex_id"]; ?>"
                    >


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label">
                                Complex Name
                            </label>

                            <input
                                type="text"
                                name="complex_name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_complex["complex_name"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Location
                            </label>

                            <input
                                type="text"
                                name="location"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_complex["location"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Number of Courts
                            </label>

                            <input
                                type="number"
                                name="number_of_courts"
                                class="form-control"
                                min="1"
                                value="<?php echo htmlspecialchars($edit_complex["number_of_courts"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-8">

                            <label class="form-label">
                                Court Type
                            </label>

                            <input
                                type="text"
                                name="court_type"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_complex["court_type"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Facilities
                            </label>

                            <textarea
                                name="facilities"
                                class="form-control"
                                rows="3"
                            ><?php echo htmlspecialchars($edit_complex["facilities"]); ?></textarea>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Contact Phone
                            </label>

                            <input
                                type="text"
                                name="contact_phone"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_complex["contact_phone"]); ?>"
                            >

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea
                                name="description"
                                class="form-control"
                                rows="4"
                            ><?php echo htmlspecialchars($edit_complex["description"]); ?></textarea>

                        </div>


                    </div>

                </div>


                <div class="modal-footer">


                    <a
                        href="complexes.php"
                        class="btn btn-secondary"
                    >

                        Cancel

                    </a>


                    <button
                        type="submit"
                        name="edit_complex"
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