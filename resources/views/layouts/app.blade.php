<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Andriani - Promomedia</title>
  <link rel="icon" sizes="57x57" href="https://www.andrianispa.com/wp-content/uploads/2022/04/favicon-150x150.webp" type="image/x-icon" />

  <!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/tsparticles@2.11.1/tsparticles.bundle.min.js"></script>

<!-- Font Awesome + Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- AdminLTE CSS -->
<link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">

<!-- Imposta primary color verde -->
<style>
  :root {
    --bs-primary: #255459 !important;
    --bs-primary-rgb: 37, 84, 89 !important;
  }
  .bg-primary, .btn-primary {
    background-color: #255459 !important;
    border-color: #255459 !important;
  }


  .pagination .page-item.active .page-link {
    background-color: #255459;
    border-color: #255459;
    color: #fff;
  }

  .pagination .page-link {
    color: #255459;
    border-radius: 0.25rem;
  }

  .pagination .page-link:hover {
    background-color: #1e4448;
    color: #fff;
  }

</style>


</head>
  <body class="layout-fixed sidebar-expand-lg sidebar sidebar-collapse bg-body-tertiary">
        <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
          </ul>
          <!--end::Start Navbar Links-->
          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
              <!-- Authentication -->
              <a href="#" class="nav-link dropdown-toggle d-flex align-items-center justify-content-md-start justify-content-center text-center w-100" data-bs-toggle="dropdown">
                <img
                  src="{{ asset('adminlte/dist/assets/img/favicon-150x150.webp') }}"
                  class="user-image rounded-circle shadow me-2"
                  alt="User Image"
                />
                <span class="d d-md-inline">{{ Auth::user()->name }}</span>
              </a>
              
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img
                    src="{{ asset('adminlte/dist/assets/img/favicon-150x150.webp') }}"
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    Alexander Pierce - Web Developer
                    <small>Member since Nov. 2023</small>
                  </p>
                </li>
                <!--end::User Image-->
                <!--begin::Menu Body-->
                <li class="user-body">
                  <!--begin::Row-->
                  <div class="row">
                    <div class="col-4 text-center"><a href="#">Followers</a></div>
                    <div class="col-4 text-center"><a href="#">Sales</a></div>
                    <div class="col-4 text-center"><a href="#">Friends</a></div>
                  </div>
                  <!--end::Row-->
                </li>
                <!--end::Menu Body-->
                <!--begin::Menu Footer-->
                <li class="user-footer">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                  <a href="#" class="btn btn-default btn-flat float-end">Sign out</a>
                </li>
                <!--end::Menu Footer-->
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
            </ul>
            <form method="POST" action="{{ route('logout') }}" class="d-inline ms-4">
            @csrf
            <button type="submit" class="dropdown-item text-danger">
              <i class="fas fa-sign-out-alt me-2"></i> {{ __('Log Out') }}
            </button>
            </form>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar shadowstify-content-center align-items-center" data-bs-theme="dark" style="text-align: center !important;">
	  <!-- Particles background -->
	  <div id="particles-sidebar" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 0;"></div>

	  <!--begin::Sidebar Brand-->
	  <div class="sidebar-brand position-relative" style="z-index: 1;">
		<a href="" class="brand-link">
		  <img
			src="{{ asset('adminlte/dist/assets/img/favicon-150x150.webp') }}"
			alt="AndrianiLogo"
			class="brand-image opacity-75"
		  />
		  <span class="brand-text fw-light"><b>Andriani</b></span>
		</a>
	  </div>
	  <!--end::Sidebar Brand-->

	  <div class="sidebar-wrapper position-relative" style="z-index: 1;">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="menu"
              data-accordion="false"
            >
              <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('adesioni.index') }}" class="nav-link">
                  <i class="nav-icon bi bi-clipboard-fill"></i>
                  <p>Adesioni</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('eventi.index') }}" class="nav-link">
                    <i class="nav-icon bi bi-calendar-event-fill"></i>
                  <p>Eventi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('punti-vendita.index') }}" class="nav-link">
                    <i class="nav-icon bi bi-basket-fill"></i>
                  <p>Punti Vendita</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('materiali.index') }}" class="nav-link">
                    <i class="nav-icon bi bi-box-seam-fill"></i>
                  <p>Materiali</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('aree-di-competenza.index') }}" class="nav-link">
                  <i class="nav-icon bi bi-map-fill"></i>
                  <p>Aree di Competenza</p>
                </a>
              </li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->

        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                 <!--begin::Container-->
                 <div class="container-fluid">
                    @yield('content')
                </div>
                 <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
      
        <!--begin::Footer-->
        <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">Positive Food for Positive Life</div>
        <!--end::To the end-->
            <!--begin::Copyright-->
        <strong>
            Copyright &copy;2025&nbsp;
            Andriani SpA
        </strong>
        <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
   

   <!-- jQuery (richiesto da AdminLTE 3/4) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Bundle (include Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>

<script>
  tsParticles.load("particles-sidebar", {
	  fullScreen: { enable: false },
	  background: { color: "#004750" },
	  particles: {
		number: { value: 20 },
		shape: { type: "circle" },
		size: { value: 8, random: true },
		opacity: { value: 0.2, random: true },
		color: { value: "#71A850" },
		move: { enable: true, speed: 1, direction: "top", out_mode: "out" }
	  }
	});

</script>


@stack('scripts')
    
</body>
</html>