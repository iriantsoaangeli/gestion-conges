<?= $this->extend('layouts/employe') ?>

<?= $this->section('breadcrumb') ?>
<a href="<?= base_url('employe/dashboard') ?>">Accueil</a>
<i class="bi bi-chevron-right" style="font-size:.6rem"></i>
Nouvelle demande
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start" class="form-layout">

  <!-- Formulaire principal -->
  <div>
    <div class="form-section">
      <h3>Détails de la demande</h3>

      <?= form_open('employe/conges/store') ?>
      <?= csrf_field() ?>

      <div class="f-group">
        <label class="f-label" for="type_conge_id">Type de congé <span style="color:var(--danger)">*</span></label>
        <select name="type_conge_id" id="type_conge_id" class="f-select <?= isset($validation) && $validation->hasError('type_conge_id') ? 'is-invalid' : '' ?>">
          <option value="">-- Choisir un type --</option>
          <?php foreach ($types_conge ?? [] as $t): ?>
            <option value="<?= $t['id'] ?>" <?= old('type_conge_id') == $t['id'] ? 'selected' : '' ?>>
              <?= esc($t['libelle']) ?> (<?= $t['solde_restant'] ?> j restants)
            </option>
          <?php endforeach; ?>
        </select>
        <?php if (isset($validation) && $validation->hasError('type_conge_id')): ?>
          <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= $validation->getError('type_conge_id') ?></div>
        <?php endif; ?>
      </div>

      <div class="form-grid-2">
        <div class="f-group">
          <label class="f-label" for="date_debut">Date de début <span style="color:var(--danger)">*</span></label>
          <input type="date" id="date_debut" name="date_debut" class="f-input" value="<?= old('date_debut') ?>" required/>
          <?php if (isset($validation) && $validation->hasError('date_debut')): ?>
            <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= $validation->getError('date_debut') ?></div>
          <?php endif; ?>
        </div>
        <div class="f-group">
          <label class="f-label" for="date_fin">Date de fin <span style="color:var(--danger)">*</span></label>
          <input type="date" id="date_fin" name="date_fin" class="f-input" value="<?= old('date_fin') ?>" required/>
          <?php if (isset($validation) && $validation->hasError('date_fin')): ?>
            <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= $validation->getError('date_fin') ?></div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Calcul auto JS -->
      <div class="f-computed" id="calcul-jours" style="display:none">
        <div class="f-computed-num" id="nb-jours">0</div>
        <div class="f-computed-label">
          jours calendaires calculés<br>
          <span id="periode-label" style="font-size:.7rem;opacity:.7"></span>
        </div>
      </div>

      <div class="f-group">
        <label class="f-label" for="motif">Motif (optionnel)</label>
        <textarea name="motif" id="motif" class="f-textarea" placeholder="Précisez le motif de votre demande si nécessaire..."><?= old('motif') ?></textarea>
        <div class="f-hint">Le motif est visible par le responsable RH.</div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-forest"><i class="bi bi-send"></i> Soumettre la demande</button>
        <a href="<?= base_url('employe/dashboard') ?>" class="btn-secondary"><i class="bi bi-x"></i> Annuler</a>
      </div>

      <?= form_close() ?>
    </div>
  </div>

  <!-- Panneau latéral -->
  <div style="display:flex;flex-direction:column;gap:1rem">

    <!-- Soldes actuels -->
    <div class="data-card" style="margin:0">
      <div class="data-card-head">
        <h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Vos soldes actuels</h3>
      </div>
      <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.75rem">
        <?php foreach ($soldes ?? [] as $s): ?>
          <?php $pct = ($s['total'] > 0) ? round(($s['restant'] / $s['total']) * 100) : 0; ?>
          <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
              <span style="font-size:.8rem;color:var(--ink)"><?= esc($s['type']) ?></span>
              <span style="font-family:'DM Mono',monospace;font-size:.8rem;color:<?= $pct <= 20 ? 'var(--warn)' : 'var(--forest)' ?>;font-weight:500"><?= $s['restant'] ?> j</span>
            </div>
            <div class="solde-bar">
              <div class="solde-fill <?= $pct <= 20 ? 'warn' : '' ?>" style="width:<?= $pct ?>%"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Info solde -->
    <div class="flash flash-info" style="margin:0">
      <i class="bi bi-info-circle-fill"></i>
      <span style="font-size:.8rem">Le solde est déduit uniquement à l'approbation de votre responsable.</span>
    </div>

    <!-- Rappel des règles -->
    <div style="background:var(--cream);border:1px solid var(--border);border-radius:8px;padding:.85rem 1rem">
      <div style="font-size:.78rem;font-weight:500;color:var(--ink);margin-bottom:.5rem">
        <i class="bi bi-clipboard-check" style="color:var(--forest);margin-right:5px"></i>Rappel des règles
      </div>
      <ul style="margin:0;padding-left:1rem;font-size:.75rem;color:var(--muted);line-height:1.7">
        <li>Préavis minimum : 48h avant la date de début</li>
        <li>Pas de chevauchement avec une demande en cours</li>
        <li>Solde insuffisant = demande refusée automatiquement</li>
      </ul>
    </div>

  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Calcul automatique du nombre de jours
const debut = document.getElementById('date_debut');
const fin   = document.getElementById('date_fin');
const box   = document.getElementById('calcul-jours');
const nbEl  = document.getElementById('nb-jours');
const perEl = document.getElementById('periode-label');

function calculer() {
  if (!debut.value || !fin.value) { box.style.display = 'none'; return; }
  const d1 = new Date(debut.value);
  const d2 = new Date(fin.value);
  if (d2 < d1) { box.style.display = 'none'; return; }
  const diff = Math.round((d2 - d1) / (1000 * 60 * 60 * 24)) + 1;
  nbEl.textContent = diff;
  const opts = { weekday:'long', day:'numeric', month:'long', year:'numeric' };
  perEl.textContent = 'du ' + d1.toLocaleDateString('fr-FR', opts) + ' au ' + d2.toLocaleDateString('fr-FR', opts);
  box.style.display = 'flex';
}

debut.addEventListener('change', calculer);
fin.addEventListener('change', calculer);
</script>
<?= $this->endSection() ?>
