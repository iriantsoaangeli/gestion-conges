<?= $this->extend('layouts/employe') ?>

<?= $this->section('breadcrumb') ?>
<a href="<?= base_url('employe/dashboard') ?>">Accueil</a>
<i class="bi bi-chevron-right" style="font-size:.6rem"></i>
Mon profil
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start">

  <!-- Infos profil -->
  <div>
    <div class="form-section">
      <h3><i class="bi bi-person-circle" style="color:var(--forest);margin-right:6px"></i>Informations personnelles</h3>

      <!-- En-tête profil -->
      <div style="display:flex;align-items:center;gap:1rem;padding:1rem;background:var(--cream);border-radius:10px;margin-bottom:1.5rem">
        <div class="avatar av-green" style="width:56px;height:56px;font-size:1.1rem">
          <?= strtoupper(substr($user['prenom'] ?? 'U', 0, 1) . substr($user['nom'] ?? '', 0, 1)) ?>
        </div>
        <div>
          <div style="font-size:1.05rem;font-weight:600;color:var(--ink)"><?= esc($user['prenom'] ?? '') ?> <?= esc($user['nom'] ?? '') ?></div>
          <div style="font-size:.8rem;color:var(--muted)"><?= esc($user['email'] ?? '') ?></div>
          <div class="inline-stats" style="margin-top:.35rem">
            <span class="inline-stat"><i class="bi bi-building"></i> <?= esc($user['departement'] ?? '—') ?></span>
            <span class="inline-stat"><i class="bi bi-calendar-date"></i> Embauché le <?= esc($user['date_embauche_fmt'] ?? '—') ?></span>
          </div>
        </div>
      </div>

      <?= form_open('employe/profil/update') ?>
      <?= csrf_field() ?>

      <div class="form-grid-2">
        <div class="f-group">
          <label class="f-label">Prénom</label>
          <input type="text" name="prenom" class="f-input" value="<?= esc($user['prenom'] ?? '') ?>"/>
        </div>
        <div class="f-group">
          <label class="f-label">Nom</label>
          <input type="text" name="nom" class="f-input" value="<?= esc($user['nom'] ?? '') ?>"/>
        </div>
      </div>

      <div class="f-group">
        <label class="f-label">Email</label>
        <input type="email" name="email" class="f-input" value="<?= esc($user['email'] ?? '') ?>" readonly style="background:var(--cream);cursor:not-allowed"/>
        <div class="f-hint">L'email ne peut pas être modifié.</div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-forest"><i class="bi bi-save"></i> Enregistrer</button>
      </div>
      <?= form_close() ?>
    </div>

    <!-- Changer mot de passe -->
    <div class="form-section">
      <h3><i class="bi bi-lock" style="color:var(--forest);margin-right:6px"></i>Changer le mot de passe</h3>
      <?= form_open('employe/profil/password') ?>
      <?= csrf_field() ?>
      <div class="f-group">
        <label class="f-label">Mot de passe actuel</label>
        <input type="password" name="password_actuel" class="f-input" placeholder="••••••••"/>
      </div>
      <div class="form-grid-2">
        <div class="f-group">
          <label class="f-label">Nouveau mot de passe</label>
          <input type="password" name="password_nouveau" class="f-input" placeholder="••••••••"/>
        </div>
        <div class="f-group">
          <label class="f-label">Confirmer</label>
          <input type="password" name="password_confirm" class="f-input" placeholder="••••••••"/>
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn-secondary"><i class="bi bi-shield-lock"></i> Mettre à jour</button>
      </div>
      <?= form_close() ?>
    </div>
  </div>

  <!-- Soldes résumé -->
  <div style="display:flex;flex-direction:column;gap:1rem">
    <div class="data-card" style="margin:0">
      <div class="data-card-head"><h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Mes soldes <?= date('Y') ?></h3></div>
      <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.75rem">
        <?php foreach ($soldes ?? [] as $s): ?>
          <?php $pct = ($s['total'] > 0) ? round(($s['restant'] / $s['total']) * 100) : 0; ?>
          <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
              <span style="font-size:.8rem;color:var(--ink)"><?= esc($s['type']) ?></span>
              <span style="font-family:'DM Mono',monospace;font-size:.8rem;color:var(--forest);font-weight:500"><?= $s['restant'] ?> j</span>
            </div>
            <div class="solde-bar">
              <div class="solde-fill <?= $pct <= 20 ? 'warn' : '' ?>" style="width:<?= $pct ?>%"></div>
            </div>
            <div class="solde-label"><?= $s['restant'] ?> / <?= $s['total'] ?> j restants</div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Stats globales -->
    <div style="background:var(--cream);border:1px solid var(--border);border-radius:10px;padding:1rem">
      <div style="font-size:.78rem;font-weight:500;color:var(--ink);margin-bottom:.75rem">Statistiques <?= date('Y') ?></div>
      <div style="display:flex;flex-direction:column;gap:.5rem">
        <div style="display:flex;justify-content:space-between;font-size:.8rem">
          <span style="color:var(--muted)">Demandes soumises</span>
          <strong style="font-family:'DM Mono',monospace"><?= $stats['total'] ?? 0 ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.8rem">
          <span style="color:var(--muted)">Approuvées</span>
          <strong style="font-family:'DM Mono',monospace;color:var(--success)"><?= $stats['approuvees'] ?? 0 ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.8rem">
          <span style="color:var(--muted)">Refusées</span>
          <strong style="font-family:'DM Mono',monospace;color:var(--danger)"><?= $stats['refusees'] ?? 0 ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.8rem">
          <span style="color:var(--muted)">En attente</span>
          <strong style="font-family:'DM Mono',monospace;color:var(--warn)"><?= $stats['attente'] ?? 0 ?></strong>
        </div>
      </div>
    </div>
  </div>

</div>

<?= $this->endSection() ?>
