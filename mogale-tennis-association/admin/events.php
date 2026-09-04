<?php
require_once "admin-check.php";
require_once "../database/db.php";

$message = "";
$message_type = "";


// =====================================================
// DELETE EVENT
// =====================================================

if (isset($_GET["delete"])) {

    $event_id = intval($_GET["delete"]);

    $stmt = $conn->prepare(
        "DELETE FROM events WHERE event_id = ?"
    );

    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {

        $message = "Event deleted successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to delete event.";
        $message_type = "danger";

    }

    $stmt->close();
}


// =====================================================
// ADD EVENT
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["add_event"])
) {

    $event_name = trim($_POST["event_name"]);
    $event_type = trim($_POST["event_type"]);
    $event_date = $_POST["event_date"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];
    $venue = trim($_POST["venue"]);
    $description = trim($_POST["description"]);


    $stmt = $conn->prepare(
        "INSERT INTO events
        (
            event_name,
            event_type,
            event_date,
            start_time,
            end_time,
            venue,
            description
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );


    $stmt->bind_param(
        "sssssss",
        $event_name,
        $event_type,
        $event_date,
        $start_time,
        $end_time,
        $venue,
        $description
    );


    if ($stmt->execute()) {

        $message = "Event added successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to add event.";
        $message_type = "danger";

    }

    $stmt->close();
}


// =====================================================
// EDIT EVENT
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["edit_event"])
) {

    $event_id = intval($_POST["event_id"]);

    $event_name = trim($_POST["event_name"]);
    $event_type = trim($_POST["event_type"]);
    $event_date = $_POST["event_date"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];
    $venue = trim($_POST["venue"]);
    $description = trim($_POST["description"]);


    $stmt = $conn->prepare(
        "UPDATE events
         SET
            event_name = ?,
            event_type = ?,
            event_date = ?,
            start_time = ?,
            end_time = ?,
            venue = ?,
            description = ?
         WHERE event_id = ?"
    );


    $stmt->bind_param(
        "sssssssi",
        $event_name,
        $event_type,
        $event_date,
        $start_time,
        $end_time,
        $venue,
        $description,
        $event_id
    );


    if ($stmt->execute()) {

        $message = "Event updated successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to update event.";
        $message_type = "danger";

    }

    $stmt->close();
}


// =====================================================
// GET EVENT FOR EDITING
// =====================================================

$edit_event = null;

if (isset($_GET["edit"])) {

    $edit_id = intval($_GET["edit"]);

    $stmt = $conn->prepare(
        "SELECT
            event_id,
            event_name,
            event_type,
            event_date,
            start_time,
            end_time,
            venue,
            description
         FROM events
         WHERE event_id = ?"
    );

    $stmt->bind_param("i", $edit_id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $edit_event = $result->fetch_assoc();

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
            event_id,
            event_name,
            event_type,
            event_date,
            start_time,
            end_time,
            venue,
            description
         FROM events
         WHERE event_name LIKE ?
            OR event_type LIKE ?
            OR venue LIKE ?
         ORDER BY event_date ASC"
    );


    $search_term = "%" . $search . "%";


    $stmt->bind_param(
        "sss",
        $search_term,
        $search_term,
        $search_term
    );


    $stmt->execute();

    $events = $stmt->get_result();

} else {

    $events = $conn->query(
        "SELECT
            event_id,
            event_name,
            event_type,
            event_date,
            start_time,
            end_time,
            venue,
            description
         FROM events
         ORDER BY event_date ASC"
    );

}

?>


<?php include '../includes/header.php'; ?>
<?php include '../includes/admin-navigationbar.php'; ?>


<section class="bg-primary text-white py-5">

    <div class="container">

        <h1>Manage Events</h1>

        <p class="lead">
            Manage tournaments, workshops and community tennis events.
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

                <h2>Tennis Events</h2>

                <p class="text-muted mb-0">
                    Events currently recorded in the system.
                </p>

            </div>


            <button
                type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#addEventModal"
            >

                <i class="bi bi-calendar-plus"></i>

                Add Event

            </button>

        </div>


        <!-- SEARCH -->

        <form
            method="GET"
            action="events.php"
            class="mb-4"
        >

            <div class="input-group">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search event, type or venue..."
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
                        href="events.php"
                        class="btn btn-secondary"
                    >
                        Clear
                    </a>

                <?php endif; ?>

            </div>

        </form>


        <!-- EVENTS -->

        <div class="row g-4">


            <?php if ($events->num_rows > 0): ?>


                <?php while (
                    $event = $events->fetch_assoc()
                ): ?>


                    <div class="col-md-6 col-lg-4">


                        <div class="card shadow h-100">


                            <div class="card-body">


                                <span class="badge bg-primary mb-3">

                                    <?php

                                    echo htmlspecialchars(
                                        $event["event_type"]
                                    );

                                    ?>

                                </span>


                                <h4>

                                    <?php

                                    echo htmlspecialchars(
                                        $event["event_name"]
                                    );

                                    ?>

                                </h4>


                                <hr>


                                <p>

                                    <i class="bi bi-calendar-event"></i>

                                    <strong>
                                        Date:
                                    </strong>

                                    <?php

                                    echo date(
                                        "d M Y",
                                        strtotime(
                                            $event["event_date"]
                                        )
                                    );

                                    ?>

                                </p>


                                <p>

                                    <i class="bi bi-clock"></i>

                                    <strong>
                                        Time:
                                    </strong>

                                    <?php

                                    echo date(
                                        "H:i",
                                        strtotime(
                                            $event["start_time"]
                                        )
                                    );

                                    ?>

                                    -

                                    <?php

                                    echo date(
                                        "H:i",
                                        strtotime(
                                            $event["end_time"]
                                        )
                                    );

                                    ?>

                                </p>


                                <p>

                                    <i class="bi bi-geo-alt"></i>

                                    <strong>
                                        Venue:
                                    </strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $event["venue"]
                                    );

                                    ?>

                                </p>


                                <p>

                                    <?php

                                    echo htmlspecialchars(
                                        $event["description"]
                                    );

                                    ?>

                                </p>


                                <div class="d-flex gap-2 mt-4">


                                    <a
                                        href="events.php?edit=<?php echo $event["event_id"]; ?>"
                                        class="btn btn-warning btn-sm flex-fill"
                                    >

                                        Edit

                                    </a>


                                    <a
                                        href="events.php?delete=<?php echo $event["event_id"]; ?>"
                                        class="btn btn-danger btn-sm flex-fill"
                                        onclick="return confirm('Are you sure you want to delete this event?');"
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

                        No events found.

                    </div>

                </div>


            <?php endif; ?>


        </div>

    </div>

</section>



<!-- ================================================= -->
<!-- ADD EVENT MODAL -->
<!-- ================================================= -->

<div
    class="modal fade"
    id="addEventModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Add New Event
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
                                Event Name
                            </label>

                            <input
                                type="text"
                                name="event_name"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Event Type
                            </label>

                            <select
                                name="event_type"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Event Type
                                </option>

                                <option value="Tournament">
                                    Tournament
                                </option>

                                <option value="Workshop">
                                    Workshop
                                </option>

                                <option value="League">
                                    League
                                </option>

                                <option value="Community Event">
                                    Community Event
                                </option>

                                <option value="Training">
                                    Training
                                </option>

                            </select>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Event Date
                            </label>

                            <input
                                type="date"
                                name="event_date"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Start Time
                            </label>

                            <input
                                type="time"
                                name="start_time"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                End Time
                            </label>

                            <input
                                type="time"
                                name="end_time"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Venue
                            </label>

                            <input
                                type="text"
                                name="venue"
                                class="form-control"
                                required
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
                        name="add_event"
                        class="btn btn-success"
                    >

                        Add Event

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>



<!-- ================================================= -->
<!-- EDIT EVENT MODAL -->
<!-- ================================================= -->

<?php if ($edit_event !== null): ?>

<div
    class="modal fade show"
    id="editEventModal"
    tabindex="-1"
    style="display: block;"
    aria-modal="true"
    role="dialog"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Edit Event
                </h5>

                <a
                    href="events.php"
                    class="btn-close"
                ></a>

            </div>


            <form method="POST">


                <div class="modal-body">


                    <input
                        type="hidden"
                        name="event_id"
                        value="<?php echo $edit_event["event_id"]; ?>"
                    >


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label">
                                Event Name
                            </label>

                            <input
                                type="text"
                                name="event_name"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_event["event_name"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Event Type
                            </label>

                            <select
                                name="event_type"
                                class="form-select"
                                required
                            >

                                <?php

                                $types = [
                                    "Tournament",
                                    "Workshop",
                                    "League",
                                    "Community Event",
                                    "Training"
                                ];

                                foreach ($types as $type):

                                ?>

                                    <option
                                        value="<?php echo $type; ?>"
                                        <?php
                                        if (
                                            $edit_event["event_type"]
                                            === $type
                                        ) {
                                            echo "selected";
                                        }
                                        ?>
                                    >

                                        <?php echo $type; ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Event Date
                            </label>

                            <input
                                type="date"
                                name="event_date"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_event["event_date"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Start Time
                            </label>

                            <input
                                type="time"
                                name="start_time"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_event["start_time"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                End Time
                            </label>

                            <input
                                type="time"
                                name="end_time"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_event["end_time"]); ?>"
                                required
                            >

                        </div>


                        <div class="col-12">

                            <label class="form-label">
                                Venue
                            </label>

                            <input
                                type="text"
                                name="venue"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_event["venue"]); ?>"
                                required
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
                            ><?php echo htmlspecialchars($edit_event["description"]); ?></textarea>

                        </div>


                    </div>

                </div>


                <div class="modal-footer">

                    <a
                        href="events.php"
                        class="btn btn-secondary"
                    >

                        Cancel

                    </a>


                    <button
                        type="submit"
                        name="edit_event"
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