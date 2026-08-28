<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMC School Clinic Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
        html{scroll-behavior:smooth;}
        :root{
            --navy-deep:#020b18;--navy-dark:#040f22;--navy-mid:#071830;
            --navy-card:#091a33;--navy-border:#0d2545;
            --blue-bright:#38bdf8;--blue-mid:#1e6fd9;--cyan:#00d4ff;
            --text-head:#f0f8ff;--text-body:#94b8d8;--text-muted:#4a6b8a;
            --glass-bg:rgba(9,26,51,.65);--glass-border:rgba(56,189,248,.12);
        }
        body{font-family:'Segoe UI',system-ui,sans-serif;background:var(--navy-deep);color:var(--text-body);line-height:1.6;overflow-x:hidden;}
        #bg-canvas{position:fixed;inset:0;z-index:0;pointer-events:none;}
        nav{position:fixed;top:0;left:0;right:0;z-index:1000;background:rgba(2,11,24,.85);backdrop-filter:blur(20px);border-bottom:1px solid var(--glass-border);transition:all .3s;}
        nav.scrolled{background:rgba(2,11,24,.98);box-shadow:0 4px 30px rgba(0,0,0,.5);}
        .navbar{max-width:1300px;margin:0 auto;padding:0 24px;display:flex;justify-content:space-between;align-items:center;height:68px;}
        .logo{display:flex;align-items:center;gap:10px;text-decoration:none;}
        .logo img{width:40px;height:40px;object-fit:cover;border-radius:50%;filter:drop-shadow(0 0 8px rgba(56,189,248,.5));}
        .logo-text{font-size:15px;font-weight:700;color:var(--text-head);display:block;}
        .logo-sub{font-size:10px;color:var(--blue-bright);letter-spacing:1px;text-transform:uppercase;display:block;margin-top:-2px;}
        .nav-links{display:flex;gap:28px;list-style:none;}
        .nav-links a{color:var(--text-body);text-decoration:none;font-size:13px;font-weight:500;transition:color .2s;position:relative;}
        .nav-links a::after{content:'';position:absolute;bottom:-4px;left:0;right:0;height:1px;background:var(--blue-bright);transform:scaleX(0);transition:transform .2s;}
        .nav-links a:hover{color:var(--blue-bright);}
        .nav-links a:hover::after{transform:scaleX(1);}
        .nav-actions{display:flex;gap:10px;align-items:center;}
        .btn-ghost{padding:8px 18px;border-radius:6px;text-decoration:none;font-size:13px;font-weight:600;color:var(--text-body);border:1px solid var(--navy-border);background:transparent;transition:all .2s;}
        .btn-ghost:hover{color:var(--blue-bright);border-color:var(--blue-bright);background:rgba(56,189,248,.06);}
        .btn-nav-cta{padding:9px 20px;border-radius:6px;text-decoration:none;font-size:13px;font-weight:700;color:#fff;background:linear-gradient(135deg,#1e6fd9,#38bdf8);display:inline-flex;align-items:center;gap:6px;transition:all .2s;box-shadow:0 0 20px rgba(56,189,248,.2);}
        .btn-nav-cta:hover{transform:translateY(-2px);box-shadow:0 6px 24px rgba(56,189,248,.35);}
        .hamburger{display:none;background:none;border:1px solid var(--navy-border);color:var(--text-body);padding:8px 10px;border-radius:6px;cursor:pointer;font-size:16px;transition:all .2s;}
        .hamburger:hover{border-color:var(--blue-bright);color:var(--blue-bright);}
        .mobile-menu{display:none;position:fixed;top:68px;left:0;right:0;background:rgba(4,15,34,.98);backdrop-filter:blur(20px);border-bottom:1px solid var(--glass-border);padding:20px 24px;flex-direction:column;gap:14px;z-index:999;animation:slideDown .25s ease;}
        .mobile-menu.open{display:flex;}
        .mobile-menu a{color:var(--text-body);text-decoration:none;font-size:14px;padding:10px 0;border-bottom:1px solid var(--navy-border);transition:color .2s;}
        .mobile-menu a:hover{color:var(--blue-bright);}
        .hero{position:relative;z-index:1;min-height:100vh;display:flex;align-items:center;padding:100px 24px 60px;}
        .hero-inner{max-width:1300px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;width:100%;}
        .hero-centered{grid-template-columns:1fr;text-align:center;max-width:820px;}
        .hero-desc-wide{max-width:680px;margin:0 auto 32px;}
        .hero-btns-centered{justify-content:center;}
        .eyebrow{display:inline-flex;align-items:center;gap:8px;background:rgba(56,189,248,.08);border:1px solid rgba(56,189,248,.2);border-radius:40px;padding:5px 14px 5px 10px;font-size:11px;font-weight:700;color:var(--blue-bright);letter-spacing:.8px;text-transform:uppercase;margin-bottom:20px;}
        .eyebrow .dot{width:6px;height:6px;border-radius:50%;background:var(--blue-bright);animation:pulseAnim 2s infinite;}
        @keyframes pulseAnim{0%,100%{box-shadow:0 0 0 0 rgba(56,189,248,.7);}50%{box-shadow:0 0 0 6px rgba(56,189,248,0);}}
        .hero-title{font-size:56px;font-weight:800;line-height:1.15;color:var(--text-head);margin-bottom:18px;}
        .gradient-text{background:linear-gradient(90deg,#38bdf8,#00d4ff,#1e6fd9,#38bdf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;background-size:300%;animation:gradShift 5s linear infinite;}
        @keyframes gradShift{0%{background-position:0%;}100%{background-position:300%;}}
        .hero-desc{font-size:16px;color:var(--text-body);line-height:1.8;margin-bottom:32px;max-width:480px;}
        .hero-btns{display:flex;gap:12px;flex-wrap:wrap;}
        .btn-hero-primary{padding:14px 28px;border-radius:8px;background:linear-gradient(135deg,#1e6fd9,#38bdf8);color:#fff;text-decoration:none;font-size:14px;font-weight:700;display:inline-flex;align-items:center;gap:8px;transition:all .25s;box-shadow:0 0 30px rgba(56,189,248,.25);}
        .btn-hero-primary:hover{transform:translateY(-3px);box-shadow:0 12px 36px rgba(56,189,248,.42);}
        .btn-hero-outline{padding:13px 24px;border-radius:8px;color:var(--blue-bright);border:1px solid rgba(56,189,248,.3);background:rgba(56,189,248,.05);text-decoration:none;font-size:14px;font-weight:600;display:inline-flex;align-items:center;gap:8px;transition:all .2s;}
        .btn-hero-outline:hover{background:rgba(56,189,248,.12);border-color:var(--blue-bright);}
        .hero-visual{position:relative;display:flex;align-items:center;justify-content:center;}
        .hero-card{background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:16px;backdrop-filter:blur(20px);padding:24px;width:100%;max-width:460px;box-shadow:0 20px 60px rgba(0,0,0,.5),0 0 40px rgba(56,189,248,.06);animation:floatCard 4s ease-in-out infinite;}
        @keyframes floatCard{0%,100%{transform:translateY(0);}50%{transform:translateY(-14px);}}
        .hero-card-header{display:flex;align-items:center;gap:10px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--navy-border);}
        .hero-card-icon{width:36px;height:36px;border-radius:8px;background:linear-gradient(135deg,#1e6fd9,#38bdf8);display:flex;align-items:center;justify-content:center;font-size:16px;color:white;}
        .hero-card-title{font-size:13px;font-weight:700;color:var(--text-head);}
        .hero-card-sub{font-size:11px;color:var(--text-muted);}
        .hero-kpi-row{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:16px;}
        .hero-kpi{background:rgba(7,24,48,.8);border:1px solid var(--navy-border);border-radius:8px;padding:12px;text-align:center;}
        .hero-kpi-val{font-size:22px;font-weight:800;color:var(--text-head);}
        .hero-kpi-lbl{font-size:9px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-top:2px;}
        .hero-kpi.blue .hero-kpi-val{color:#38bdf8;}
        .hero-kpi.green .hero-kpi-val{color:#27ae60;}
        .hero-kpi.orange .hero-kpi-val{color:#f39c12;}
        .hero-bars{display:flex;align-items:flex-end;gap:4px;height:50px;margin-top:4px;}
        .hero-bar{flex:1;border-radius:3px 3px 0 0;background:linear-gradient(to top,#1e6fd9,#38bdf8);opacity:.65;animation:barGrow .8s ease both;}
        @keyframes barGrow{from{transform:scaleY(0);transform-origin:bottom;}to{transform:scaleY(1);transform-origin:bottom;}}
        .hero-status{display:flex;align-items:center;gap:6px;font-size:10px;color:#27ae60;font-weight:600;margin-top:14px;}
        .status-dot{width:6px;height:6px;border-radius:50%;background:#27ae60;animation:pulseAnim 2s infinite;}
        .float-badge{position:absolute;background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:10px;backdrop-filter:blur(10px);padding:8px 12px;font-size:11px;font-weight:700;color:var(--text-head);display:flex;align-items:center;gap:6px;box-shadow:0 4px 20px rgba(0,0,0,.4);}
        .fb-1{top:8%;left:-8%;animation:fb1 3s ease-in-out infinite;}
        .fb-2{bottom:12%;right:-8%;animation:fb2 3.5s ease-in-out infinite;}
        @keyframes fb1{0%,100%{transform:translateY(0) rotate(-2deg);}50%{transform:translateY(-8px) rotate(2deg);}}
        @keyframes fb2{0%,100%{transform:translateY(0) rotate(1deg);}50%{transform:translateY(-10px) rotate(-1deg);}}
        section{position:relative;z-index:1;}
        .section-wrap{max-width:1300px;margin:0 auto;padding:0 24px;}
        .section-head{text-align:center;margin-bottom:56px;}
        .section-tag{display:inline-block;font-size:11px;font-weight:700;color:var(--blue-bright);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:12px;}
        .section-title{font-size:40px;font-weight:800;color:var(--text-head);margin-bottom:12px;line-height:1.2;}
        .section-desc{font-size:16px;color:var(--text-body);max-width:580px;margin:0 auto;line-height:1.8;}
        .stats-band{padding:60px 24px;border-top:1px solid var(--navy-border);border-bottom:1px solid var(--navy-border);background:linear-gradient(135deg,rgba(7,24,48,.8),rgba(4,15,34,.9));}
        .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);}
        .stat-item{text-align:center;padding:24px;border-right:1px solid var(--navy-border);}
        .stat-item:last-child{border-right:none;}
        .stat-num{font-size:48px;font-weight:800;background:linear-gradient(135deg,#38bdf8,#00d4ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1;}
        .stat-lbl{font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.8px;margin-top:8px;}
        .services-section{padding:100px 24px;}
        .services-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
        .service-card{background:var(--navy-card);border:1px solid var(--navy-border);border-radius:14px;padding:28px 24px;transition:all .3s;position:relative;overflow:hidden;}
        .service-card::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(56,189,248,.04),transparent);opacity:0;transition:opacity .3s;}
        .service-card:hover{border-color:rgba(56,189,248,.3);transform:translateY(-6px);box-shadow:0 16px 40px rgba(0,0,0,.4),0 0 30px rgba(56,189,248,.08);}
        .service-card:hover::before{opacity:1;}
        .svc-icon{width:52px;height:52px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:18px;background:rgba(56,189,248,.08);border:1px solid rgba(56,189,248,.15);color:var(--blue-bright);transition:all .3s;}
        .service-card:hover .svc-icon{background:linear-gradient(135deg,#1e6fd9,#38bdf8);border-color:transparent;color:white;}
        .service-card h3{font-size:16px;font-weight:700;color:var(--text-head);margin-bottom:10px;}
        .service-card p{font-size:13px;color:var(--text-body);line-height:1.7;}
        .how-section{padding:100px 24px;background:linear-gradient(135deg,rgba(7,24,48,.5),transparent);}
        .steps-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0;position:relative;}
        .steps-grid::before{content:'';position:absolute;top:36px;left:10%;right:10%;height:1px;background:linear-gradient(90deg,transparent,rgba(56,189,248,.4),transparent);}
        .step-item{text-align:center;padding:0 20px;}
        .step-num{width:72px;height:72px;border-radius:50%;border:2px solid rgba(56,189,248,.3);background:linear-gradient(135deg,#1e6fd9,#38bdf8);color:white;font-size:22px;font-weight:800;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;box-shadow:0 0 24px rgba(56,189,248,.3);}
        .step-item h3{font-size:16px;font-weight:700;color:var(--text-head);margin-bottom:10px;}
        .step-item p{font-size:13px;color:var(--text-body);line-height:1.7;}
        .community-section{padding:100px 24px;}
        .community-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}
        .community-card{background:var(--navy-card);border:1px solid var(--navy-border);border-radius:14px;padding:28px 22px;transition:all .3s;text-align:center;}
        .community-card:hover{border-color:rgba(56,189,248,.3);transform:translateY(-4px);box-shadow:0 12px 30px rgba(0,0,0,.4);}
        .comm-icon{font-size:32px;margin-bottom:14px;display:block;}
        .community-card h3{font-size:16px;font-weight:700;color:var(--text-head);margin-bottom:10px;}
        .community-card p{font-size:13px;color:var(--text-body);line-height:1.7;}
        .wellness-section{padding:100px 24px;background:linear-gradient(135deg,rgba(7,24,48,.5),transparent);}
        .wellness-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
        .wellness-item{background:var(--navy-card);border:1px solid var(--navy-border);border-left:3px solid var(--blue-bright);border-radius:12px;padding:22px 20px;transition:all .3s;display:flex;gap:14px;align-items:flex-start;}
        .wellness-item:hover{border-left-color:var(--cyan);transform:translateX(4px);box-shadow:0 8px 24px rgba(0,0,0,.3);}
        .well-emoji{font-size:22px;flex-shrink:0;margin-top:2px;}
        .wellness-item h4{font-size:14px;font-weight:700;color:var(--text-head);margin-bottom:6px;}
        .wellness-item p{font-size:12px;color:var(--text-body);line-height:1.7;}
        .cta-section{padding:100px 24px;text-align:center;position:relative;overflow:hidden;}
        .cta-section::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 60% at 50% 50%,rgba(30,111,217,.2),transparent),linear-gradient(135deg,rgba(7,24,48,.96),rgba(4,15,34,.96));}
        .cta-inner{position:relative;z-index:1;max-width:680px;margin:0 auto;}
        .cta-section h2{font-size:48px;font-weight:800;color:var(--text-head);margin-bottom:16px;line-height:1.2;}
        .cta-section p{font-size:16px;color:var(--text-body);margin-bottom:36px;line-height:1.8;}
        .cta-btns{display:flex;gap:14px;justify-content:center;flex-wrap:wrap;}
        .btn-cta-main{padding:16px 36px;border-radius:10px;background:linear-gradient(135deg,#1e6fd9,#38bdf8);color:white;text-decoration:none;font-size:15px;font-weight:700;display:inline-flex;align-items:center;gap:8px;transition:all .25s;box-shadow:0 0 40px rgba(56,189,248,.3);}
        .btn-cta-main:hover{transform:translateY(-3px);box-shadow:0 14px 40px rgba(56,189,248,.45);}
        .btn-cta-ghost{padding:15px 28px;border-radius:10px;color:var(--blue-bright);border:1px solid rgba(56,189,248,.3);background:rgba(56,189,248,.05);text-decoration:none;font-size:15px;font-weight:600;display:inline-flex;align-items:center;gap:8px;transition:all .2s;}
        .btn-cta-ghost:hover{background:rgba(56,189,248,.1);border-color:var(--blue-bright);}
        footer{background:var(--navy-dark);border-top:1px solid var(--navy-border);padding:60px 24px 24px;}
        .footer-grid{max-width:1300px;margin:0 auto 40px;display:grid;grid-template-columns:1.5fr 1fr 1fr 1.5fr;gap:40px;}
        .footer-brand img{width:36px;margin-bottom:12px;}
        .footer-brand-name{font-size:15px;font-weight:700;color:var(--text-head);}
        .footer-brand p{font-size:13px;color:var(--text-muted);line-height:1.8;margin-top:10px;}
        footer h5{font-size:12px;font-weight:700;color:var(--text-head);margin-bottom:16px;text-transform:uppercase;letter-spacing:.8px;}
        footer ul{list-style:none;}footer ul li{margin-bottom:10px;}
        footer a{color:var(--text-muted);text-decoration:none;font-size:13px;transition:color .2s;}
        footer a:hover{color:var(--blue-bright);}
        .footer-contact p{font-size:13px;color:var(--text-muted);margin-bottom:8px;display:flex;align-items:center;gap:8px;}
        .footer-contact i{color:var(--blue-bright);width:14px;}
        .footer-bottom{max-width:1300px;margin:0 auto;padding-top:20px;border-top:1px solid var(--navy-border);display:flex;justify-content:space-between;align-items:center;font-size:12px;color:var(--text-muted);}
        .reveal{opacity:0;transform:translateY(30px);transition:opacity .7s ease,transform .7s ease;}
        .reveal.revealed{opacity:1;transform:translateY(0);}
        .reveal-d1{transition-delay:.1s;}.reveal-d2{transition-delay:.2s;}.reveal-d3{transition-delay:.3s;}.reveal-d4{transition-delay:.4s;}.reveal-d5{transition-delay:.5s;}
        @keyframes slideDown{from{opacity:0;transform:translateY(-12px);}to{opacity:1;transform:translateY(0);}}
        @media(max-width:1024px){.hero-inner{grid-template-columns:1fr;gap:48px;}.hero-title{font-size:42px;}.fb-1,.fb-2{display:none;}.services-grid{grid-template-columns:repeat(2,1fr);}.community-grid{grid-template-columns:repeat(2,1fr);}.stats-grid{grid-template-columns:repeat(2,1fr);}.stat-item:nth-child(2){border-right:none;}.steps-grid{grid-template-columns:repeat(2,1fr);gap:32px;}.steps-grid::before{display:none;}.footer-grid{grid-template-columns:repeat(2,1fr);}}
        @media(max-width:768px){.nav-links,.nav-actions{display:none;}.hamburger{display:block;}.hero-title{font-size:32px;}.section-title{font-size:28px;}.services-grid{grid-template-columns:1fr;}.community-grid{grid-template-columns:repeat(2,1fr);}.wellness-grid{grid-template-columns:1fr;}.steps-grid{grid-template-columns:1fr;}.cta-section h2{font-size:32px;}.cta-btns{flex-direction:column;align-items:center;}.footer-grid{grid-template-columns:1fr;gap:28px;}.footer-bottom{flex-direction:column;gap:8px;text-align:center;}}
    </style>
</head>
<body>
<canvas id="bg-canvas"></canvas>
<nav id="mainNav">
    <div class="navbar">
        <a href="/" class="logo">
            <img src="{{ asset('images/cmc-logo.png') }}" alt="CMC Logo">
            <div><span class="logo-text">CMC Clinic</span><span class="logo-sub">School Clinic System</span></div>
        </a>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#wellness">Wellness</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn-ghost"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="{{ route('login') }}" class="btn-nav-cta">Clinic Portal <i class="fas fa-arrow-right"></i></a>
        </div>
        <button class="hamburger" onclick="toggleMobile()"><i class="fas fa-bars" id="ham-icon"></i></button>
    </div>
</nav>
<div class="mobile-menu" id="mobileMenu">
    <a href="#home" onclick="closeMobile()">Home</a>
    <a href="#about" onclick="closeMobile()">About</a>
    <a href="#services" onclick="closeMobile()">Services</a>
    <a href="#wellness" onclick="closeMobile()">Wellness</a>
    <a href="#contact" onclick="closeMobile()">Contact</a>
    <a href="{{ route('login') }}" style="color:#38bdf8;font-weight:700;border-bottom:none;"><i class="fas fa-sign-in-alt"></i> Access Clinic Portal</a>
</div>
<section class="hero" id="home">
    <div class="hero-inner hero-centered">
        <div class="eyebrow"><span class="dot"></span> CMC Clinic System</div>
        <h1 class="hero-title">Your Health.<br><span class="gradient-text">Our Priority.</span></h1>
        <p class="hero-desc hero-desc-wide">A modern, centralized clinic management platform for CMC students, faculty, and staff — providing organized, accessible, and efficient healthcare services.</p>
        <div class="hero-btns hero-btns-centered">
            <a href="{{ route('login') }}" class="btn-hero-primary"><i class="fas fa-sign-in-alt"></i> Access Clinic Portal</a>
            <a href="#about" class="btn-hero-outline">Learn More <i class="fas fa-arrow-down"></i></a>
        </div>
    </div>
</section>
<div class="stats-band" id="about">
    <div class="section-wrap">
        <div class="stats-grid">
            <div class="stat-item reveal"><div class="stat-num" data-count="500" data-suffix="+">0+</div><div class="stat-lbl">Patients Served</div></div>
            <div class="stat-item reveal reveal-d1"><div class="stat-num" data-count="6" data-suffix="">0</div><div class="stat-lbl">Core Services</div></div>
            <div class="stat-item reveal reveal-d2"><div class="stat-num" data-count="24" data-suffix="">0</div><div class="stat-lbl">Hours Access</div></div>
            <div class="stat-item reveal reveal-d3"><div class="stat-num" data-count="100" data-suffix="%">0%</div><div class="stat-lbl">Digital Records</div></div>
        </div>
    </div>
</div>
<section class="services-section" id="services">
    <div class="section-wrap">
        <div class="section-head reveal"><span class="section-tag">What We Offer</span><h2 class="section-title">Clinic Services &amp; Features</h2><p class="section-desc">Comprehensive healthcare management tools built for the CMC community.</p></div>
        <div class="services-grid">
            <div class="service-card reveal"><div class="svc-icon"><i class="fas fa-stethoscope"></i></div><h3>Clinic Visit Records</h3><p>Record and manage student, faculty, and staff clinic visits with detailed history and follow-up tracking.</p></div>
            <div class="service-card reveal reveal-d1"><div class="svc-icon"><i class="fas fa-file-medical"></i></div><h3>Digital Health Records</h3><p>Maintain organized clinical records and health information accessible to authorized clinic personnel.</p></div>
            <div class="service-card reveal reveal-d2"><div class="svc-icon"><i class="fas fa-heartbeat"></i></div><h3>Vital Signs Monitoring</h3><p>Track recorded vital signs and quickly identify abnormal readings for proactive patient care.</p></div>
            <div class="service-card reveal reveal-d3"><div class="svc-icon"><i class="fas fa-pills"></i></div><h3>Medicine Inventory</h3><p>Track available medicines, monitor stock levels, receive low-stock alerts, and manage expiry dates.</p></div>
            <div class="service-card reveal reveal-d4"><div class="svc-icon"><i class="fas fa-calendar-check"></i></div><h3>Appointment Scheduling</h3><p>Manage clinic appointments and follow-up schedules for better patient care coordination.</p></div>
            <div class="service-card reveal reveal-d5"><div class="svc-icon"><i class="fas fa-bell"></i></div><h3>Notifications &amp; Reminders</h3><p>Receive important clinic follow-up reminders and real-time system notifications.</p></div>
        </div>
    </div>
</section>
<section class="how-section">
    <div class="section-wrap">
        <div class="section-head reveal"><span class="section-tag">Getting Started</span><h2 class="section-title">How the Clinic Portal Works</h2><p class="section-desc">Simple steps to access CMC's digital healthcare system.</p></div>
        <div class="steps-grid">
            <div class="step-item reveal"><div class="step-num">01</div><h3>Register</h3><p>Create your clinic portal account using your CMC identification information.</p></div>
            <div class="step-item reveal reveal-d1"><div class="step-num">02</div><h3>Get Approved</h3><p>Wait for account verification and approval from the clinic administrator.</p></div>
            <div class="step-item reveal reveal-d2"><div class="step-num">03</div><h3>Access Services</h3><p>Log in and access your health records, appointments, and clinic information.</p></div>
            <div class="step-item reveal reveal-d3"><div class="step-num">04</div><h3>Stay Informed</h3><p>Receive follow-up reminders and stay connected with clinic health updates.</p></div>
        </div>
    </div>
</section>
<section class="community-section">
    <div class="section-wrap">
        <div class="section-head reveal"><span class="section-tag">Who We Serve</span><h2 class="section-title">Serving the CMC Community</h2><p class="section-desc">Healthcare services designed for every member of Carmen Municipal College.</p></div>
        <div class="community-grid">
            <div class="community-card reveal"><span class="comm-icon">&#x1F468;&#x200D;&#x1F393;</span><h3>Students</h3><p>Easy access to clinic services, health records, and appointment management tailored for student needs.</p></div>
            <div class="community-card reveal reveal-d1"><span class="comm-icon">&#x1F468;&#x200D;&#x1F3EB;</span><h3>Faculty</h3><p>Organized healthcare information and clinic assistance with priority scheduling and record access.</p></div>
            <div class="community-card reveal reveal-d2"><span class="comm-icon">&#x1F454;</span><h3>Staff</h3><p>Accessible clinic services and appointment management integrated with your work schedule.</p></div>
            <div class="community-card reveal reveal-d3"><span class="comm-icon">&#x2695;&#xFE0F;</span><h3>Clinic Personnel</h3><p>Powerful tools for managing patient records, visits, medicines, and follow-ups efficiently.</p></div>
        </div>
    </div>
</section>
<section class="wellness-section" id="wellness">
    <div class="section-wrap">
        <div class="section-head reveal"><span class="section-tag">Health Information</span><h2 class="section-title">Promoting Health &amp; Wellness</h2><p class="section-desc">Key health guidance for the CMC community.</p></div>
        <div class="wellness-grid">
            <div class="wellness-item reveal"><span class="well-emoji">&#x1F6E1;&#xFE0F;</span><div><h4>Preventive Healthcare</h4><p>Regular checkups and preventive care help identify and prevent health issues early.</p></div></div>
            <div class="wellness-item reveal reveal-d1"><span class="well-emoji">&#x1F3C3;</span><div><h4>Healthy Habits</h4><p>Healthy eating, regular exercise, and adequate sleep support overall wellness.</p></div></div>
            <div class="wellness-item reveal reveal-d2"><span class="well-emoji">&#x1F48A;</span><div><h4>Medication Awareness</h4><p>Understanding medications, dosages, and proper usage ensures safe healthcare.</p></div></div>
            <div class="wellness-item reveal reveal-d3"><span class="well-emoji">&#x1F4CA;</span><div><h4>Health Monitoring</h4><p>Tracking vital signs and health metrics helps maintain awareness of your health status.</p></div></div>
            <div class="wellness-item reveal reveal-d4"><span class="well-emoji">&#x2705;</span><div><h4>Clinic Follow-ups</h4><p>Attending scheduled follow-ups ensures continuity of care and better outcomes.</p></div></div>
            <div class="wellness-item reveal reveal-d5"><span class="well-emoji">&#x1F9E0;</span><div><h4>Mental Wellness</h4><p>Mental health is equally important - seek support when needed for total wellbeing.</p></div></div>
        </div>
    </div>
</section>
<section class="cta-section" id="contact">
    <div class="cta-inner">
        <div class="eyebrow" style="margin:0 auto 20px;width:fit-content;"><span class="dot"></span> Ready to Get Started?</div>
        <h2>Your Health Matters at CMC</h2>
        <p>Access the CMC School Clinic portal and stay connected with the healthcare services available to the entire CMC community.</p>
        <div class="cta-btns">
            <a href="{{ route('login') }}" class="btn-cta-main"><i class="fas fa-sign-in-alt"></i> Access Clinic Portal</a>
            <a href="#about" class="btn-cta-ghost"><i class="fas fa-info-circle"></i> Learn More</a>
        </div>
    </div>
</section>
<footer>
    <div class="footer-grid">
        <div class="footer-brand">
            <img src="{{ asset('images/cmc-logo.png') }}" alt="CMC Logo">
            <div class="footer-brand-name">CMC School Clinic</div>
            <p>Serving the Carmen Municipal College community with digital, organized, and efficient healthcare management.</p>
        </div>
        <div>
            <h5>Navigation</h5>
            <ul><li><a href="#home">Home</a></li><li><a href="#about">About</a></li><li><a href="#services">Services</a></li><li><a href="#wellness">Health Info</a></li></ul>
        </div>
        <div>
            <h5>Quick Access</h5>
            <ul><li><a href="{{ route('login') }}">Login</a></li><li><a href="{{ route('register') }}">Register</a></li><li><a href="{{ route('register.clinic-staff') }}">Staff Registration</a></li></ul>
        </div>
        <div class="footer-contact">
            <h5>Contact</h5>
            <p><i class="fas fa-map-marker-alt"></i> Poblacion Norte, Carmen, Bohol, Philippines</p>
            <p><i class="fas fa-phone"></i> (038) 539-0002</p>
            <p><i class="fas fa-envelope"></i> clinic@cmc.edu.ph</p>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; 2026 Carmen Municipal College School Clinic Management System. All rights reserved.</span>
        <span style="color:var(--blue-bright);">Built with care for CMC</span>
    </div>
</footer>
<script>
(function(){
    var canvas=document.getElementById('bg-canvas'),ctx=canvas.getContext('2d'),W,H,pts=[];
    function resize(){W=canvas.width=window.innerWidth;H=canvas.height=window.innerHeight;}
    function Pt(){this.x=Math.random()*W;this.y=Math.random()*H;this.r=Math.random()*1.4+.3;this.vx=(Math.random()-.5)*.22;this.vy=(Math.random()-.5)*.22;this.a=Math.random()*.45+.1;}
    function init(){pts=[];var n=Math.floor(W*H/9000);for(var i=0;i<n;i++)pts.push(new Pt());}
    function draw(){
        ctx.clearRect(0,0,W,H);
        for(var i=0;i<pts.length;i++)for(var j=i+1;j<pts.length;j++){var dx=pts[i].x-pts[j].x,dy=pts[i].y-pts[j].y,d=Math.sqrt(dx*dx+dy*dy);if(d<130){ctx.beginPath();ctx.strokeStyle='rgba(56,189,248,'+(0.07*(1-d/130))+')';ctx.lineWidth=.5;ctx.moveTo(pts[i].x,pts[i].y);ctx.lineTo(pts[j].x,pts[j].y);ctx.stroke();}}
        for(var k=0;k<pts.length;k++){var p=pts[k];ctx.beginPath();ctx.arc(p.x,p.y,p.r,0,Math.PI*2);ctx.fillStyle='rgba(56,189,248,'+p.a+')';ctx.fill();p.x+=p.vx;p.y+=p.vy;if(p.x<0||p.x>W)p.vx*=-1;if(p.y<0||p.y>H)p.vy*=-1;}
        requestAnimationFrame(draw);
    }
    window.addEventListener('resize',function(){resize();init();});
    resize();init();draw();
})();
window.addEventListener('scroll',function(){document.getElementById('mainNav').classList.toggle('scrolled',window.scrollY>40);});
function toggleMobile(){var m=document.getElementById('mobileMenu'),i=document.getElementById('ham-icon');m.classList.toggle('open');i.className=m.classList.contains('open')?'fas fa-times':'fas fa-bars';}
function closeMobile(){document.getElementById('mobileMenu').classList.remove('open');document.getElementById('ham-icon').className='fas fa-bars';}
document.addEventListener('click',function(e){if(!document.querySelector('.navbar').contains(e.target)&&!document.getElementById('mobileMenu').contains(e.target))closeMobile();});
var revObs=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting)e.target.classList.add('revealed');});},{threshold:.1});
document.querySelectorAll('.reveal').forEach(function(el){revObs.observe(el);});
var cntObs=new IntersectionObserver(function(entries){entries.forEach(function(e){if(!e.isIntersecting)return;var el=e.target,target=parseInt(el.dataset.count),suffix=el.dataset.suffix||'',start=null;var step=function(ts){if(!start)start=ts;var p=Math.min((ts-start)/1600,1),eased=1-Math.pow(1-p,3);el.textContent=Math.floor(eased*target)+suffix;if(p<1)requestAnimationFrame(step);};requestAnimationFrame(step);cntObs.unobserve(el);});},{threshold:.3});
document.querySelectorAll('[data-count]').forEach(function(el){cntObs.observe(el);});
</script>
</body>
</html>
