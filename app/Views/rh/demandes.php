<?= $this->extend('layouts/rh') ?>

<?= $this->section('breadcrumb') ?>
<a href="<?= base_url('rh/dashboard') ?>">Accueil</a>
<i class="bi bi-chevron-right" style="font-size:.6rem"></i>
Demandes
<?= $this->endSection() ?>

<?= $this->section('topbar_actions') ?>
<?php if ($nb_attente > 0): ?>
  <span style="font-size:.8rem;background:var(--warn-bg);border:1px solid var(--warn-br);border-radius:6px;padding:5px 10px;display:flex;align-items:center;gap:5px;color:var(--warn)">
    <i class="bi bi-hourglass-split"></i> <?= $nb_attente ?> en attente
  </span>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Filtres -->
<div style="display:flex;gap:8px;margin-bottom:1.25rem;flex-wrap:wrap">
  <button class="filtre-btn active" onclick="filtrer(this,'')"
    style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--forest);background:var(--forest);color:var(--white);cursor:pointer">
    Tous (<?= $total ?>)
  </button>
  <button class="filtre-btn" onclick="filtrer(this,'en_attente')"
    style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">
    En attente (<?= $nb_attente ?>)
  </button>
  <button class="filtre-btn" onclick="filtrer(this,'approuvee')"
    style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">
    Approuvées (<?= $nb_approuvees ?>)
  </button>
  <button class="filtre-btn" onclick="filtrer(this,'refusee')"
    style="padding:6px 14px;border-radius:20px;font-size:.8rem;font-weight:500;border:1.5px solid var(--border);background:var(--white);color:var(--muted);cursor:pointer">
    Refusées (<?= $nb_refusees ?>)
  </button>
  <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto;margin-left:auto" onchange="filtrerDept(this.value)">
    <option value="">Tous les départements</option>
    <?php foreach ($departements ?? [] as $dept): ?>
      <option value="<?= esc($dept) ?>"><?= esc($dept) ?></option>
    <?php endforeach; ?>
  </select>
</div>

<div class="data-card">
  <div class="data-card-head"><h3>Toutes les demandes</h3></div>
  <table class="tbl">
    <thead>
      <tr>
        <th>Employé</th>
        <th>Type</th>
        <th>Période</th>
        <th>Durée</th>
        <th>Solde dispo</th>
        <th>Statut</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($demandes)): ?>
        <tr><td colspan="7"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande trouvée.</p></div></td></tr>
      <?php else: ?>
        <?php foreach ($demandes as $d): ?>
          <tr data-statut="<?= esc($d['statut']) ?>" data-dept="<?= esc($d['departement']) ?>">
            <td>
              <div class="profile-row">
                <div class="avatar av-green" style="width:32px;height:32px;font-size:.7rem">
                  <?= strtoupper(substr($d['prenom'], 0, 1) . substr($d['nom'], 0, 1)) ?>
                </div>
                <div class="profile-info">
                  <div class="pname"><?= esc($d['prenom']) ?> <?= esc($d['nom']) ?></div>
                  <div class="pdept"><?= esc($d['departement']) ?></div>
                </div>
              </div>
            </td>
            <td><span class="type-badge <?= esc($d['badge_class']) ?>"><?= esc($d['type_label']) ?></span></td>
            <td class="td-muted" style="font-size:.8rem"><?= esc($d['date_debut_fmt']) ?> – <?= esc($d['date_fin_fmt']) ?></td>
            <td class="td-mono"><?= $d['nb_jours'] ?> j</td>
            <td>
              <?php if ($d['solde_ok']): ?>
                <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--success);font-weight:500"><?= $d['solde_dispo'] ?> j</span>
                <span style="font-size:.72rem;color:var(--muted)"> dispo</span>
              <?php else: ?>
                <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--warn);font-weight:500"><?= $d['solde_dispo'] ?> j</span>
                <span style="font-size:.72rem;color:var(--danger)"> ⚠ insuffisant</span>
              <?php endif; ?>
            </td>
            <td><span class="statut <?= esc($d['statut_class']) ?>"><?= esc($d['statut_label']) ?></span></td>
            <td>
              <?php if ($d['statut'] === 'en_attente'): ?>
                <div class="action-btns">
                  <?= form_open('rh/demandes/approuver/' . $d['id'], ['style' => 'display:inline']) ?>
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-sm btn-approve" <?= !$d['solde_ok'] ? 'disabled style="opacity:.4;cursor:not-allowed"' : '' ?>>
                      <i class="bi bi-check-lg"></i> Approuver
                    </button>
                  <?= form_close() ?>
                  <button class="btn-sm btn-refuse" onclick="ouvrirRefus(<?= $d['id'] ?>, '<?= esc($d['prenom']) ?> <?= esc($d['nom']) ?>', <?= $d['nb_jours'] ?>, '<?= esc($d['date_debut_fmt']) ?>','<?= esc($d['date_fin_fmt']) ?>', '<?= esc($d['type_label']) ?>', <?= $d['solde_dispo'] ?>)">
                    <i class="bi bi-x-lg"></i> Refuser
                  </button>
                </div>
              <?php else: ?>
                <span class="td-muted" style="font-size:.75rem">Traité</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal refus -->
<div id="modal-refus" style="display:none;margin-top:1.5rem">
  <div class="form-section" style="border-color:var(--danger-br);background:var(--danger-bg)">
    <h3 style="color:var(--danger)"><i class="bi bi-x-circle"></i> Confirmer le refus — <span id="refus-nom"></span></h3>
    <div id="refus-detail" style="font-size:.875rem;color:var(--ink);margin-bottom:1rem"></div>
    <?= form_open('rh/demandes/refuser', ['id' => 'form-refus']) ?>
      <?= csrf_field() ?>
      <input type="hidden" name="demande_id" id="refus-id"/>
      <div class="f-group">
        <label class="f-label">Commentaire pour l'employé (optionnel)</label>
        <textarea name="commentaire" class="f-textarea" placeholder="Ex : Solde insuffisant, veuillez contacter les RH."></textarea>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn-sm btn-refuse" style="padding:9px 16px;font-size:.875rem">
          <i class="bi bi-x-lg"></i> Confirmer le refus
        </button>
        <button type="button" class="btn-secondary" onclick="document.getElementById('modal-refus').style.display='none'">
          <i class="bi bi-arrow-left"></i> Annuler
        </button>
      </div>
    <?= form_close() ?>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function filtrer(btn, statut) {
  document.querySelectorAll('.filtre-btn').forEach(b => {
    b.style.background = 'var(--white)';
    b.style.color = 'var(--muted)';
    b.style.borderColor = 'var(--border)';
  });
  btn.style.background = 'var(--forest)';
  btn.style.color = 'var(--white)';
  btn.style.borderColor = 'var(--forest)';
  document.querySelectorAll('tbody tr[data-statut]').forEach(tr => {
    tr.style.display = (!statut || tr.dataset.statut === statut) ? '' : 'none';
  });
}

function filtrerDept(dept) {
  document.querySelectorAll('tbody tr[data-dept]').forEach(tr => {
    tr.style.display = (!dept || tr.dataset.dept === dept) ? '' : 'none';
  });
}

function ouvrirRefus(id, nom, jours, debut, fin, type, solde) {
  document.getElementById('refus-nom').textContent = nom;
  document.getElementById('refus-id').value = id;
  const insuffisant = solde < jours
    ? `<span style="font-size:.8rem;color:var(--danger)"><i class="bi bi-exclamation-triangle"></i> Solde insuffisant : ${solde} jour(s) disponible(s), ${jours} demandé(s).</span>`
    : '';
  document.getElementById('refus-detail').innerHTML =
    `Demande de <strong>${jours} jours</strong> du ${debut} au ${fin} · Type : ${type}<br>${insuffisant}`;
  const modal = document.getElementById('modal-refus');
  modal.style.display = 'block';
  modal.scrollIntoView({behavior:'smooth'});
}
</script>
<?= $this->endSection() ?>
