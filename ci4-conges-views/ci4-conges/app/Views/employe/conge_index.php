<?= $this->extend('layouts/employe') ?>

<?= $this->section('breadcrumb') ?>
<a href="<?= base_url('employe/dashboard') ?>">Accueil</a>
<i class="bi bi-chevron-right" style="font-size:.6rem"></i>
Mes demandes
<?= $this->endSection() ?>

<?= $this->section('topbar_actions') ?>
<a href="<?= base_url('employe/conge/create') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
  <i class="bi bi-plus-lg"></i> Nouvelle demande
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="data-card">
  <div class="data-card-head">
    <h3>Toutes mes demandes</h3>
    <div style="display:flex;gap:6px">
      <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto" onchange="filtrerStatut(this.value)">
        <option value="">Tous les statuts</option>
        <option value="en_attente">En attente</option>
        <option value="approuvee">Approuvée</option>
        <option value="refusee">Refusée</option>
        <option value="annulee">Annulée</option>
      </select>
    </div>
  </div>
  <table class="tbl">
    <thead>
      <tr>
        <th>Type</th>
        <th>Début</th>
        <th>Fin</th>
        <th>Durée</th>
        <th>Statut</th>
        <th>Commentaire RH</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($demandes)): ?>
        <tr>
          <td colspan="7">
            <div class="empty">
              <i class="bi bi-calendar-x"></i>
              <p>Aucune demande de congé pour l'instant.<br>
                <a href="<?= base_url('employe/conge/create') ?>" style="color:var(--forest)">Faire une première demande →</a>
              </p>
            </div>
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($demandes as $d): ?>
          <tr data-statut="<?= esc($d['statut']) ?>">
            <td><span class="type-badge <?= esc($d['badge_class']) ?>"><?= esc($d['type_label']) ?></span></td>
            <td class="td-muted"><?= esc($d['date_debut_fmt']) ?></td>
            <td class="td-muted"><?= esc($d['date_fin_fmt']) ?></td>
            <td class="td-mono"><?= $d['nb_jours'] ?> j</td>
            <td><span class="statut <?= esc($d['statut_class']) ?>"><?= esc($d['statut_label']) ?></span></td>
            <td style="font-size:.78rem;color:<?= $d['statut'] === 'refusee' ? 'var(--danger)' : ($d['statut'] === 'approuvee' ? 'var(--success)' : 'var(--muted)') ?>">
              <?php if (!empty($d['commentaire_rh'])): ?>
                <?php if ($d['statut'] === 'approuvee'): ?><i class="bi bi-check-circle"></i><?php endif; ?>
                <?= esc($d['commentaire_rh']) ?>
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
            <td>
              <?php if ($d['statut'] === 'en_attente'): ?>
                <?= form_open('employe/conge/annuler/' . $d['id'], ['style' => 'display:inline']) ?>
                  <?= csrf_field() ?>
                  <button type="submit" class="btn-sm btn-cancel"
                    onclick="return confirm('Voulez-vous vraiment annuler cette demande ?')">
                    <i class="bi bi-x"></i> Annuler
                  </button>
                <?= form_close() ?>
              <?php else: ?>
                <span class="td-muted" style="font-size:.75rem">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function filtrerStatut(val) {
  document.querySelectorAll('tbody tr[data-statut]').forEach(tr => {
    tr.style.display = (!val || tr.dataset.statut === val) ? '' : 'none';
  });
}
</script>
<?= $this->endSection() ?>
