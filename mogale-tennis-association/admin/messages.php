<?php
require_once "admin-check.php";
require_once "../database/db.php";


// =====================================================
// DELETE MESSAGE
// =====================================================

if (isset($_GET["delete"])) {

    $message_id = intval($_GET["delete"]);

    $stmt = $conn->prepare(
        "DELETE FROM messages WHERE message_id = ?"
    );

    $stmt->bind_param("i", $message_id);

    $stmt->execute();

    $stmt->close();

}


// =====================================================
// GET MESSAGES
// =====================================================

$messages = $conn->query(
    "SELECT
        message_id,
        name,
        email,
        phone,
        subject,
        message,
        created_at
     FROM messages
     ORDER BY created_at DESC"
);

?>


<?php include '../includes/header.php'; ?>
<?php include '../includes/admin-navigationbar.php'; ?>


<!-- ================================================= -->
<!-- PAGE HEADER -->
<!-- ================================================= -->

<section class="bg-primary text-white py-5">

    <div class="container">

        <h1>Contact Messages</h1>

        <p class="lead mb-0">

            View enquiries submitted through the
            Mogale Tennis Association website.

        </p>

    </div>

</section>


<!-- ================================================= -->
<!-- MESSAGES -->
<!-- ================================================= -->

<section class="py-5">

    <div class="container">


        <?php if (
            $messages
            && $messages->num_rows > 0
        ): ?>


            <div class="row g-4">


                <?php while (
                    $msg =
                    $messages->fetch_assoc()
                ): ?>


                    <div class="col-lg-6">


                        <div class="card shadow-sm h-100">


                            <div class="card-header">


                                <div
                                    class="d-flex
                                    justify-content-between
                                    align-items-center"
                                >

                                    <strong>

                                        <?php

                                        echo htmlspecialchars(
                                            $msg["subject"]
                                        );

                                        ?>

                                    </strong>


                                    <small class="text-muted">

                                        <?php

                                        echo date(
                                            "d M Y H:i",
                                            strtotime(
                                                $msg["created_at"]
                                            )
                                        );

                                        ?>

                                    </small>

                                </div>

                            </div>


                            <div class="card-body">


                                <p>

                                    <strong>
                                        From:
                                    </strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $msg["name"]
                                    );

                                    ?>

                                </p>


                                <p>

                                    <strong>
                                        Email:
                                    </strong>

                                    <a
                                        href="mailto:<?php echo htmlspecialchars($msg["email"]); ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $msg["email"]
                                        );

                                        ?>

                                    </a>

                                </p>


                                <?php if (
                                    !empty($msg["phone"])
                                ): ?>

                                    <p>

                                        <strong>
                                            Phone:
                                        </strong>

                                        <?php

                                        echo htmlspecialchars(
                                            $msg["phone"]
                                        );

                                        ?>

                                    </p>

                                <?php endif; ?>


                                <hr>


                                <p class="mb-0">

                                    <?php

                                    echo nl2br(
                                        htmlspecialchars(
                                            $msg["message"]
                                        )
                                    );

                                    ?>

                                </p>


                            </div>


                            <div class="card-footer bg-white">


                                <a
                                    href="messages.php?delete=<?php echo $msg["message_id"]; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this message?');"
                                >

                                    Delete Message

                                </a>


                            </div>


                        </div>


                    </div>


                <?php endwhile; ?>


            </div>


        <?php else: ?>


            <div class="alert alert-info text-center">

                No messages have been received.

            </div>


        <?php endif; ?>


    </div>

</section>


<?php include '../includes/footer.php'; ?>