<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Espace Opérateur Money' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{
            --bg:#F7F6F2;
            --pink:#00BFFF;
            --yellow:#FFC72C;
            --purple:#C9BEFF;
            --black:#0F0F10;
            --gray:#6B6A70;
        }
        *{ box-sizing:border-box; }
        body{
            margin:0;
            font-family:'Inter',sans-serif;
            color:var(--black);
            background:var(--bg);
            display:flex;
            flex-direction:column;
            min-height:100vh;
        }

        .topbar{
            background:#0f172a;
            color:#fff;
            position:sticky;
            top:0;
            z-index:100;
        }
        .topbar-inner{
            padding:18px 24px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
        }
        .title{
            font-weight:700;
            font-size:1rem;
            display:flex;
            align-items:center;
            gap:12px;
        }
        .menu-toggle{
            display:none;
            background:none;
            border:none;
            color:#fff;
            font-size:1.4rem;
            cursor:pointer;
            padding:4px;
        }
        .operator-actions{
            display:flex;
            align-items:center;
            gap:14px;
            flex-wrap:wrap;
        }
        .topbar-logout{
            display:inline-block;
            border:1px solid rgba(255,255,255,0.65);
            color:#fff;
            text-decoration:none;
            padding:8px 14px;
            border-radius:10px;
            transition:background .15s ease;
        }
        .topbar-logout:hover{
            background:rgba(255,255,255,0.08);
        }

        .main-wrapper{
            display:flex;
            flex:1;
            gap:0;
        }

        .sidebar{
            width:260px;
            background:#fff;
            border-right:1px solid rgba(15,15,16,0.06);
            padding:24px 0;
            overflow-y:auto;
            transition:transform .3s ease, width .3s ease;
        }
        .sidebar.mobile-hidden{
            transform:translateX(-100%);
            position:absolute;
            left:0;
            top:0;
            height:calc(100vh - 60px);
            z-index:50;
        }

        .nav-section{
            margin-bottom:24px;
        }
        .nav-section-title{
            font-size:0.75rem;
            text-transform:uppercase;
            letter-spacing:0.08em;
            font-weight:700;
            color:var(--gray);
            padding:0 20px;
            margin-bottom:12px;
        }
        .nav-link{
            display:flex;
            align-items:center;
            gap:12px;
            padding:12px 20px;
            text-decoration:none;
            color:var(--black);
            font-size:0.95rem;
            border-left:3px solid transparent;
            transition:background .15s ease, border-color .15s ease;
            cursor:pointer;
        }
        .nav-link:hover{
            background:rgba(0,191,255,0.08);
            border-left-color:var(--pink);
        }
        .nav-link.active{
            background:rgba(0,191,255,0.15);
            border-left-color:var(--pink);
            font-weight:600;
        }
        .nav-icon{
            font-size:1.1rem;
        }

        .container{
            flex:1;
            padding:48px 24px 64px;
            overflow-y:auto;
            max-width:100%;
        }

        .page-header{
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom:32px;
        }
        .brand-dot{
            width:14px;
            height:14px;
            background:var(--pink);
        }
        h1{
            font-family:'Sora',sans-serif;
            font-weight:800;
            font-size:1.7rem;
            letter-spacing:-0.02em;
            margin:0;
        }
        .eyebrow{
            font-size:0.8rem;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:0.06em;
            color:var(--gray);
            margin:0 0 4px;
        }

        .grid-2{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:20px;
            margin-bottom:24px;
        }
        .grid-3{
            display:grid;
            grid-template-columns:1fr 2fr;
            gap:20px;
        }
        @media (max-width:900px){
            .grid-2, .grid-3{ grid-template-columns:1fr; }
        }

        .panel{
            background:#fff;
            border:1px solid rgba(15,15,16,0.06);
            overflow:hidden;
            display:flex;
            flex-direction:column;
        }
        .panel-header{
            padding:18px 24px;
            font-family:'Sora',sans-serif;
            font-weight:700;
            font-size:1rem;
            color:#fff;
            display:flex;
            align-items:center;
            gap:10px;
        }
        .panel-header.tone-pink{ background:var(--pink); }
        .panel-header.tone-black{ background:var(--black); }
        .panel-body{
            padding:20px 24px 24px;
            flex:1;
        }
        .panel-body.scroll{
            max-height:270px;
            overflow-y:auto;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }
        thead th{
            text-align:left;
            font-size:0.72rem;
            text-transform:uppercase;
            letter-spacing:0.05em;
            color:var(--gray);
            font-weight:700;
            padding:10px 8px;
            border-bottom:1px solid rgba(15,15,16,0.07);
        }
        tbody td{
            padding:13px 8px;
            font-size:0.92rem;
            border-bottom:1px solid rgba(15,15,16,0.05);
        }
        tbody tr:last-child td{ border-bottom:none; }
        .text-capitalize{ text-transform:capitalize; }
        .text-end{ text-align:right; }
        .fw-bold{ font-weight:700; }
        .text-secondary{ color:var(--gray); }

        .badge{
            display:inline-block;
            padding:5px 14px;
            font-size:0.78rem;
            font-weight:700;
            background:var(--pink);
            color:#fff;
        }

        .inline-form{
            display:flex;
            gap:10px;
            margin-bottom:18px;
        }
        .field{
            width:100%;
            border:1.5px solid #E7E5E0;
            padding:11px 14px;
            font-family:'Inter',sans-serif;
            font-size:0.92rem;
            background:#FAFAF8;
            outline:none;
            transition:border-color .15s ease;
        }
        .field:focus{ border-color:var(--pink); background:#fff; }
        select.field{ appearance:none; cursor:pointer; }

        .btn{
            border:none;
            padding:11px 20px;
            font-family:'Sora',sans-serif;
            font-weight:700;
            font-size:0.9rem;
            cursor:pointer;
            white-space:nowrap;
            background:var(--black);
            color:#fff;
            transition:transform .12s ease, background .15s ease;
        }
        .btn:hover{ background:var(--pink); transform:translateY(-1px); }
        .btn-yellow{ background:var(--yellow); color:var(--black); }
        .btn-yellow:hover{ background:var(--black); color:#fff; }

        .form-bareme{
            display:grid;
            grid-template-columns:1.4fr 1fr 1fr 1fr auto;
            gap:10px;
            margin-bottom:22px;
        }
        @media (max-width:640px){
            .form-bareme{ grid-template-columns:1fr 1fr; }
        }

        .prefix-list{
            list-style:none;
            margin:0;
            padding:0;
            display:flex;
            flex-direction:column;
            gap:8px;
        }
        .prefix-item{
            background:var(--purple);
            padding:12px 16px;
            font-size:0.9rem;
            display:flex;
            align-items:center;
            gap:8px;
        }
        .prefix-item strong{ font-weight:800; }

        @media (max-width:1024px){
            .menu-toggle{ display:block; }
            .sidebar.mobile-hidden{
                transform:translateX(-100%);
            }
            .sidebar{
                width:260px;
            }
        }

        @media (max-width:768px){
            .main-wrapper{
                flex-direction:column;
            }
            .sidebar{
                width:100%;
                border-right:none;
                border-bottom:1px solid rgba(15,15,16,0.06);
                position:fixed;
                left:0;
                top:60px;
                height:auto;
                max-height:70vh;
            }
            .sidebar.mobile-hidden{
                position:fixed;
            }
            .container{
                padding:24px 12px 48px;
            }
            .grid-2, .grid-3{
                grid-template-columns:1fr;
            }
        }
    </style>
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <div class="title">
            <button class="menu-toggle" id="menuToggle">☰</button>
            Espace Opérateur
        </div>
        <div class="operator-actions">
            <?php if (session()->has('operator')): ?>
                <span>Connecté comme <strong><?= esc(session()->get('operator')['username']) ?></strong></span>
            <?php endif; ?>
            <a href="<?= base_url('operator/logout') ?>" class="topbar-logout">Déconnexion</a>
        </div>
    </div>
</header>

<div class="main-wrapper">
    <nav class="sidebar" id="sidebar">
        <div class="nav-section">
            <div class="nav-section-title">Navigation</div>
            <a href="<?= base_url('operator/gains') ?>" class="nav-link <?= ($current_page === 'gains') ? 'active' : '' ?>">
                <span class="nav-icon">📊</span>
                <span>Situation des gains</span>
            </a>
            <a href="<?= base_url('operator/clients') ?>" class="nav-link <?= ($current_page === 'clients') ? 'active' : '' ?>">
                <span class="nav-icon">👥</span>
                <span>Comptes clients</span>
            </a>
            <a href="<?= base_url('operator/prefixes') ?>" class="nav-link <?= ($current_page === 'prefixes') ? 'active' : '' ?>">
                <span class="nav-icon">🔢</span>
                <span>Préfixes valables</span>
            </a>
            <a href="<?= base_url('operator/baremes') ?>" class="nav-link <?= ($current_page === 'baremes') ? 'active' : '' ?>">
                <span class="nav-icon">💰</span>
                <span>Barèmes des frais</span>
            </a>
        </div>
    </nav>

    <div class="container">
        <?= $this->renderSection('content') ?>
    </div>
</div>

<script>
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');

    menuToggle.addEventListener('click', function(){
        sidebar.classList.toggle('mobile-hidden');
    });
</script>
</body>
</html>
