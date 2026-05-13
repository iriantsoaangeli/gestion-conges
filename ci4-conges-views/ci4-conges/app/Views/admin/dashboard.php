<?= $this->extend('layouts/admin') ?>

<?= $this->section('breadcrumb') ?>
Administration
<?= $this->endSection() ?>

<?= $this->section('topbar_actions') ?>
<a href="<?= base_url('admin/employes') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
  <i class="bi bi-person-plus"></i> Ajouter un employé
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Métriques -->
<div class="metrics">
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-people"></i></div></div>
    <div class="metric-val"><?= $nb_employes ?? 0 ?></div>
    <div class="metric-label">Employés actifs</div>
    <?php if (!empty($employes_new)): ?>
      <div class="metric-sub up"><i class="bi bi-arrow-up-short"></i> +<?= $employes_new ?> ce mois</div>
    <?php endif; ?>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
    <div class="metric-val"><?= $nb_attente ?? 0 ?></div>
    <div class="metric-label">Demandes en attente</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-calendar-check"></i></div></div>
    <div class="metric-val"><?= $nb_approuvees_mois ?? 0 ?></div>
    <div class="metric-label">Approuvées ce mois</div>
    <?php if (!empty($diff_approuvees)): ?>
      <div class="metric-sub up"><i class="bi bi-arrow-up-short"></i> +<?= $diff_approuvees ?> vs mois dernier</div>
    <?php endif; ?>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-building"></i></div></div>
    <div class="metric-val"><?= $nb_departements ?? 0 ?></div>
    <div class="metric-label">Départements</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-person-slash"></i></div></div>
    <div class="metric-val"><?= $nb_absents ?? 0 ?></div>
    <div class="metric-label">Absents aujourd'hui</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">

  <!-- Demandes récentes -->
  <div class="data-card" style="margin:0">
    <div class="data-card-head">
      <h3>Demandes récentes</h3>
      <a href="<?= base_url('admin/demandes') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Tout voir →</a>
    </div>
    <table class="tbl">
      <thead>
        <tr><th>Employé</th><th>Type</th><th>Durée</th><th>Statut</th></tr>
      </thead>
      <tbody>
        <?php if (empty($demandes_recentes)): ?>
          <tr><td colspan="4"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande récente.</p></div></td></tr>
        <?php else: ?>
          <?php foreach ($demandes_recentes as $d): ?>
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:7px">
                  <div class="avatar av-green" style="width:28px;height:28px;font-size:.62rem">
                    <?= strtoupper(substr($d['prenom'], 0, 1) . substr($d['nom'], 0, 1)) ?>
                  </div>
                  <span class="td-name" style="font-size:.84rem"><?= esc($d['prenom']) ?> <?= esc($d['nom']) ?></span>
                </div>
              </td>
              <td><span class="type-badge <?= esc($d['badge_class']) ?>"><?= esc($d['type_label']) ?></span></td>
              <td class="td-mono"><?= $d['nb_jours'] ?> j</td>
              <td><span class="statut <?= esc($d['statut_class']) ?>"><?= esc($d['statut_label']) ?></span></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Absents + alertes -->
  <div style="display:flex;flex-direction:column;gap:1rem">

    <!-- Absents du jour -->
    <div class="data-card" style="margin:0">
      <div class="data-card-head">
        <h3><i class="bi bi-person-slash" style="color:var(--muted);margin-right:5px"></i>Absents aujourd'hui</h3>
      </div>
      <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.6rem">
        <?php if (empty($absents)): ?>
          <p style="font-size:.8rem;color:var(--muted);margin:0">Aucune absence aujourd'hui.</p>
        <?php else: ?>
          <?php foreach ($absents as $a): ?>
            <div style="display:flex;align-items:center;gap:8px">
              <div class="avatar av-green" style="width:30px;height:30px;font-size:.65rem">
                <?= strtoupper(substr($a['prenom'], 0, 1) . substr($a['nom'], 0, 1)) ?>
              </div>
              <div>
                <div style="font-size:.83rem;font-weight:500;color:var(--ink)"><?= esc($a['prenom']) ?> <?= esc($a['nom']) ?></div>
                <div style="font-size:.72rem;color:var(--muted)"><?= esc($a['type_label']) ?> · retour <?= esc($a['retour_fmt']) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- Alerte soldes critiques -->
    <?php if (!empty($nb_soldes_critiques) && $nb_soldes_critiques > 0): ?>
      <div class="flash flash-warn" style="margin:0">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span style="font-size:.8rem">
          <?= $nb_soldes_critiques ?> employé<?= $nb_soldes_critiques > 1 ? 's' : '' ?> avec un solde critique (≤ 2 jours).
          <a href="<?= base_url('admin/soldes') ?>" style="color:var(--warn);font-weight:500">Voir les soldes →</a>
        </span>
      </div>
    <?php endif; ?>

  </div>
</div>

<?= $this->endSection() ?>
