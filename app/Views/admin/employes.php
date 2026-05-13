<?= $this->extend('layouts/admin') ?>

<?= $this->section('breadcrumb') ?>
<a href="<?= base_url('admin/dashboard') ?>">Admin</a>
<i class="bi bi-chevron-right" style="font-size:.6rem"></i>
Employés
<?= $this->endSection() ?>

<?= $this->section('topbar_actions') ?>
<a href="#form-ajouter" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
  <i class="bi bi-person-plus"></i> Ajouter
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Formulaire ajout -->
<div class="form-section" id="form-ajouter">
  <h3><i class="bi bi-person-plus" style="color:var(--forest);margin-right:6px"></i>Ajouter un employé</h3>

  <?= form_open('admin/employes/store') ?>
  <?= csrf_field() ?>

  <div class="form-grid-2" style="margin-bottom:1rem">
    <div class="f-group">
      <label class="f-label">Prénom</label>
      <input type="text" name="prenom" class="f-input" placeholder="Jean" value="<?= old('prenom') ?>"/>
      <?php if (isset($validation) && $validation->hasError('prenom')): ?>
        <div class="f-error"><?= $validation->getError('prenom') ?></div>
      <?php endif; ?>
    </div>
    <div class="f-group">
      <label class="f-label">Nom</label>
      <input type="text" name="nom" class="f-input" placeholder="Rakoto" value="<?= old('nom') ?>"/>
      <?php if (isset($validation) && $validation->hasError('nom')): ?>
        <div class="f-error"><?= $validation->getError('nom') ?></div>
      <?php endif; ?>
    </div>
    <div class="f-group">
      <label class="f-label">Email</label>
      <input type="email" name="email" class="f-input" placeholder="jean.rakoto@techmada.mg" value="<?= old('email') ?>"/>
      <?php if (isset($validation) && $validation->hasError('email')): ?>
        <div class="f-error"><?= $validation->getError('email') ?></div>
      <?php endif; ?>
    </div>
    <div class="f-group">
      <label class="f-label">Mot de passe initial</label>
      <input type="password" name="password" class="f-input" placeholder="À communiquer à l'employé"/>
      <?php if (isset($validation) && $validation->hasError('password')): ?>
        <div class="f-error"><?= $validation->getError('password') ?></div>
      <?php endif; ?>
    </div>
    <div class="f-group">
      <label class="f-label">Département</label>
      <select name="departement_id" class="f-select">
        <option value="">-- Choisir --</option>
        <?php foreach ($departements ?? [] as $dept): ?>
          <option value="<?= $dept['id'] ?>" <?= old('departement_id') == $dept['id'] ? 'selected' : '' ?>>
            <?= esc($dept['nom']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="f-group">
      <label class="f-label">Rôle</label>
      <select name="role" class="f-select">
        <option value="employe" <?= old('role') === 'employe' ? 'selected' : '' ?>>Employé</option>
        <option value="rh" <?= old('role') === 'rh' ? 'selected' : '' ?>>Responsable RH</option>
        <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Administrateur</option>
      </select>
    </div>
    <div class="f-group">
      <label class="f-label">Date d'embauche</label>
      <input type="date" name="date_embauche" class="f-input" value="<?= old('date_embauche', date('Y-m-d')) ?>"/>
    </div>
  </div>

  <div class="flash flash-info" style="margin-bottom:1rem">
    <i class="bi bi-info-circle-fill"></i>
    <span style="font-size:.82rem">Les soldes de congés seront initialisés automatiquement selon les types de congé configurés.</span>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn-forest"><i class="bi bi-plus"></i> Créer l'employé</button>
    <button type="reset" class="btn-secondary">Réinitialiser</button>
  </div>

  <?= form_close() ?>
</div>

<!-- Liste employés -->
<div class="data-card">
  <div class="data-card-head">
    <h3>Tous les employés</h3>
    <div style="display:flex;gap:6px">
      <input type="text" class="f-input" id="search-emp" placeholder="Rechercher..." style="width:200px;padding:6px 10px;font-size:.8rem" oninput="rechercherEmp(this.value)"/>
      <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto" onchange="filtrerDept(this.value)">
        <option value="">Tous les depts</option>
        <?php foreach ($departements ?? [] as $dept): ?>
          <option value="<?= esc($dept['nom']) ?>"><?= esc($dept['nom']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <table class="tbl">
    <thead>
      <tr>
        <th>Employé</th>
        <th>Département</th>
        <th>Rôle</th>
        <th>Embauche</th>
        <th>Statut</th>
        <th>Solde annuel</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($employes)): ?>
        <tr><td colspan="7"><div class="empty"><i class="bi bi-people"></i><p>Aucun employé trouvé.</p></div></td></tr>
      <?php else: ?>
        <?php foreach ($employes as $e): ?>
          <tr data-dept="<?= esc($e['departement']) ?>" data-nom="<?= esc(strtolower($e['prenom'] . ' ' . $e['nom'])) ?>" style="<?= $e['actif'] ? '' : 'opacity:.5' ?>">
            <td>
              <div class="profile-row">
                <div class="avatar av-green" style="width:32px;height:32px;font-size:.68rem">
                  <?= strtoupper(substr($e['prenom'], 0, 1) . substr($e['nom'], 0, 1)) ?>
                </div>
                <div class="profile-info">
                  <div class="pname"><?= esc($e['prenom']) ?> <?= esc($e['nom']) ?></div>
                  <div class="pdept"><?= esc($e['email']) ?></div>
                </div>
              </div>
            </td>
            <td class="td-muted"><?= esc($e['departement']) ?></td>
            <td>
              <span class="type-badge <?= $e['role'] === 'rh' ? 't-maladie' : ($e['role'] === 'admin' ? 't-special' : '') ?>"
                style="<?= $e['role'] === 'employe' ? 'background:#f1efe8;color:#444441' : '' ?>">
                <?= esc($e['role']) ?>
              </span>
            </td>
            <td class="td-muted td-mono" style="font-size:.78rem"><?= esc($e['date_embauche_fmt']) ?></td>
            <td>
              <span class="statut <?= $e['actif'] ? 's-approuvee' : 's-annulee' ?>" style="font-size:.68rem">
                <?= $e['actif'] ? 'actif' : 'inactif' ?>
              </span>
            </td>
            <td>
              <?php if ($e['actif']): ?>
                <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--forest)">
                  <?= $e['solde_restant'] ?> / <?= $e['solde_total'] ?> j
                </span>
              <?php else: ?>
                <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--muted)">— / — j</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="action-btns">
                <?php if ($e['actif']): ?>
                  <a href="<?= base_url('admin/employes/edit/' . $e['id']) ?>" class="btn-sm btn-edit">
                    <i class="bi bi-pencil"></i> Éditer
                  </a>
                  <?= form_open('admin/employes/desactiver/' . $e['id'], ['style' => 'display:inline']) ?>
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-sm btn-del" onclick="return confirm('Désactiver cet employé ?')">
                      <i class="bi bi-slash-circle"></i>
                    </button>
                  <?= form_close() ?>
                <?php else: ?>
                  <?= form_open('admin/employes/reactiver/' . $e['id'], ['style' => 'display:inline']) ?>
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-sm btn-view">
                      <i class="bi bi-arrow-counterclockwise"></i> Réactiver
                    </button>
                  <?= form_close() ?>
                <?php endif; ?>
              </div>
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
function rechercherEmp(val) {
  const q = val.toLowerCase();
  document.querySelectorAll('tbody tr[data-nom]').forEach(tr => {
    tr.style.display = tr.dataset.nom.includes(q) ? '' : 'none';
  });
}
function filtrerDept(dept) {
  document.querySelectorAll('tbody tr[data-dept]').forEach(tr => {
    tr.style.display = (!dept || tr.dataset.dept === dept) ? '' : 'none';
  });
}
</script>
<?= $this->endSection() ?>
