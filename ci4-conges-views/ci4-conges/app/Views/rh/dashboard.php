<?= $this->extend('layouts/rh') ?>

<?= $this->section('breadcrumb') ?>
Accueil
<?= $this->endSection() ?>

<?= $this->section('topbar_actions') ?>
<a href="<?= base_url('rh/demandes') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
  <i class="bi bi-inbox"></i> Traiter les demandes
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Métriques -->
<div class="metrics">
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
    <div class="metric-val"><?= $nb_attente ?? 0 ?></div>
    <div class="metric-label">En attente</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
    <div class="metric-val"><?= $nb_approuvees ?? 0 ?></div>
    <div class="metric-label">Approuvées ce mois</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-people"></i></div></div>
    <div class="metric-val"><?= $nb_employes ?? 0 ?></div>
    <div class="metric-label">Employés</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-person-slash"></i></div></div>
    <div class="metric-val"><?= $nb_absents ?? 0 ?></div>
    <div class="metric-label">Absents aujourd'hui</div>
  </div>
</div>

<!-- Demandes en attente -->
<div class="data-card">
  <div class="data-card-head">
    <h3>Demandes en attente de traitement</h3>
    <a href="<?= base_url('rh/demandes') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Tout voir →</a>
  </div>
  <table class="tbl">
    <thead>
      <tr><th>Employé</th><th>Type</th><th>Période</th><th>Durée</th><th>Solde dispo</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php if (empty($demandes_attente)): ?>
        <tr><td colspan="6">
          <div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande en attente.</p></div>
        </td></tr>
      <?php else: ?>
        <?php foreach ($demandes_attente as $d): ?>
          <tr>
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
              <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:<?= $d['solde_ok'] ? 'var(--success)' : 'var(--warn)' ?>;font-weight:500">
                <?= $d['solde_dispo'] ?> j
              </span>
              <?php if (!$d['solde_ok']): ?>
                <span style="font-size:.72rem;color:var(--danger)"> ⚠</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="action-btns">
                <?= form_open('rh/demandes/approuver/' . $d['id'], ['style' => 'display:inline']) ?>
                  <?= csrf_field() ?>
                  <button type="submit" class="btn-sm btn-approve" <?= !$d['solde_ok'] ? 'disabled style="opacity:.4;cursor:not-allowed"' : '' ?>>
                    <i class="bi bi-check-lg"></i> Approuver
                  </button>
                <?= form_close() ?>
                <a href="<?= base_url('rh/demandes') ?>" class="btn-sm btn-refuse">
                  <i class="bi bi-x-lg"></i> Refuser
                </a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
