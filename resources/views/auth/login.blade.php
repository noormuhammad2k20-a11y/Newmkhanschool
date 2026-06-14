<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CampusOS - College Management System | Login</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

<style>
/* ============ GLOBAL ============ */
*{ margin:0; padding:0; box-sizing:border-box; }
.material-symbols-outlined{ font-family:'Material Symbols Outlined'; font-weight:normal; font-style:normal; font-size:16px; line-height:1; vertical-align:middle; }
:focus-visible{ outline:2px solid #6C2BD9; outline-offset:2px; }

body{ font-family:'Inter', sans-serif; background:#EEF0FB; overflow-x: hidden; }

.page{ width:100%; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:12px; }

/* =========================================================
   DESIGN 1 - "Aurora" Purple Theme
========================================================= */
#page1{ 
  background:radial-gradient(circle at 20% 20%, #F2EEFE 0%, #EEF0FB 55%); 
  font-family:'Plus Jakarta Sans',sans-serif; 
}

.card1{
  width:100%; max-width:900px;
  background:#fff; border-radius:18px; overflow:hidden;
  display:grid; grid-template-columns:1fr 1.05fr;
  box-shadow:0 15px 40px -15px rgba(76,29,149,.15);
  max-height: 90vh; /* Limits height on desktop */
}

/* Left panel with the form */
.left1{ 
  padding:20px 30px; 
  display:flex; flex-direction:column; justify-content:center; 
  position:relative; 
  overflow-y:auto; /* Allow scroll inside if content is slightly taller */
}

/* Hide scrollbar for a cleaner look */
.left1::-webkit-scrollbar { width: 4px; }
.left1::-webkit-scrollbar-thumb { background: #E4DBFB; border-radius: 10px; }

.brand1{ display:flex; align-items:center; gap:8px; margin-bottom:10px; }
.brand1 .logo-icon{
  width:32px; height:32px; border-radius:8px;
  background:linear-gradient(135deg,#8B5CF6,#5B21B6);
  display:flex; align-items:center; justify-content:center; color:#fff;
  box-shadow:0 4px 10px -4px rgba(91,33,182,.5);
}
.brand1 .logo-text b{ display:block; font-size:14px; font-weight:800; color:#2D2A4A; letter-spacing:.2px; }
.brand1 .logo-text b span{ color:#7C3AED; }
.brand1 .logo-text small{ display:block; font-size:8.5px; color:#9492AA; letter-spacing:1px; font-weight:600; text-transform:uppercase; }

.badge1{
  display:inline-flex; align-items:center; gap:4px; align-self:flex-start;
  background:#F3EEFE; color:#7C3AED; font-size:10px; font-weight:700;
  padding:4px 8px; border-radius:12px; margin-bottom:6px;
}
.badge1 .material-symbols-outlined{ font-size:12px; }

.left1 h1{ font-size:20px; font-weight:800; color:#221F3B; margin-bottom:2px; letter-spacing:-.5px; }
.left1 .sub{ font-size:11.5px; color:#9492AA; margin-bottom:10px; line-height:1.4; }

.field1{ margin-bottom:8px; }
.field1 label{ display:block; font-size:10px; font-weight:700; color:#5B5874; margin-bottom:3px; letter-spacing:.3px; }
.input-wrap1{
  display:flex; align-items:center; gap:8px;
  background:#F7F6FD; border:1.5px solid #ECEAF6; border-radius:10px;
  padding:8px 12px; transition:.2s;
}
.input-wrap1:focus-within{ border-color:#8B5CF6; background:#fff; box-shadow:0 0 0 4px #F3EEFE; }
.input-wrap1 .material-symbols-outlined{ color:#A7A4C2; font-size:15px; }
.input-wrap1 input{ border:none; outline:none; background:transparent; width:100%; font-size:12px; font-family:'Inter',sans-serif; color:#2D2A4A; }
.input-wrap1 input::placeholder{ color:#BBB9D2; }
.toggle-eye{ cursor:pointer; color:#A7A4C2; transition:.15s; }
.toggle-eye:hover{ color:#7C3AED; }

.row1{ display:flex; justify-content:space-between; align-items:center; margin:2px 0 10px; font-size:10.5px; }
.remember1{ display:flex; align-items:center; gap:6px; color:#7C7A93; cursor:pointer; user-select:none; }

.remember1 input[type="checkbox"] { display: none; }
.remember1 .check-box {
  width: 14px; height: 14px; border-radius: 4px; border: 1.5px solid #D2CFEA;
  display: flex; align-items: center; justify-content: center; background: transparent;
  transition: .2s;
}
.remember1 .check-box .material-symbols-outlined { opacity: 0; color: #fff; font-size: 11px; transition: .2s; }
.remember1 input[type="checkbox"]:checked + .check-box { background: #7C3AED; border-color: #7C3AED; }
.remember1 input[type="checkbox"]:checked + .check-box .material-symbols-outlined { opacity: 1; }

.row1 a{ color:#7C3AED; font-weight:700; text-decoration:none; }
.row1 a:hover{ text-decoration:underline; }

.btn-primary1{
  width:100%; border:none; border-radius:10px; padding:10px;
  background:linear-gradient(135deg,#8B5CF6,#5B21B6); color:#fff;
  font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:12.5px;
  display:flex; align-items:center; justify-content:center; gap:6px;
  cursor:pointer; transition:.2s; box-shadow:0 8px 16px -6px rgba(91,33,182,.5);
}
.btn-primary1:hover{ box-shadow:0 10px 20px -6px rgba(91,33,182,.6); }

/* Demo accounts */
.demo1{ margin-top:10px; background:#F8F7FE; border:1px dashed #D9D5F2; border-radius:10px; padding:8px 12px; }
.demo1 .demo-title{ display:flex; align-items:center; gap:6px; font-weight:700; font-size:10.5px; color:#5B5874; margin-bottom:6px; }
.demo1 .demo-title .material-symbols-outlined{ color:#F5B500; font-size:13px; }
.demo-grid1{ display:grid; grid-template-columns:1fr 1fr; gap:6px; }
.demo-item1{ display:flex; align-items:center; gap:6px; background:#fff; border:1px solid #ECEAF6; border-radius:8px; padding:6px 8px; cursor:pointer; transition:.2s; }
.demo-item1:hover{ border-color:#8B5CF6; }
.demo-item1 .ic{ width:20px; height:20px; border-radius:6px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:12px; flex-shrink:0; }
.demo-item1 .meta .role{ font-size:10px; font-weight:700; color:#2D2A4A; }
.demo-item1 .meta .mail{ font-size:8.5px; color:#A7A4C2; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.ic-admin{ background:linear-gradient(135deg,#8B5CF6,#5B21B6); }
.ic-teacher{ background:linear-gradient(135deg,#34D399,#059669); }
.ic-student{ background:linear-gradient(135deg,#60A5FA,#2563EB); }
.ic-parent{ background:linear-gradient(135deg,#FB923C,#EA580C); }

/* Right panel */
.right1{
  position:relative; background:linear-gradient(160deg,#8B5CF6 0%,#5B21B6 100%);
  display:flex; flex-direction:column; align-items:center; justify-content:center; overflow:hidden; padding:20px;
}
.right1 .grid-pattern{
  position:absolute; inset:0; opacity:.08;
  background-image:linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg,#fff 1px, transparent 1px);
  background-size:20px 20px;
}
.right1::before{ content:''; position:absolute; width:260px; height:260px; border-radius:50%; background:rgba(255,255,255,.06); top:-60px; right:-80px; }
.right1::after{ content:''; position:absolute; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,.05); bottom:-50px; left:-50px; }

.right1 .heading2{ position:relative; z-index:2; text-align:center; color:#fff; margin-bottom:16px; }
.right1 .heading2 h2{ font-size:17px; font-weight:800; margin-bottom:2px; }
.right1 .heading2 p{ font-size:11px; color:#E4DBFB; max-width:260px; margin:0 auto; line-height:1.4; }

.stack1{ position:relative; width:220px; height:220px; z-index:2; }
.panel1{
  position:absolute; width:180px; height:105px; border-radius:10px;
  background:rgba(255,255,255,.97); box-shadow:0 10px 20px -5px rgba(40,15,90,.25);
  padding:10px; display:flex; flex-direction:column; justify-content:space-between;
}
.panel1 .top{ display:flex; align-items:center; gap:6px; }
.panel1 .top .pic{ width:24px; height:24px; border-radius:6px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:13px; }
.panel1 .top .txt b{ display:block; font-size:10.5px; color:#2D2A4A; }
.panel1 .top .txt span{ font-size:8.5px; color:#A7A4C2; }
.panel1 .bar{ height:4px; border-radius:3px; background:#EFEDFB; overflow:hidden; }
.panel1 .bar i{ display:block; height:100%; border-radius:3px; }

.p-admin{ top:0; left:20px; transform:rotate(-6deg); z-index:4; }
.p-teacher{ top:36px; left:0; transform:rotate(4deg); z-index:3; opacity:.97; }
.p-student{ top:72px; left:40px; transform:rotate(-3deg); z-index:2; opacity:.93; }
.p-parent{ top:108px; left:5px; transform:rotate(5deg); z-index:1; opacity:.9; }

.features1{ position:relative; z-index:2; display:flex; gap:8px; margin-top:16px; flex-wrap:wrap; justify-content:center; }
.features1 .feature{
  display:flex; align-items:center; gap:4px;
  background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.18); border-radius:20px;
  padding:6px 12px; backdrop-filter:blur(6px);
}
.features1 .feature .material-symbols-outlined{ font-size:14px; color:#E4DBFB; }
.features1 .feature b{ font-size:10.5px; font-weight:600; color:#fff; letter-spacing:.3px; }

/* =========================================================
   RESPONSIVE
========================================================= */
@media (max-width:980px){
  .card1{ 
    grid-template-columns:1fr; 
    max-width: 500px; 
    margin: 0 auto; 
    max-height: none; /* Allow full height on mobile to prevent squishing */
    height: auto;
  }
  .page { height: auto; min-height: 100vh; padding: 20px 16px; overflow-y: visible; }
  .left1{ padding:30px 24px; overflow-y: visible; }
  .right1{ min-height:320px; order:-1; padding:30px 20px; }
  
  .demo-grid1{ grid-template-columns:1fr 1fr; gap:8px; } /* Keep 2 columns on tablet/large mobile if possible */
  
  .stack1{ transform:scale(.85); height: 180px; margin-bottom: -10px; }
  .features1{ margin-top: 5px; }

  /* Increase input sizing slightly for touch usability on mobile */
  .input-wrap1{ padding: 12px 14px; }
  .input-wrap1 input{ font-size: 13px; }
  .btn-primary1{ padding: 14px; font-size: 14px; }
  .demo1{ padding: 12px; }
}

@media (max-width:480px){
  .demo-grid1{ grid-template-columns:1fr; } /* Stack demo accounts on very small screens */
  .left1{ padding:24px 16px; }
  .right1{ min-height: 280px; padding: 24px 16px; }
  .stack1{ transform:scale(.7); height: 150px; }
  .right1 .heading2 h2 { font-size: 16px; }
}

/* Alert Styling for Laravel Validation Errors */
.alert-error {
  background: #ffdad6; color: #93000a; padding: 8px 12px; border-radius: 8px;
  margin-bottom: 10px; font-size: 11px; font-weight: 500; border: 1px solid #ffb4ab;
  display: flex; align-items: center; gap: 6px;
}
</style>
</head>
<body>

<div class="page" id="page1">
  <div class="card1">

    <div class="left1">
      <div class="brand1">
        <div class="logo-icon"><span class="material-symbols-outlined">school</span></div>
        <div class="logo-text">
          <b>Campus<span>OS</span></b>
          <small>College Management System</small>
        </div>
      </div>

      <span class="badge1"><span class="material-symbols-outlined">verified</span> Secure Campus Login</span>
      <h1>Welcome Back</h1>
      <p class="sub">Please enter your credentials to access your account.</p>

      @if ($errors->any())
        <div class="alert-error">
          <span class="material-symbols-outlined" style="font-size:16px;">error</span>
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="field1">
          <label>Email Address</label>
          <div class="input-wrap1">
            <span class="material-symbols-outlined">mail</span>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@college.edu">
          </div>
        </div>

        <div class="field1">
          <label>Password</label>
          <div class="input-wrap1">
            <span class="material-symbols-outlined">lock</span>
            <input type="password" name="password" required placeholder="Enter your password" id="pass1">
            <span class="material-symbols-outlined toggle-eye" onclick="togglePass('pass1', this)">visibility_off</span>
          </div>
        </div>

        <div class="row1">
          <label class="remember1">
            <input type="checkbox" name="remember" id="remember">
            <span class="check-box"><span class="material-symbols-outlined">check</span></span>
            Remember me
          </label>
          <a href="#">Forgot?</a>
        </div>

        <button type="submit" class="btn-primary1">
          Sign In to Dashboard
          <span class="material-symbols-outlined">arrow_forward</span>
        </button>
      </form>

      <div class="demo1">
        <div class="demo-title"><span class="material-symbols-outlined">lightbulb</span> Demo Accounts (Pass: password)</div>
        <div class="demo-grid1">
          <div class="demo-item1" onclick="document.querySelector('input[name=email]').value='admin@school.com'; document.querySelector('input[name=password]').value='password';">
            <div class="ic ic-admin"><span class="material-symbols-outlined">admin_panel_settings</span></div>
            <div class="meta"><div class="role">Administrator</div><div class="mail">admin@school.com</div></div>
          </div>
          <div class="demo-item1" onclick="document.querySelector('input[name=email]').value='teacher@school.com'; document.querySelector('input[name=password]').value='password';">
            <div class="ic ic-teacher"><span class="material-symbols-outlined">person_play</span></div>
            <div class="meta"><div class="role">Teacher</div><div class="mail">teacher@school.com</div></div>
          </div>
          <div class="demo-item1" onclick="document.querySelector('input[name=email]').value='student@school.com'; document.querySelector('input[name=password]').value='password';">
            <div class="ic ic-student"><span class="material-symbols-outlined">school</span></div>
            <div class="meta"><div class="role">Student</div><div class="mail">student@school.com</div></div>
          </div>
          <div class="demo-item1" onclick="document.querySelector('input[name=email]').value='parent@school.com'; document.querySelector('input[name=password]').value='password';">
            <div class="ic ic-parent"><span class="material-symbols-outlined">family_restroom</span></div>
            <div class="meta"><div class="role">Parent</div><div class="mail">parent@school.com</div></div>
          </div>
        </div>
      </div>
    </div>

    <div class="right1">
      <div class="grid-pattern"></div>
      <div class="heading2">
        <h2>One Platform. Entire Campus.</h2>
        <p>Manage admissions, attendance, examinations, fee collection and faculty operations — all from a single dashboard.</p>
      </div>

      <div class="stack1">
        <div class="panel1 p-parent">
          <div class="top"><div class="pic ic-parent"><span class="material-symbols-outlined">family_restroom</span></div>
            <div class="txt"><b>Parent Portal</b><span>Track ward's progress</span></div></div>
          <div class="bar"><i style="width:55%; background:#FB923C;"></i></div>
        </div>
        <div class="panel1 p-student">
          <div class="top"><div class="pic ic-student"><span class="material-symbols-outlined">school</span></div>
            <div class="txt"><b>Student Portal</b><span>Assignments & Results</span></div></div>
          <div class="bar"><i style="width:70%; background:#60A5FA;"></i></div>
        </div>
        <div class="panel1 p-teacher">
          <div class="top"><div class="pic ic-teacher"><span class="material-symbols-outlined">person_play</span></div>
            <div class="txt"><b>Faculty Portal</b><span>Attendance & Grading</span></div></div>
          <div class="bar"><i style="width:80%; background:#34D399;"></i></div>
        </div>
        <div class="panel1 p-admin">
          <div class="top"><div class="pic ic-admin"><span class="material-symbols-outlined">admin_panel_settings</span></div>
            <div class="txt"><b>Admin Panel</b><span>Manage entire college</span></div></div>
          <div class="bar"><i style="width:90%; background:#8B5CF6;"></i></div>
        </div>
      </div>

      <div class="features1">
        <div class="feature">
          <span class="material-symbols-outlined">security</span>
          <b>Secure</b>
        </div>
        <div class="feature">
          <span class="material-symbols-outlined">bolt</span>
          <b>Fast</b>
        </div>
        <div class="feature">
          <span class="material-symbols-outlined">sync</span>
          <b>Real-time</b>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
function togglePass(id, el){
  const input = document.getElementById(id);
  if(input.type === 'password'){ input.type = 'text'; el.textContent = 'visibility'; }
  else{ input.type = 'password'; el.textContent = 'visibility_off'; }
}
</script>
</body>
</html>
