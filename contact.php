<?php
$confirmation = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if (strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (strlen($message) < 10) {
        $errors[] = "Message must be at least 10 characters.";
    }

    if (empty($errors)) {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        /*
         * Portfolio note:
         * This form validates submitted data and displays a confirmation message.
         * Email delivery is intentionally disabled for the portfolio version to avoid
         * exposing a live mail endpoint in a public demo.
         */
        $confirmation = "Thanks, {$safeName}! This demo form successfully validated your message.";
    }
}
?>

<?php include 'header.php'; ?>

<section class="about-section">
    <div class="card-stack">
        <img src="images/jigglypuff.png" alt="Jigglypuff illustration" class="float-right">
    </div>

    <h2 class="ultraball-heading">About This Project</h2>

    <p>
        Hi! I’m J.P., a recent SUNY Empire graduate in computer science and Pokemon TCG player from Rochester, New York.
        I built this catalogue as an Advanced Web Development project and later refined it as a portfolio piece.
    </p>

    <p>
        The site presents an archived snapshot of the Pokemon TCG meta game leading into Worlds 2025.
        A few examples of the components this project demonstrates (but not limited to): 
        PHP includes for reusable layout, JSON based deck data, client side filtering, and individual deck
        profile pages.
    </p>

    <p>
        I chose the Pokemon TCG as the topic because it gave the project a real domain with structured data
        and a clear user goal: helping players browse competitive deck archetypes.
    </p>

    <p>
        My favorite Pokemon is <strong>Jigglypuff</strong>, mostly because I think we look like twins.
    </p>
</section>

<section class="contact-section">
    <h2 class="greatball-heading">Contact Demo</h2>

    <p>
        This form demonstrates basic client side and server side validation. In the portfolio version,
        messages are validated but not emailed.
    </p>

    <?php if (!empty($errors)): ?>
        <div class="form-message form-error" role="alert">
            <p>Please fix the following:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($confirmation)): ?>
        <p class="form-message form-success" role="status">
            <?php echo $confirmation; ?>
        </p>
    <?php endif; ?>

    <form id="contactForm" method="POST" novalidate>
        <div class="form-field">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required minlength="2">
        </div>

        <div class="form-field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-field">
            <label for="message">Message</label>
            <textarea id="message" name="message" required minlength="10"></textarea>
        </div>

        <button type="submit" class="form-button">Send Demo Message</button>
    </form>
</section>

<p>
    <a href="index.php" class="home-button">← Return to Homepage</a>
</p>

<script>
    const contactForm = document.getElementById('contactForm');

    if (contactForm) {
        contactForm.addEventListener('submit', function (event) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const message = document.getElementById('message').value.trim();

            const errors = [];

            if (name.length < 2) {
                errors.push("Name must be at least 2 characters.");
            }

            if (!email.match(/^[^@]+@[^@]+\.[a-z]{2,}$/i)) {
                errors.push("Please enter a valid email address.");
            }

            if (message.length < 10) {
                errors.push("Message must be at least 10 characters.");
            }

            if (errors.length > 0) {
                alert("Please fix the following:\n\n" + errors.join("\n"));
                event.preventDefault();
            }
        });
    }
</script>

<?php include 'footer.php'; ?>