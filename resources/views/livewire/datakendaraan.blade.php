<?php

use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;
    public $search = '';
    public $perpage = 10;
    public $kendaraanId;

    public function setPerPage($perpage)
    {
        $this->perpage = $perpage;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'kendaraans' => \App\Models\Kendaraan::where('nomor_plat', 'like', '%' . $this->search . '%')->paginate($this->perpage)
        ];
    }

    public $nomor_plat, $tahun_pembuatan, $nama_pemilik, $alamat_pemilik, $no_telepon_pemilik, $status_kir;
    public $user_id, $name, $email, $role, $password;

    public function resetInput()
    {
        $this->nomor_plat = null;
        $this->tahun_pembuatan = null;
        $this->nama_pemilik = null;
        $this->alamat_pemilik = null;
        $this->no_telepon_pemilik = null;
        $this->status_kir = null;
        $this->kendaraanId = null;
        $this->email = null;
        $this->role = null;
        $this->password = null;
    }

    public function store()
    {
        $this->validate([
            'nomor_plat' => 'required',
            'tahun_pembuatan' => 'required',
            'nama_pemilik' => 'required',
            'alamat_pemilik' => 'required',
            'no_telepon_pemilik' => 'required',
            'status_kir' => 'required',
            'email' => 'required|email',
            // 'role' => 'required',
            'password' => 'required',
        ], [
            'nomor_plat.required' => 'Nomor Plat tidak boleh kosong',
            'tahun_pembuatan.required' => 'Tahun Pembuatan tidak boleh kosong',
            'nama_pemilik.required' => 'Nama Pemilik tidak boleh kosong',
            'alamat_pemilik.required' => 'Alamat Pemilik tidak boleh kosong',
            'no_telepon_pemilik.required' => 'No Telp Pemilik tidak boleh kosong',
            'status_kir.required' => 'Status KIR tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            // 'role.required' => 'Role tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
        ]);

        DB::transaction(function () {
            // create user pemilik
            $user = \App\Models\User::create([
            'name' => $this->nama_pemilik,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role' => 'pemilik',
            ]);

            \App\Models\Kendaraan::create([
            'nomor_plat' => $this->nomor_plat,
            'tahun_pembuatan' => $this->tahun_pembuatan,
            'nama_pemilik' => $this->nama_pemilik,
            'alamat_pemilik' => $this->alamat_pemilik,
            'no_telepon_pemilik' => $this->no_telepon_pemilik,
            'status_kir' => $this->status_kir,
            'user_id' => $user->id,
            ]);
        });

        $this->resetInput();
        $this->dispatch('tambahAlertToast');
    }

    public function edit($id)
    {
        $kendaraan = \App\Models\Kendaraan::find($id);
        $this->nomor_plat = $kendaraan->nomor_plat;
        $this->tahun_pembuatan = $kendaraan->tahun_pembuatan;
        $this->nama_pemilik = $kendaraan->nama_pemilik;
        $this->alamat_pemilik = $kendaraan->alamat_pemilik;
        $this->no_telepon_pemilik = $kendaraan->no_telepon_pemilik;
        $this->status_kir = $kendaraan->status_kir;

        $this->kendaraanId = $id;
    }

    // update
    public function update()
    {
        $this->validate([
            'nomor_plat' => 'required',
            'tahun_pembuatan' => 'required',
            'nama_pemilik' => 'required',
            'alamat_pemilik' => 'required',
            'no_telepon_pemilik' => 'required',
            'status_kir' => 'required',
        ]);

        \App\Models\Kendaraan::find($this->kendaraanId)->update([
            'nomor_plat' => $this->nomor_plat,
            'tahun_pembuatan' => $this->tahun_pembuatan,
            'nama_pemilik' => $this->nama_pemilik,
            'alamat_pemilik' => $this->alamat_pemilik,
            'no_telepon_pemilik' => $this->no_telepon_pemilik,
            'status_kir' => $this->status_kir,
        ]);

        $this->resetInput();
        $this->dispatch('tambahAlertToast');
    }

    public function close()
    {
        $this->resetInput();
    }

    public function delete($id)
    {
        $kendaraan = \App\Models\Kendaraan::find($id);
        $ujis = \App\Models\Uji::where('kendaraan_id', $id)->exists();

        if (!$ujis) {
            \App\Models\User::find($kendaraan->user_id)->delete();
            $kendaraan->delete();
            $this->dispatch('deleteAlertToast');
        } else {
            $this->dispatch('errorAlertToast', 'Data tidak dapat dihapus karena terkait dengan data ujis.');
        }
    }
}; ?>

<div>
    <div class="col-md-12">
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Kendaraan</div>
                    <div class="card-tools">
                        <a href="#" class="btn btn-info  btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addKendaraan">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>
                            Tambah Kendaraan
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table table-responsive">
                    <table class="table table-hover table-borderless">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nomor Plat</th>
                                <th>Tahun Pembuatan</th>
                                <th>Nama Pemilik</th>
                                <th>Alamat Pemilik</th>
                                <th>No Telp Pemilik</th>
                                <th>Status KIR</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($kendaraans as $kendaraan)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $kendaraan->nomor_plat }}</td>
                                <td>{{ $kendaraan->tahun_pembuatan }}</td>
                                <td>{{ $kendaraan->nama_pemilik }}</td>
                                <td>{{ $kendaraan->alamat_pemilik }}</td>
                                <td>{{ $kendaraan->no_telepon_pemilik }}</td>
                                <td> <span class="badge badge-{{ $kendaraan->status_kir == 'aktif' ? 'success' : 'danger' }}">{{ $kendaraan->status_kir }}</span> </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editKendaraan" wire:click="edit({{ $kendaraan->id }})">
                                        <i class="fa fa-edit fa-lg"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger"
                                        wire:click="delete({{ $kendaraan->id }})"
                                        wire:confirm="Apakah Anda Yakin akan menghapus data ini?">
                                        <i class="fa fa-trash fa-lg"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="justify-content-between">
                        {{ $kendaraans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore class="modal fade" id="addKendaraan" tabindex="-1" aria-labelledby="addKendaraanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKendaraanLabel">Tambah Kendaraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="store()">
                        @csrf
                        <div class="mb-3">
                            <label for="nomor_plat" class="form-label">Nomor Plat</label>
                            <input type="text" class="form-control @error('nomor_plat') is-invalid @enderror" id="nomor_plat" placeholder="Nomor Plat" wire:model="nomor_plat">
                            @error('nomor_plat') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan</label>
                            <input type="text" class="form-control @error('tahun_pembuatan') is-invalid @enderror" id="tahun_pembuatan" placeholder="Tahun Pembuatan" wire:model="tahun_pembuatan">
                            @error('tahun_pembuatan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nama_pemilik" class="form-label">Nama Pemilik</label>
                            <input type="text" class="form-control @error('nama_pemilik') is-invalid @enderror" id="nama_pemilik" placeholder="Nama Pemilik" wire:model="nama_pemilik">
                            @error('nama_pemilik') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="alamat_pemilik" class="form-label">Alamat Pemilik</label>
                            <input type="text" class="form-control @error('alamat_pemilik') is-invalid @enderror" id="alamat_pemilik" placeholder="Alamat Pemilik" wire:model="alamat_pemilik">
                            @error('alamat_pemilik') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="no_telepon_pemilik" class="form-label">No Telp Pemilik</label>
                            <input type="text" class="form-control @error('no_telepon_pemilik') is-invalid @enderror" id="no_telepon_pemilik" placeholder="No Telp Pemilik" wire:model="no_telepon_pemilik">
                            @error('no_telepon_pemilik') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status_kir" class="form-label">Status KIR</label>
                            <select class="form-select @error('status_kir') is-invalid @enderror" id="status_kir" wire:model="status_kir">
                                <option selected>Pilih...</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                            </select>
                            @error('status_kir') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- user -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email" wire:model="email">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" wire:model="password">
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore class="modal fade " id="editKendaraan" tabindex="-1" aria-labelledby="editKendaraanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKendaraanLabel">Edit Kendaraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="update()">
                        @csrf
                        <div class="mb-3">
                            <label for="nomor_plat" class="form-label ">Nomor Plat</label>
                            <input type="text" class="form-control @error('nomor_plat') is-invalid @enderror" id="nomor_plat" placeholder="Nomor Plat" wire:model="nomor_plat">
                            @error('nomor_plat') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tahun_pembuatan" class="form-label ">Tahun Pembuatan</label>
                            <input type="text" class="form-control @error('tahun_pembuatan') is-invalid @enderror" id="tahun_pembuatan" placeholder="Tahun Pembuatan" wire:model="tahun_pembuatan">
                            @error('tahun_pembuatan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nama_pemilik" class="form-label ">Nama Pemilik</label>
                            <input type="text" class="form-control @error('nama_pemilik') is-invalid @enderror" id="nama_pemilik" placeholder="Nama Pemilik" wire:model="nama_pemilik">
                            @error('nama_pemilik') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="alamat_pemilik" class="form-label ">Alamat Pemilik</label>
                            <input type="text" class="form-control @error('alamat_pemilik') is-invalid @enderror" id="alamat_pemilik" placeholder="Alamat Pemilik" wire:model="alamat_pemilik">
                            @error('alamat_pemilik') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="no_telepon_pemilik" class="form-label ">No Telp Pemilik</label>
                            <input type="text" class="form-control @error('no_telepon_pemilik') is-invalid @enderror" id="no_telepon_pemilik" placeholder="No Telp Pemilik" wire:model="no_telepon_pemilik">
                            @error('no_telepon_pemilik') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status_kir" class="form-label ">Status KIR</label>
                            <select class="form-select @error('status_kir') is-invalid @enderror" id="status_kir" wire:model="status_kir">
                                <option selected>Pilih...</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                            </select>
                            @error('status_kir') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <livewire:_alert />
</div>