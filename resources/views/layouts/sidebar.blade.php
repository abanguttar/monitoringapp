<div class="drawer ml-5 pt-3">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content">
        <!-- Page content here -->
        <label for="my-drawer" class="btn btn-primary drawer-button btn-sm"><i class="fa-solid fa-bars"></i></label>
    </div>
    <div class="drawer-side" style="z-index: 1000">
        <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
        <ul class="menu bg-base-200 text-base-content min-h-full w-80 p-4">
            <ul class="mt-3">
                <h1 class="text-2xl text-center">Aplikasi Monitoring</h1>
            </ul>
            <!-- Sidebar content here -->
            <li class="mt-5">
                <a href="/AplikasiMonitoring/dashboard" class="btn btn-light p-2" id="nav-dashboard">Dashboard</a>
            </li>
            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin')
                <li>
                    <a href="/AplikasiMonitoring/master-data-peserta" class="btn btn-light p-2"
                        id="nav-master-data-peserta">Master Data
                        Peserta</a>
                </li>
                <li>
                    <a href="/AplikasiMonitoring/list-peserta" class="btn btn-light p-2" id="nav-list-peserta">List
                        Peserta</a>
                </li>
                <li>
                    <a href="/AplikasiMonitoring/list-pembayaran" class="btn btn-light p-2"
                        id="nav-list-pembayaran">List Pembayaran</a>
                </li>
                <li>
                    <a href="/AplikasiMonitoring/list-komisi-mitra" class="btn btn-light p-2"
                        id="nav-list-komisi-mitra">List Komisi
                        Mitra</a>
                </li>
                <li>
                    <a href="/AplikasiMonitoring/grafik-transaksi" class="btn btn-light p-2"
                        id="nav-grafik-transaksi">Grafik Transaksi</a>
                </li>
                <li>
                    <a href="/AplikasiMonitoring/list-mitra" class="btn btn-light p-2" id="nav-list-mitra">List
                        Mitra</a>
                </li>
                <li>
                    <a href="/AplikasiMonitoring/list-digital-platform" class="btn btn-light p-2"
                        id="nav-list-digital-platform">List
                        Digital
                        Platform</a>
                </li>
                <li>
                    <a href="/AplikasiMonitoring/list-data-kelas" class="btn btn-light p-2"
                        id="nav-list-data-kelas">List Data
                        Kelas</a>
                </li>
                <li>
                    <a href="/AplikasiMonitoring/master-data-trainer" class="btn btn-light p-2"
                        id="nav-master-data-trainer">
                        Master Data Trainer</a>
                </li>

                <li class="flex justify-center mx-auto mt-5">
                    <h5 class="text-center font-semibold text-md" >KOMISI MITRA</h5>
                </li>
                <li>
                    {{-- <div className="btn btn-light --collapse bg-base-200">
                        <input type="radio" name="my-accordion-1" defaultChecked />
                        <div className="collapse-title text-xl font-medium">Komisi Trainer
                        </div>
                        <div className="collapse-content">
                            <a href="/AplikasiMonitoring/komisi-trainer/kelas-jadwal" class="btn btn-light p-2">List
                                Kelas & Jadwal</a>
                        </div>
                    </div> --}}
                    <a href="/AplikasiMonitoring/komisi-trainer/kelas-jadwal" class="btn btn-light p-2"
                    id="nav-komisi-trainer-kelas-jadwal"
                    >List
                        Kelas & Jadwal</a>
                </li>
            @else
                <li>
                    <a href="/AplikasiMonitoring/list-peserta/marketing" class="btn btn-light p-2"
                        id="nav-list-peserta">List Peserta</a>
                </li>
                <li>
                    <a href="/AplikasiMonitoring/list-komisi-mitra" class="btn btn-light p-2"
                        id="nav-list-komisi-mitra">List Komisi
                        Mitra</a>
                </li>
                <li>
                    <a href="/AplikasiMonitoring/list-mitra" class="btn btn-light p-2" id="nav-list-mitra">List
                        Mitra</a>
                </li>
            @endif
            @if (Auth::user()->role === 'superadmin')
                <li>
                    <a href="/AplikasiMonitoring/list-user" class="btn btn-light p-2" id="nav-list-user">List User</a>
                </li>
            @endif
            <li>
                <button class="btn btn-light p-2" id="btn-logout">Logout</button>
            </li>

        </ul>
    </div>
</div>
