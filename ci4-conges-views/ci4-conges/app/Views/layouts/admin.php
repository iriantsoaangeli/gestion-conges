<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title><?= $title ?? 'TechMada RH' ?> — Administration</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<link href="<?= base_url('css/app.css') ?>" rel="stylesheet"/>
<?= $this->renderSection('styles') ?>
</head>
<body>

<div class="app-wrap">

  <!-- ─── SIDEBAR ADMIN ───────────────────────────────────── -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)">
        <i class="bi bi-shield-check" style="color:var(--leaf)"></i>
      </div>
      <div class="sidebar-brand-name">TechMada RH<span>Administration</span></div>
    </div>
    <div class="sidebar-section">Gestion</div>
    <ul class="sidebar-nav">
      <li>
        <a href="<?= base_url('admin/dashboard') ?>" <?= str_contains(current_url(), 'admin/dashboard') ? 'class="active"' : '' ?>>
          <i class="bi bi-speedometer2"></i> Vue d'ensemble
        </a>
      </li>
      <li>
        <a href="<?= base_url('admin/demandes') ?>" <?= str_contains(current_url(), 'admin/demandes') ? 'class="active"' : '' ?>>
          <i class="bi bi-inbox"></i> Toutes les demandes
          <?php if (!empty($nb_attente) && $nb_attente > 0): ?>
            <span class="nav-badge alert"><?= $nb_attente ?></span>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a href="<?= base_url('admin/employes') ?>" <?= str_contains(current_url(), 'admin/employes') ? 'class="active"' : '' ?>>
          <i class="bi bi-people"></i> Employés
        </a>
      </li>
      <li>
        <a href="<?= base_url('admin/departements') ?>" <?= str_contains(current_url(), 'admin/departements') ? 'class="active"' : '' ?>>
          <i class="bi bi-building"></i> Départements
        </a>
      </li>
      <li>
        <a href="<?= base_url('admin/types-conge') ?>" <?= str_contains(current_url(), 'admin/types-conge') ? 'class="active"' : '' ?>>
          <i class="bi bi-tags"></i> Types de congé
        </a>
      </li>
      <li>
        <a href="<?= base_url('admin/soldes') ?>" <?= str_contains(current_url(), 'admin/soldes') ? 'class="active"' : '' ?>>
          <i class="bi bi-sliders"></i> Soldes annuels
        </a>
      </li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-purple" style="width:32px;height:32px;font-size:.7rem">AD</div>
        <div>
          <div class="user-name">Administrateur</div>
          <div class="user-role">Admin système</div>
        </div>
        <a href="<?= base_url('auth/logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion">
          <i class="bi bi-box-arrow-right"></i>
        </a>
      </div>
    </div>
  </aside>

  <!-- ─── MAIN ────────────────────────────────────────────── -->
  <div class="main">

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

    <div class="content">

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

      <?= $this->renderSection('content') ?>
    </div>

    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>

</div>

<?= $this->renderSection('scripts') ?>
</body>
</html>
