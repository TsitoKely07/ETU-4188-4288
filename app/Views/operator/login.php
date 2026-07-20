<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Opérateur - Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root{
            --bg:#F7F6F2;
            --pink:#00BFFF;
            --black:#0F0F10;
            --gray:#6B6A70;
        }
        *{ box-sizing:border-box; }
        body{
            margin:0;
            min-height:100vh;
            font-family:'Inter',sans-serif;
            color:var(--black);
            background:
                radial-gradient(circle at 15% 20%, rgba(255,31,109,0.10), transparent 40%),
                radial-gradient(circle at 85% 15%, rgba(255,199,44,0.14), transparent 40%),
                radial-gradient(circle at 50% 90%, rgba(201,190,255,0.18), transparent 45%),
                var(--bg);
            display:flex;
            align-items:center;
            justify-content:center;
            padding:24px;
        }
        .auth-card{
            width:100%;
            max-width:420px;
            background:#fff;
            padding:40px 36px;
            border-radius:18px;
            box-shadow:0 20px 45px -20px rgba(15,15,16,0.18);
            border:1px solid rgba(15,15,16,0.06);
        }
        .brand{
            display:flex;
            align-items:center;
            gap:8px;
            margin-bottom:28px;
        }
        .brand-dot{
            width:14px;
            height:14px;
            background:var(--pink);
            border-radius:50%;
        }
        .brand-name{
            font-weight:800;
            font-size:1.05rem;
            letter-spacing:-0.02em;
        }
        h1{
            font-weight:800;
            font-size:1.85rem;
            margin:0 0 6px;
            letter-spacing:-0.02em;
        }
        p.subtitle{
            color:var(--gray);
            font-size:0.95rem;
            margin:0 0 28px;
        }
        .alert{
            padding:12px 16px;
            font-size:0.9rem;
            margin-bottom:20px;
            font-weight:500;
        }
        .alert-danger{
            background:rgba(255,31,109,0.10);
            color:#C4104F;
            border:1px solid rgba(255,31,109,0.25);
        }
        label{
            display:block;
            font-size:0.85rem;
            font-weight:600;
            margin-bottom:8px;
            color:var(--black);
        }
        .form-control{
            width:100%;
            border:1.5px solid #E7E5E0;
            padding:14px 16px;
            font-size:1rem;
            background:#FAFAF8;
            outline:none;
            transition:border-color .15s ease, background .15s ease;
        }
        .form-control:focus{
            border-color:var(--pink);
            background:#fff;
        }
        .mb-3{ margin-bottom:22px; }
        .btn-submit{
            width:100%;
            border:none;
            background:var(--black);
            color:#fff;
            font-weight:700;
            font-size:1rem;
            padding:15px;
            cursor:pointer;
            transition:transform .12s ease, background .15s ease;
        }
        .btn-submit:hover{
            background:var(--pink);
            transform:translateY(-1px);
        }
        .foot-note{
            text-align:center;
            font-size:0.9rem;
            color:var(--gray);
            margin-top:22px;
        }
        .foot-note a{
            color:var(--pink);
            text-decoration:none;
        }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="brand">
        <span class="brand-dot"></span>
        <span class="brand-name">Mobile Money</span>
    </div>

    <h1>Connexion opérateur</h1>
    <p class="subtitle">Accédez au backoffice opérateur avec vos identifiants sécurisés.</p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('operator/loginProcess') ?>" method="post">
        <div class="mb-3">
            <label for="username">Identifiant</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Identifiant" required>
        </div>
        <div class="mb-3">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe" required>
        </div>
        <button type="submit" class="btn-submit">Se connecter</button>
    </form>

    <p class="foot-note">
        Vous n'êtes pas opérateur ? <a href="<?= base_url('/') ?>">Retour à la connexion client</a>.
    </p>
</div>
</body>
</html>
