<?php
session_start();

$adminPasswordHash = '$2y$12$M08ISDmvIQ6RhPGv1ia5wexL0IscVrjMX/Pf9tdtliSKEUD65Lqh6';
$deckFile = 'decks.json';

$error = '';
$statusMessage = '';

function loadDeckData($deckFile) {
    if (!file_exists($deckFile)) {
        return [];
    }

    $json = file_get_contents($deckFile);
    $data = json_decode($json, true);

    return is_array($data) ? $data : [];
}

function cleanText($value) {
    return trim($value ?? '');
}

function cleanTags($tagString) {
    $tags = explode(',', strtolower($tagString ?? ''));

    return array_values(array_filter(array_map('trim', $tags)));
}

function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $submittedPassword = $_POST['password'] ?? '';

    if (password_verify($submittedPassword, $adminPasswordHash)) {
        $_SESSION['logged_in'] = true;
        header('Location: admin.php');
        exit;
    }

    $error = 'Incorrect password. Try again.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!isLoggedIn()) {
        header('Location: admin.php');
        exit;
    }

    $action = cleanText($_POST['action'] ?? '');
    $name = cleanText($_POST['name'] ?? '');
    $link = cleanText($_POST['link'] ?? '');
    $metaShare = cleanText($_POST['metaShare'] ?? '');
    $tags = cleanTags($_POST['tags'] ?? '');

    if ($action === 'add') {
        $statusMessage = "Demo action received: Add Deck. In a production version, this would add {$name} to the JSON data source.";
    } elseif ($action === 'edit') {
        $statusMessage = "Demo action received: Edit Deck. In a production version, this would update {$name} in the JSON data source.";
    } elseif ($action === 'delete') {
        $statusMessage = "Demo action received: Delete Deck. In a production version, this would remove {$name} from the JSON data source.";
    } else {
        $statusMessage = "Demo action received. No live data was changed.";
    }
}

$deckData = loadDeckData($deckFile);
?>

<?php include 'header.php'; ?>

<section>
    <h2 class="greatball-heading">Admin Demo</h2>

    <p>
        This page demonstrates a simple session based admin workflow for managing deck data stored in
        <code>decks.json</code>. It is included as a portfolio demo, not as a production ready content management system.
    </p>

    <p>
        For portfolio safety, this admin panel is read only. Submitted actions are validated and acknowledged,
        but they do not write changes to the live JSON file.
    </p>
</section>

<?php if (!isLoggedIn()): ?>
    <section>
        <h2 class="ultraball-heading">Enter Demo Password</h2>

        <p>
            Demo password: <strong>demo123</strong>
        </p>

        <?php if (!empty($error)): ?>
            <p class="form-message form-error" role="alert">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="login" value="1">

            <div class="form-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="form-button">Log In</button>
        </form>
    </section>

    <p>
        <a href="index.php" class="home-button">← Return to Homepage</a>
    </p>
<?php else: ?>

    <?php if (!empty($statusMessage)): ?>
        <p class="form-message form-success" role="status">
            <?php echo htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php endif; ?>

    <section>
        <h2 class="ultraball-heading">Manage Decks</h2>

        <p>
            Use the forms below to simulate adding, editing, or deleting deck entries. These forms demonstrate
            server-side POST handling without modifying the live project data.
        </p>
    </section>

    <section>
        <h3>Add Deck</h3>

        <form method="POST">
            <input type="hidden" name="action" value="add">

            <div class="form-field">
                <label for="add-name">Name</label>
                <input type="text" id="add-name" name="name" required>
            </div>

            <div class="form-field">
                <label for="add-link">Link</label>
                <input type="text" id="add-link" name="link" required>
            </div>

            <div class="form-field">
                <label for="add-meta-share">Meta Share</label>
                <input type="text" id="add-meta-share" name="metaShare" required>
            </div>

            <div class="form-field">
                <label for="add-tags">Tags, comma-separated</label>
                <input type="text" id="add-tags" name="tags">
            </div>

            <button type="submit" class="form-button">Simulate Add Deck</button>
        </form>
    </section>

    <section>
        <h3>Edit Existing Deck</h3>

        <form method="POST">
            <input type="hidden" name="action" value="edit">

            <div class="form-field">
                <label for="edit-name">Name, must match exactly</label>
                <input type="text" id="edit-name" name="name" required>
            </div>

            <div class="form-field">
                <label for="edit-link">New Link, optional</label>
                <input type="text" id="edit-link" name="link">
            </div>

            <div class="form-field">
                <label for="edit-meta-share">New Meta Share, optional</label>
                <input type="text" id="edit-meta-share" name="metaShare">
            </div>

            <div class="form-field">
                <label for="edit-tags">New Tags, comma-separated</label>
                <input type="text" id="edit-tags" name="tags">
            </div>

            <button type="submit" class="form-button">Simulate Edit Deck</button>
        </form>
    </section>

    <section>
        <h3>Delete Deck</h3>

        <form method="POST">
            <input type="hidden" name="action" value="delete">

            <div class="form-field">
                <label for="delete-name">Deck Name to Delete</label>
                <input type="text" id="delete-name" name="name" required>
            </div>

            <button type="submit" class="form-button">Simulate Delete Deck</button>
        </form>
    </section>

    <section>
        <h3>Current Decks</h3>

        <?php if (count($deckData) > 0): ?>
            <ul>
                <?php foreach ($deckData as $deck): ?>
                    <li>
                        <strong>
                            <?php echo htmlspecialchars($deck['name'] ?? 'Unknown Deck', ENT_QUOTES, 'UTF-8'); ?>
                        </strong>
                        —
                        <?php echo htmlspecialchars($deck['metaShare'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?>
                        —
                        Tags:
                        <?php
                        $tags = $deck['tags'] ?? [];
                        echo htmlspecialchars(implode(', ', $tags), ENT_QUOTES, 'UTF-8');
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No deck data is currently available.</p>
        <?php endif; ?>
    </section>

    <form method="POST" action="logout.php">
        <button type="submit" class="form-button">Log Out</button>
    </form>

    <p>
        <a href="index.php" class="home-button">← Return to Homepage</a>
    </p>
<?php endif; ?>

<?php include 'footer.php'; ?>