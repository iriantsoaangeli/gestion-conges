<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title><?= $title ?? 'TechMada RH' ?> — Espace employé</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<link href="<?= base_url('css/app.css') ?>" rel="stylesheet"/>
<?= $this->renderSection('styles') ?>
</head>
<body>

<div class="app-wrap">

  <!-- ─── SIDEBAR EMPLOYÉ ─────────────────────────────────── -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
    </div>
    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-nav">
      <li>
        <a href="<?= base_url('employe/dashboard') ?>" <?= (current_url() === base_url('employe/dashboard')) ? 'class="active"' : '' ?>>
          <i class="bi bi-grid-1x2"></i> Tableau de bord
        </a>
      </li>
      <li>
        <a href="<?= base_url('employe/conges/create') ?>" <?= str_contains(current_url(), 'conges/create') ? 'class="active"' : '' ?>>
          <i class="bi bi-plus-circle"></i> Nouvelle demande
        </a>
      </li>
      <li>
        <a href="<?= base_url('employe/conges') ?>" <?= (str_contains(current_url(), 'employe/conges') && !str_contains(current_url(), 'create')) ? 'class="active"' : '' ?>>
          <i class="bi bi-calendar3"></i> Mes demandes
          <?php if (!empty($badge_attente) && $badge_attente > 0): ?>
            <span class="nav-badge alert"><?= $badge_attente ?></span>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a href="<?= base_url('employe/profil') ?>" <?= str_contains(current_url(), 'employe/profil') ? 'class="active"' : '' ?>>
          <i class="bi bi-person"></i> Mon profil
        </a>
      </li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-green"><?= strtoupper(substr($user['prenom'] ?? 'U', 0, 1) . substr($user['nom'] ?? '', 0, 1)) ?></div>
        <div>
          <div class="user-name"><?= esc($user['prenom'] ?? '') ?> <?= esc($user['nom'] ?? '') ?></div>
          <div class="user-role">Employé · <?= esc($user['departement'] ?? '') ?></div>
        </div>
        <a href="<?= base_url('auth/logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion">
          <i class="bi bi-box-arrow-right"></i>
        </a>
      </div>
    </div>
  </aside>

  <!-- ─── MAIN ────────────────────────────────────────────── -->
  <div class="main">

    <!-- Topbar -->
    <div class="topbar">
      <div>
        <div class="topbar-title"><?= $pageTitle ?? '' ?></div>
        <div class="topbar-breadcrumb">
          <?= $this->renderSection('breadcrumb') ?>
        </div>
      </div>
      <div class="topbar-actions">
        <?= $this->renderSection('topbar_actions') ?>
      </div>
    </div>

    <!-- Contenu -->
    <div class="content">

      <!-- Flash messages CI4 -->
      <?php if (session()->getFlashdata('success')): ?>
        <div class="flash flash-success">
          <i class="bi bi-check-circle-fill"></i>
          <?= session()->getFlashdata('success') ?>
        </div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash-error">
          <i class="bi bi-exclamation-circle-fill"></i>
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('warning')): ?>
        <div class="flash flash-warn">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <?= session()->getFlashdata('warning') ?>
        </div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('info')): ?>
        <div class="flash flash-info">
          <i class="bi bi-info-circle-fill"></i>
          <?= session()->getFlashdata('info') ?>
        </div>
      <?php endif; ?>

      <?= $this->renderSection('content') ?>
    </div>

    <div class="footer-app">
      <i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span> — Projet CodeIgniter 4
    </div>
  </div>

</div>

<?= $this->renderSection('scripts') ?>
</body>
</html>
