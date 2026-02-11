<div class="width-sm card my-4">
    <div class="card-body">
        <div class="text-center">
            <img src="{{ url("/assets/installer/logo.svg") }}" class="install-logo" alt="Logo">
        </div>
        <h2 class="text-center position-relative pb-3 my-3">Installation</h2>
        <ul class="nav nav-pills mb-3 d-none pe-none" id="installation-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-intro-tab" data-bs-toggle="pill" data-bs-target="#pills-intro"
                    type="button" role="tab" aria-controls="pills-intro" aria-selected="true">intro</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-database-tab" data-bs-toggle="pill" data-bs-target="#pills-database"
                    type="button" role="tab" aria-controls="pills-database" aria-selected="false">database</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-settings-tab" data-bs-toggle="pill" data-bs-target="#pills-settings"
                    type="button" role="tab" aria-controls="pills-settings" aria-selected="false">settings</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-installation-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-installation" type="button" role="tab" aria-controls="pills-installation"
                    aria-selected="false">installation</button>
            </li>
        </ul>
        <div class="tab-content" id="installation-tabsContent">
            <div class="tab-pane fade show active" id="pills-intro" role="tabpanel" aria-labelledby="pills-intro-tab" tabindex="0">
                <div class="text-center">
                    <button type="button" class="btn btn-color-1" data-tab="pills-database">
                        Start Installation
                    </button>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-database" role="tabpanel" aria-labelledby="pills-database-tab" tabindex="0">
                <form action="{{ url('install/database') }}" method="post" id="database-check">
                    <div class="checker-card p-4 mb-3">
                        <p class="mb-2">
                            <span class="step-title">Database Connection</span>
                            <span class="step-ok d-none">
                                <i class="fa-solid fa-check fa-fw fa-lg"></i>
                            </span>
                            <span class="loader spinner-grow spinner-grow-sm ms-2 d-none" role="status"></span>
                        </p>
                        <p class="checker-content text-md m-0">Enter the database information into the form and start
                            the installation.</p>
                        <div class="errors mt-2 d-none">
                        </div>
                        <div class="form-floating my-3">
                            <select class="form-select install-select" id="floatingSelect" name="type" aria-label="Database Type">
                                <option value="mysql" selected>MySQL</option>
                                <option value="mariadb">MariaDB</option>
                                <option value="pgsql">PostgreSQL</option>
                                <option value="sqlsrv">SQL Server</option>
                            </select>
                            <label for="floatingSelect">Database Type</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control install-input" id="hostname" name="hostname"
                                placeholder="Database Hostname">
                            <label for="hostname">Database Hostname</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control install-input" id="database" name="database"
                                placeholder="Database Name">
                            <label for="database">Database Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control install-input" id="user" name="username" placeholder="Database User">
                            <label for="user">Database User</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control install-input" id="password" name="password"
                                placeholder="Database Password">
                            <label for="password">Database Password</label>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-color-1">
                            Connect to Database
                        </button>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-color-1 d-none" data-tab="pills-settings">
                            Continue
                        </button>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="pills-settings" role="tabpanel" aria-labelledby="pills-settings-tab" tabindex="0">
                <form action="{{ url('install/settings') }}" method="post" id="settings">
                    <div class="checker-card p-4 mb-3">
                        <p class="mb-2">
                            <span class="step-title">Settings</span>
                            <span class="step-ok d-none">
                                <i class="fa-solid fa-check fa-fw fa-lg"></i>
                            </span>
                            <span class="loader spinner-grow spinner-grow-sm ms-2 d-none" role="status"></span>
                        </p>
                        <p class="checker-content text-md m-0">Enter the website information and admin account into the form.</p>
                        <div class="errors mt-2 d-none"></div>
                        <div class="form-floating my-3">
                            <input type="text" class="form-control install-input" id="website-url" name="website-url"
                                placeholder="Website Url">
                            <label for="website-url">Website Url</label>
                        </div>
                        <div class="form-floating my-3">
                            <input type="text" class="form-control install-input" id="admin-email" name="admin-email"
                                placeholder="Admin Email">
                            <label for="admin-email">Admin Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control install-input" id="admin-password" name="admin-password"
                                placeholder="Admin Password">
                            <label for="admin-password">Admin password</label>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-color-1">
                            Create Admin
                        </button>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-color-1 d-none" data-tab="pills-installation">
                            Continue
                        </button>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="pills-installation" role="tabpanel" aria-labelledby="pills-installation-tab" tabindex="0">
                <form action="{{ url('install/finish') }}" method="post" id="finish">
                    <div class="checker-card p-4 mb-3" data-step="requirements">
                        <p class="mb-2">
                            <span>Step 1</span>
                            <span class="loader spinner-grow spinner-grow-sm ms-2 d-none" role="status"></span>
                        </p>
                        <p class="checker-content text-md m-0">Checking required plugins</p>
                        <div class="errors mt-2 d-none"></div>
                    </div>
                    <div class="checker-card p-4 mb-3" data-step="database">
                        <p class="mb-2">
                            <span>Step 2</span>
                            <span class="loader spinner-grow spinner-grow-sm ms-2 d-none" role="status"></span>
                        </p>
                        <p class="checker-content text-md m-0">Creating database tables</p>
                        <div class="errors mt-2 d-none"></div>
                    </div>
                    <div class="checker-card p-4 mb-3" data-step="finish">
                        <p class="mb-2">
                            <span>Step 3</span>
                            <span class="loader spinner-grow spinner-grow-sm ms-2 d-none" role="status"></span>
                        </p>
                        <p class="checker-content text-md m-0">Final adjustments</p>
                        <div class="errors mt-2 d-none"></div>
                    </div>
                    <p class="finish-text text-center text-md mb-3"></p>
                    <div class="text-center">
                        <button type="submit" class="install-portus btn btn-color-1">Install Portus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>