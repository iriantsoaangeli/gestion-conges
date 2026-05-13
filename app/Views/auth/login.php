<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-page geo-bg">
<div class="auth-split">

  <!-- Panneau gauche -->
  <div class="auth-left">
    <div>
      <p class="auth-left-brand">TechMada RH<span>Gestion des congés</span></p>
      <p class="auth-left-text" style="margin-top:2rem">
        <strong>Bienvenue sur votre espace RH.</strong>
        Gérez vos demandes de congés, consultez votre solde et suivez l'état de vos demandes en temps réel.
      </p>
    </div>
    <div class="auth-roles">
      <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.25);margin-bottom:4px">Comptes de démonstration</div>
      <div class="role-pill">
        <i class="bi bi-shield-check"></i>
        <div>
          <div class="role-pill-name">Administrateur</div>
          <div class="role-pill-cred">jean.rakoto@example.com · admin123</div>
        </div>
      </div>
      <div class="role-pill">
        <i class="bi bi-person-check"></i>
        <div>
          <div class="role-pill-name">Responsable RH</div>
          <div class="role-pill-cred">mia.rabe@example.com · rh123</div>
        </div>
      </div>
      <div class="role-pill">
        <i class="bi bi-person"></i>
        <div>
          <div class="role-pill-name">Employé</div>
          <div class="role-pill-cred">koto.andry@example.com · emp123</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Panneau droit — formulaire -->
  <div class="auth-right">
    <p class="auth-title">Connexion</p>
    <p class="auth-sub">Entrez vos identifiants pour accéder à votre espace.</p>

    <!-- Flashdata CI4 -->
    <?php if (session()->getFlashdata('error')): ?>
      <div class="flash flash-error">
        <i class="bi bi-exclamation-circle-fill"></i>
        <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <?= form_open('auth/login') ?>

      <div class="f-group">
        <label class="f-label" for="email">Adresse email</label>
        <input
          type="email"
          id="email"
          name="email"
          class="f-input <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>"
          placeholder="vous@techmada.mg"
          value="<?= old('email') ?>"
          required
        />
        <?php if (isset($validation) && $validation->hasError('email')): ?>
          <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= $validation->getError('email') ?></div>
        <?php endif; ?>
      </div>

      <div class="f-group">
        <label class="f-label" for="password">Mot de passe</label>
        <input
          type="password"
          id="password"
          name="password"
          class="f-input <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>"
          placeholder="••••••••"
          required
        />
        <?php if (isset($validation) && $validation->hasError('password')): ?>
          <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= $validation->getError('password') ?></div>
        <?php endif; ?>
      </div>

      <?= csrf_field() ?>

      <button type="submit" class="btn-primary" style="margin-top:.5rem">
        Se connecter <i class="bi bi-arrow-right-short"></i>
      </button>

    <?= form_close() ?>
  </div>

</div>
</div>

<?= $this->endSection() ?>
