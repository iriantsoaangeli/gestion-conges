<?= $this->extend('layouts/employe') ?>

<?= $this->section('breadcrumb') ?>
Accueil
<?= $this->endSection() ?>

<?= $this->section('topbar_actions') ?>
<a href="<?= base_url('employe/conge/create') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
  <i class="bi bi-plus-lg"></i> Nouvelle demande
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
    <div class="metric-label">Approuvées</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div>
    <div class="metric-val"><?= $solde_annuel ?? 0 ?></div>
    <div class="metric-label">Jours restants</div>
    <div class="metric-sub">sur <?= $solde_total ?? 30 ?> cette année</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
    <div class="metric-val"><?= $nb_refusees ?? 0 ?></div>
    <div class="metric-label">Refusées</div>
  </div>
</div>

<!-- Soldes -->
<div class="data-card">
  <div class="data-card-head"><h3>Mes soldes de congés — <?= date('Y') ?></h3></div>
  <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
    <?php foreach ($soldes ?? [] as $s): ?>
      <?php
        $pct = ($s['total'] > 0) ? round(($s['restant'] / $s['total']) * 100) : 0;
        $cls = $pct <= 20 ? 'danger' : ($pct <= 40 ? 'warn' : '');
      ?>
      <div class="solde-card" style="margin:0">
        <div class="solde-header">
          <span class="solde-type"><?= esc($s['type']) ?></span>
          <span class="solde-nums"><strong><?= $s['restant'] ?></strong> / <?= $s['total'] ?> j</span>
        </div>
        <div class="solde-bar">
          <div class="solde-fill <?= $cls ?>" style="width:<?= $pct ?>%"></div>
        </div>
        <div class="solde-label"><?= $s['restant'] ?> jour<?= $s['restant'] > 1 ? 's' : '' ?> restant<?= $s['restant'] > 1 ? 's' : '' ?> · <?= $s['pris'] ?> pris</div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Dernières demandes -->
<div class="data-card">
  <div class="data-card-head">
    <h3>Mes dernières demandes</h3>
    <a href="<?= base_url('employe/conge') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Voir tout →</a>
  </div>
  <table class="tbl">
    <thead>
      <tr><th>Type</th><th>Du</th><th>Au</th><th>Durée</th><th>Statut</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php if (empty($dernieres_demandes)): ?>
        <tr><td colspan="6"><div class="empty"><i class="bi bi-calendar-x"></i><p>Aucune demande pour l'instant.</p></div></td></tr>
      <?php else: ?>
        <?php foreach ($dernieres_demandes as $d): ?>
          <tr>
            <td><span class="type-badge <?= esc($d['badge_class']) ?>"><?= esc($d['type_label']) ?></span></td>
            <td class="td-muted"><?= esc($d['date_debut_fmt']) ?></td>
            <td class="td-muted"><?= esc($d['date_fin_fmt']) ?></td>
            <td class="td-mono"><?= $d['nb_jours'] ?> j</td>
            <td><span class="statut <?= esc($d['statut_class']) ?>"><?= esc($d['statut_label']) ?></span></td>
            <td>
              <?php if ($d['statut'] === 'en_attente'): ?>
                <?= form_open('employe/conge/annuler/' . $d['id'], ['style' => 'display:inline']) ?>
                  <?= csrf_field() ?>
                  <button type="submit" class="btn-sm btn-cancel"><i class="bi bi-x"></i> Annuler</button>
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
