<?php
require_once "admin-check.php";
require_once "../database/db.php";

$message = "";
$message_type = "";


// =====================================================
// DELETE PAYMENT
// =====================================================

if (isset($_GET["delete"])) {

    $payment_id = intval($_GET["delete"]);

    $stmt = $conn->prepare(
        "DELETE FROM payments WHERE payment_id = ?"
    );

    $stmt->bind_param("i", $payment_id);

    if ($stmt->execute()) {

        $message = "Payment deleted successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to delete payment.";
        $message_type = "danger";

    }

    $stmt->close();
}


// =====================================================
// ADD PAYMENT
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["add_payment"])
) {

    $member_id = intval($_POST["member_id"]);
    $amount = floatval($_POST["amount"]);
    $payment_date = $_POST["payment_date"];
    $payment_method = trim($_POST["payment_method"]);
    $reference_number = trim($_POST["reference_number"]);
    $payment_status = trim($_POST["payment_status"]);
    $notes = trim($_POST["notes"]);


    $stmt = $conn->prepare(
        "INSERT INTO payments
        (
            member_id,
            amount,
            payment_date,
            payment_method,
            reference_number,
            payment_status,
            notes
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );


    $stmt->bind_param(
        "idsssss",
        $member_id,
        $amount,
        $payment_date,
        $payment_method,
        $reference_number,
        $payment_status,
        $notes
    );


    if ($stmt->execute()) {

        $message = "Payment added successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to add payment.";
        $message_type = "danger";

    }

    $stmt->close();
}


// =====================================================
// EDIT PAYMENT
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["edit_payment"])
) {

    $payment_id = intval($_POST["payment_id"]);

    $member_id = intval($_POST["member_id"]);
    $amount = floatval($_POST["amount"]);
    $payment_date = $_POST["payment_date"];
    $payment_method = trim($_POST["payment_method"]);
    $reference_number = trim($_POST["reference_number"]);
    $payment_status = trim($_POST["payment_status"]);
    $notes = trim($_POST["notes"]);


    $stmt = $conn->prepare(
        "UPDATE payments
         SET
            member_id = ?,
            amount = ?,
            payment_date = ?,
            payment_method = ?,
            reference_number = ?,
            payment_status = ?,
            notes = ?
         WHERE payment_id = ?"
    );


    $stmt->bind_param(
        "idsssssi",
        $member_id,
        $amount,
        $payment_date,
        $payment_method,
        $reference_number,
        $payment_status,
        $notes,
        $payment_id
    );


    if ($stmt->execute()) {

        $message = "Payment updated successfully.";
        $message_type = "success";

    } else {

        $message = "Unable to update payment.";
        $message_type = "danger";

    }

    $stmt->close();
}


// =====================================================
// GET PAYMENT FOR EDITING
// =====================================================

$edit_payment = null;

if (isset($_GET["edit"])) {

    $edit_id = intval($_GET["edit"]);

    $stmt = $conn->prepare(
        "SELECT
            payment_id,
            member_id,
            amount,
            payment_date,
            payment_method,
            reference_number,
            payment_status,
            notes
         FROM payments
         WHERE payment_id = ?"
    );

    $stmt->bind_param("i", $edit_id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $edit_payment = $result->fetch_assoc();

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
            p.payment_id,
            p.member_id,
            p.amount,
            p.payment_date,
            p.payment_method,
            p.reference_number,
            p.payment_status,
            p.notes,
            CONCAT(m.first_name, ' ', m.last_name) AS member_name
         FROM payments p
         LEFT JOIN members m
            ON p.member_id = m.member_id
         WHERE
            m.first_name LIKE ?
            OR m.last_name LIKE ?
            OR p.reference_number LIKE ?
            OR p.payment_method LIKE ?
            OR p.payment_status LIKE ?
         ORDER BY p.payment_date DESC"
    );


    $search_term = "%" . $search . "%";


    $stmt->bind_param(
        "sssss",
        $search_term,
        $search_term,
        $search_term,
        $search_term,
        $search_term
    );


    $stmt->execute();

    $payments = $stmt->get_result();

} else {

    $payments = $conn->query(
        "SELECT
            p.payment_id,
            p.member_id,
            p.amount,
            p.payment_date,
            p.payment_method,
            p.reference_number,
            p.payment_status,
            p.notes,
            CONCAT(m.first_name, ' ', m.last_name) AS member_name
         FROM payments p
         LEFT JOIN members m
            ON p.member_id = m.member_id
         ORDER BY p.payment_date DESC"
    );

}


// =====================================================
// GET MEMBERS FOR DROPDOWN
// =====================================================

$members = $conn->query(
    "SELECT
        member_id,
        first_name,
        last_name
     FROM members
     ORDER BY first_name ASC, last_name ASC"
);

?>


<?php include '../includes/header.php'; ?>
<?php include '../includes/admin-navigationbar.php'; ?>


<!-- ================================================= -->
<!-- PAGE HEADER -->
<!-- ================================================= -->

<section class="bg-primary text-white py-5">

    <div class="container">

        <h1>Manage Payments</h1>

        <p class="lead">
            Record and manage member payments.
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

                <h2>Payment Records</h2>

                <p class="text-muted mb-0">
                    Payments currently recorded in the system.
                </p>

            </div>


            <button
                type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#addPaymentModal"
            >

                <i class="bi bi-credit-card"></i>

                Add Payment

            </button>

        </div>


        <!-- SEARCH -->

        <form
            method="GET"
            action="payments.php"
            class="mb-4"
        >

            <div class="input-group">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search member, reference, method or status..."
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
                        href="payments.php"
                        class="btn btn-secondary"
                    >

                        Clear

                    </a>

                <?php endif; ?>

            </div>

        </form>


        <!-- ================================================= -->
        <!-- PAYMENT TABLE -->
        <!-- ================================================= -->

        <div class="card shadow">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>

                                <th>Member</th>

                                <th>Amount</th>

                                <th>Date</th>

                                <th>Method</th>

                                <th>Reference</th>

                                <th>Status</th>

                                <th>Actions</th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php if ($payments->num_rows > 0): ?>


                            <?php while (
                                $payment =
                                $payments->fetch_assoc()
                            ): ?>


                                <tr>


                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $payment["payment_id"]
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $payment["member_name"]
                                                ?? "Unknown Member"
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <strong>

                                            R
                                            <?php

                                            echo number_format(
                                                $payment["amount"],
                                                2
                                            );

                                            ?>

                                        </strong>

                                    </td>


                                    <td>

                                        <?php

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $payment["payment_date"]
                                            )
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $payment["payment_method"]
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $payment["reference_number"]
                                        );

                                        ?>

                                    </td>


                                    <td>


                                        <?php

                                        $status =
                                            $payment["payment_status"];

                                        if ($status === "Paid") {

                                            $badge =
                                                "success";

                                        } elseif (
                                            $status === "Pending"
                                        ) {

                                            $badge =
                                                "warning";

                                        } elseif (
                                            $status === "Failed"
                                        ) {

                                            $badge =
                                                "danger";

                                        } else {

                                            $badge =
                                                "secondary";

                                        }

                                        ?>


                                        <span
                                            class="badge bg-<?php echo $badge; ?>"
                                        >

                                            <?php

                                            echo htmlspecialchars(
                                                $status
                                            );

                                            ?>

                                        </span>


                                    </td>


                                    <td>

                                        <div
                                            class="d-flex gap-1"
                                        >


                                            <a
                                                href="payments.php?edit=<?php echo $payment["payment_id"]; ?>"
                                                class="btn btn-warning btn-sm"
                                            >

                                                Edit

                                            </a>


                                            <a
                                                href="payments.php?delete=<?php echo $payment["payment_id"]; ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this payment?');"
                                            >

                                                Delete

                                            </a>


                                        </div>

                                    </td>


                                </tr>


                            <?php endwhile; ?>


                        <?php else: ?>


                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-4"
                                >

                                    No payment records found.

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
<!-- ADD PAYMENT MODAL -->
<!-- ================================================= -->

<div
    class="modal fade"
    id="addPaymentModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Add Payment
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


                        <!-- MEMBER -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Member
                            </label>

                            <select
                                name="member_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Member
                                </option>


                                <?php

                                $members->data_seek(0);

                                while (
                                    $member =
                                    $members->fetch_assoc()
                                ):

                                ?>

                                    <option
                                        value="<?php echo $member["member_id"]; ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $member["first_name"]
                                            . " "
                                            . $member["last_name"]
                                        );

                                        ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>


                        <!-- AMOUNT -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Amount (R)
                            </label>

                            <input
                                type="number"
                                name="amount"
                                class="form-control"
                                step="0.01"
                                min="0"
                                required
                            >

                        </div>


                        <!-- DATE -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Payment Date
                            </label>

                            <input
                                type="date"
                                name="payment_date"
                                class="form-control"
                                value="<?php echo date("Y-m-d"); ?>"
                                required
                            >

                        </div>


                        <!-- METHOD -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Payment Method
                            </label>

                            <select
                                name="payment_method"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Method
                                </option>

                                <option value="Cash">
                                    Cash
                                </option>

                                <option value="Bank Transfer">
                                    Bank Transfer
                                </option>

                                <option value="Card">
                                    Card
                                </option>

                                <option value="EFT">
                                    EFT
                                </option>

                                <option value="Other">
                                    Other
                                </option>

                            </select>

                        </div>


                        <!-- REFERENCE -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Reference Number
                            </label>

                            <input
                                type="text"
                                name="reference_number"
                                class="form-control"
                            >

                        </div>


                        <!-- STATUS -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Payment Status
                            </label>

                            <select
                                name="payment_status"
                                class="form-select"
                                required
                            >

                                <option value="Paid">
                                    Paid
                                </option>

                                <option value="Pending">
                                    Pending
                                </option>

                                <option value="Failed">
                                    Failed
                                </option>

                            </select>

                        </div>


                        <!-- NOTES -->

                        <div class="col-12">

                            <label class="form-label">
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                class="form-control"
                                rows="3"
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
                        name="add_payment"
                        class="btn btn-success"
                    >

                        Add Payment

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>



<!-- ================================================= -->
<!-- EDIT PAYMENT MODAL -->
<!-- ================================================= -->

<?php if ($edit_payment !== null): ?>

<div
    class="modal fade show"
    id="editPaymentModal"
    tabindex="-1"
    style="display: block;"
    aria-modal="true"
    role="dialog"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Edit Payment
                </h5>


                <a
                    href="payments.php"
                    class="btn-close"
                ></a>

            </div>


            <form method="POST">


                <div class="modal-body">


                    <input
                        type="hidden"
                        name="payment_id"
                        value="<?php echo $edit_payment["payment_id"]; ?>"
                    >


                    <div class="row g-3">


                        <!-- MEMBER -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Member
                            </label>

                            <select
                                name="member_id"
                                class="form-select"
                                required
                            >

                                <?php

                                $members->data_seek(0);

                                while (
                                    $member =
                                    $members->fetch_assoc()
                                ):

                                ?>

                                    <option
                                        value="<?php echo $member["member_id"]; ?>"
                                        <?php

                                        if (
                                            $edit_payment["member_id"]
                                            == $member["member_id"]
                                        ) {

                                            echo "selected";

                                        }

                                        ?>
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $member["first_name"]
                                            . " "
                                            . $member["last_name"]
                                        );

                                        ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>


                        <!-- AMOUNT -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Amount (R)
                            </label>

                            <input
                                type="number"
                                name="amount"
                                class="form-control"
                                step="0.01"
                                min="0"
                                value="<?php echo htmlspecialchars($edit_payment["amount"]); ?>"
                                required
                            >

                        </div>


                        <!-- DATE -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Payment Date
                            </label>

                            <input
                                type="date"
                                name="payment_date"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_payment["payment_date"]); ?>"
                                required
                            >

                        </div>


                        <!-- METHOD -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Payment Method
                            </label>

                            <select
                                name="payment_method"
                                class="form-select"
                                required
                            >

                                <?php

                                $methods = [
                                    "Cash",
                                    "Bank Transfer",
                                    "Card",
                                    "EFT",
                                    "Other"
                                ];

                                foreach (
                                    $methods
                                    as $method
                                ):

                                ?>

                                    <option
                                        value="<?php echo $method; ?>"
                                        <?php

                                        if (
                                            $edit_payment[
                                                "payment_method"
                                            ] === $method
                                        ) {

                                            echo "selected";

                                        }

                                        ?>
                                    >

                                        <?php echo $method; ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- REFERENCE -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Reference Number
                            </label>

                            <input
                                type="text"
                                name="reference_number"
                                class="form-control"
                                value="<?php echo htmlspecialchars($edit_payment["reference_number"]); ?>"
                            >

                        </div>


                        <!-- STATUS -->

                        <div class="col-md-6">

                            <label class="form-label">
                                Payment Status
                            </label>

                            <select
                                name="payment_status"
                                class="form-select"
                                required
                            >

                                <?php

                                $statuses = [
                                    "Paid",
                                    "Pending",
                                    "Failed"
                                ];

                                foreach (
                                    $statuses
                                    as $status
                                ):

                                ?>

                                    <option
                                        value="<?php echo $status; ?>"
                                        <?php

                                        if (
                                            $edit_payment[
                                                "payment_status"
                                            ] === $status
                                        ) {

                                            echo "selected";

                                        }

                                        ?>
                                    >

                                        <?php echo $status; ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- NOTES -->

                        <div class="col-12">

                            <label class="form-label">
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                class="form-control"
                                rows="3"
                            ><?php echo htmlspecialchars($edit_payment["notes"]); ?></textarea>

                        </div>


                    </div>

                </div>


                <div class="modal-footer">


                    <a
                        href="payments.php"
                        class="btn btn-secondary"
                    >

                        Cancel

                    </a>


                    <button
                        type="submit"
                        name="edit_payment"
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