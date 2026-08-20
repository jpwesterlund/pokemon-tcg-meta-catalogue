<?php include 'header.php'; ?>

<?php
$deckJson = file_get_contents('decks.json');
$deckData = json_decode($deckJson, true);

if (!is_array($deckData)) {
  $deckData = [];
}
?>

<section>
  <h2 class="masterball-heading">Format Snapshot: Destined Rivals / Post-NAIC / Pre-Worlds 2025</h2>
  <p>
    This catalogue was originally built as a meta guide for the Pokemon TCG, post-NAIC, pre-World Championships 2025.
    This format was the <strong>Destined Rivals</strong> format; the set was released on
    May 30, 2025. The format saw several new additions to the competitive card pool: 
    Marnie’s Grimmsnarl as a new competitive archetype, Shaymin a new support Pokemon that
    provides bench protection, and a brand new Team Rocket’s supporter package.
  </p>

  <p>
    Since Pokemon TCG formats rotate annually, this site is presented as an archived format sample rather than
    a live tier list. For readability, deck profiles are kept in the present tense.
  </p>

  <p>
    <strong>The goal of the project is to demonstrate a PHP driven catalogue structure, reusable page 
    components, JSON based deck data, and interactive filtering.</strong>
</p>
</section>

<section class="deck-filter" aria-labelledby="deck-filter-heading">
  <h2 id="deck-filter-heading" class="ultraball-heading">Filter Decks by Tag: Find Something to Play!</h2>

  <div class="tag-buttons" role="group" aria-label="Deck archetype filters">
    <button type="button" class="tag-btn" data-tag="aggro">Aggro</button>
    <button type="button" class="tag-btn" data-tag="spread">Spread</button>
    <button type="button" class="tag-btn" data-tag="toolbox">Toolbox</button>
    <button type="button" class="tag-btn" data-tag="singleprize">Single Prize</button>
    <button type="button" class="tag-btn" data-tag="control">Control</button>
    <button type="button" class="tag-btn" data-tag="tera">Tera</button>
    <button type="button" class="tag-btn" data-tag="bigbasics">Big Basics</button>
    <button type="button" class="tag-btn" data-tag="ancient">Ancient</button>
    <button type="button" class="tag-btn" data-tag="future">Future</button>
    <button type="button" class="tag-btn" data-tag="rogue">Rogue</button>
    <button type="button" class="tag-btn" data-tag="stage2">Stage 2</button>
    <button type="button" class="tag-btn" data-tag="evolution">Evolution</button>
    <button type="button" class="tag-btn" data-tag="trainerpkmn">Trainer's Pokémon</button>
  </div>

  <div class="filter-controls">
    <button type="button" id="clear-tags" class="tag-btn clear-filter-btn">Clear Filters</button>
  </div>
</section>

<section aria-labelledby="deck-table-heading">
  <h2 id="deck-table-heading" class="greatball-heading">Top 10 Decks in the Archived Meta</h2>

  <?php if (count($deckData) > 0): ?>
    <table class="deck-table">
      <thead>
        <tr>
          <th scope="col">Archetype</th>
          <th scope="col">Meta Share</th>
        </tr>
      </thead>

      <tbody id="deck-table-body">
        <?php foreach ($deckData as $deck): ?>
          <?php
          $deckName = htmlspecialchars($deck['name'] ?? 'Unknown Deck', ENT_QUOTES, 'UTF-8');
          $deckLink = htmlspecialchars($deck['link'] ?? '#', ENT_QUOTES, 'UTF-8');
          $metaShare = htmlspecialchars($deck['metaShare'] ?? 'N/A', ENT_QUOTES, 'UTF-8');

          $tags = $deck['tags'] ?? [];
          $tagList = htmlspecialchars(implode(' ', $tags), ENT_QUOTES, 'UTF-8');
          ?>

          <tr data-tags="<?php echo $tagList; ?>">
            <td>
              <span class="pokeball-icon">
                <a href="<?php echo $deckLink; ?>"><?php echo $deckName; ?></a>
              </span>
            </td>
            <td><?php echo $metaShare; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <p id="no-results-message" class="no-results-message" hidden>
      No decks match that filter. Try another tag or clear the filters.
    </p>
  <?php else: ?>
    <p>
      Deck data is currently unavailable. Please check that <code>decks.json</code> exists and contains valid deck entries.
    </p>
  <?php endif; ?>
</section>

<section>
  <h2 class="nestball-heading">Project Context</h2>
  <ul>
    <li>
      This project was originally created for an Advanced Web Development course.
      It uses PHP includes for shared layout, JSON for deck data, and JavaScript for client side filtering.
    </li>
    <li>
      The deck profiles were written for a specific competitive format and is not updated for the current meta.
      This is done purposely to keep the project stable for portfolio review.
    </li>
  </ul>
</section>

<p>
  <a href="admin.php" class="home-button">View Admin Demo</a>
</p>

<script>
  const filterButtons = document.querySelectorAll('.tag-btn[data-tag]');
  const deckRows = document.querySelectorAll('#deck-table-body tr');
  const clearButton = document.getElementById('clear-tags');
  const noResultsMessage = document.getElementById('no-results-message');

  function updateNoResultsMessage() {
    if (!noResultsMessage) return;

    const visibleRows = Array.from(deckRows).filter(row => row.style.display !== 'none');
    noResultsMessage.hidden = visibleRows.length > 0;
  }

  function clearActiveFilters() {
    filterButtons.forEach(button => {
      button.classList.remove('active');
      button.setAttribute('aria-pressed', 'false');
    });
  }

  filterButtons.forEach(button => {
    button.setAttribute('aria-pressed', 'false');

    button.addEventListener('click', () => {
      const selectedTag = button.dataset.tag;

      clearActiveFilters();
      button.classList.add('active');
      button.setAttribute('aria-pressed', 'true');

      deckRows.forEach(row => {
        const rowTags = row.dataset.tags.split(' ');
        row.style.display = rowTags.includes(selectedTag) ? '' : 'none';
      });

      updateNoResultsMessage();
    });
  });

  if (clearButton) {
    clearButton.addEventListener('click', () => {
      clearActiveFilters();

      deckRows.forEach(row => {
        row.style.display = '';
      });

      updateNoResultsMessage();
    });
  }
</script>

<?php include 'footer.php'; ?>