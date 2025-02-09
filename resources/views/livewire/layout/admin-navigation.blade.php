<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <div class="sidebar" data-background-color="white">
        <div class="sidebar-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
                <a href="#" class="logo text-white">
                    SI - UJI KIR
                    <!-- <img src="{{ url('/') }}/assets/img/logo/android-chrome-192x192.png" alt="kolaka timur" height="30" /> -->
                </a>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="gg-menu-right"></i>
                    </button>
                    <button class="btn btn-toggle sidenav-toggler">
                        <i class="gg-menu-left"></i>
                    </button>
                </div>
                <button class="topbar-toggler more">
                    <i class="gg-more-vertical-alt"></i>
                </button>
            </div>
            <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
                <ul class="nav nav-secondary">
                    <li class="nav-item {{ Route::is('home') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('home') }}" >
                            <i class="fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Masters</h4>
                    </li>
                    <li class="nav-item {{ Route::is('kendaraan') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('kendaraan') }}" wire:navigate>
                            <i class="fas fa-car"></i>
                            <p>Data Kendaraan</p>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('parameter') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('parameter') }}" wire:navigate>
                            <i class="fas fa-list"></i>
                            <p>Parameter Uji</p>
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('aturan') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('aturan') }}" wire:navigate>
                            <i class="fas fa-book"></i>
                            <p>Aturan</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Proses</h4>
                    </li>
                    <li class="nav-item {{ Route::is('uji') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('uji') }}" wire:navigate>
                            <i class="fas fa-flask"></i>
                            <p>Uji KIR</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Output</h4>
                    </li>
                    <li class="nav-item {{ Route::is('hasiluji') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('hasiluji') }}" wire:navigate>
                            <i class="fas fa-file-alt"></i>
                            <p>Hasil Uji</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Settings</h4>
                    </li>
                    @if(auth()->user()->role == 'admin')
                    <li class="nav-item {{ Route::is('admin.manajemen-user') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('admin.manajemen-user') }}" wire:navigate>
                            <i class="fas fa-users"></i>
                            <p>Manajemen User</p>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item {{ Route::is('profil') ? 'active text-info' : '' }}">
                        <a class="nav-link" href="{{ route('profil') }}" wire:navigate>
                            <i class="fas fa-user"></i>
                            <p>Profil</p>
                        </a>
                    </li>


                    <br>
                    <div class="px-4">
                        <li class="nav-item" style="padding: 0px !important;">
                            <a href="#" wire:click="logout" class=" text-center btn btn-sm btn-danger w-100 btn-block d-flex justify-content-center align-items-center" style="padding: 0px !important;">
                                <i class="fas fa-sign-out-alt fa-lg m-2 p-1"></i> &nbsp;
                                <p style="padding: 0px !important; margin: 5px !important">Keluar</p>
                            </a>
                        </li>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</div>
