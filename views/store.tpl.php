<section class="page-section cta">
  <div class="container">
    <div class="row">
      <div class="col-xl-9 mx-auto">
        <div class="cta-inner text-center rounded">
          <h2 class="section-heading mb-5">
            <span class="section-heading-upper">Come On In</span>
            <span class="section-heading-lower">We're Open</span>
          </h2>
          <ul class="list-unstyled list-hours mb-5 text-left mx-auto">
            <?php foreach ($viewData['opening_hours'] as $key => $currentOpeningHour) : ?>
              <?php // todo rajouter la fonctionnalité de "highlight" du jour actuel
              // en utilisant PHP ( ajouter la class today ) si on est le bon jour
              // cf https://www.php.net/manual/en/function.date.php
              // et https://www.php.net/manual/en/datetime.format.php
              ?>
            <li class="list-unstyled-item list-hours-item d-flex">
              <?= $currentOpeningHour['day']; ?>
              <span class="ml-auto"><?= $currentOpeningHour['open_hours']; ?></span>
            </li>
            <?php endforeach; ?>
          </ul>
          <p class="address mb-5">
            <em>
              <strong><?= $viewData['current_address']['street']; ?></strong>
              <br>
              <?= $viewData['current_address']['city']; ?>
            </em>
          </p>
          <p class="mb-0">
            <small>
              <em>Call Anytime</em>
            </small>
            <br>
            (317) 585-8468
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Script to highlight the active date in the hours list -->
<script>
  // TODO réactiver cette fonctionnalité
  // $('.list-hours li').eq(new Date().getDay()).addClass('today');
</script>