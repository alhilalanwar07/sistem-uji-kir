<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;
    public $search = '';
    public $perpage = 10;

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
            'kriterias' => \App\Models\Kriteria::where('nama', 'like', '%' . $this->search . '%')->paginate($this->perpage),
        ];
    }


    public $nama, $bobot, $deskripsi;

    public function resetInput()
    {
        $this->nama = null;
        $this->bobot = null;
        $this->deskripsi = null;
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required',
            'bobot' => 'required|numeric',
            'deskripsi' => 'required',
        ], [
            'nama.required' => 'Nama tidak boleh kosong',
            'bobot.required' => 'Bobot tidak boleh kosong',
            'bobot.numeric' => 'Bobot harus berupa angka',
            'deskripsi.required' => 'Deskripsi tidak boleh kosong',
        ]);

        \App\Models\Kriteria::create([
            'nama' => $this->nama,
            'bobot' => $this->bobot,
            'deskripsi' => $this->deskripsi,
        ]);

        $this->resetInput();
        $this->dispatch('tambahAlertToast');
    }

    public function edit($id)
    {
        $kriteria = \App\Models\Kriteria::find($id);
        $this->nama = $kriteria->nama;
        $this->bobot = $kriteria->bobot;
        $this->deskripsi = $kriteria->deskripsi;
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required',
            'bobot' => 'required|numeric',
            'deskripsi' => 'required',
        ]);

        \App\Models\Kriteria::where('id', $this->id)->update([
            'nama' => $this->nama,
            'bobot' => $this->bobot,
            'deskripsi' => $this->deskripsi,
        ]);

        $this->resetInput();
        $this->dispatch('updateAlertToast');
    }

    public function delete($id)
    {
        \App\Models\Kriteria::find($id)->delete();
        $this->dispatch('deleteAlertToast');
    }
}; ?>

<div>
    <div class="col-md-12">
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Kriteria</div>
                    <div class="card-tools">
                        <a href="#" class="btn btn-info  btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addKriteria">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>
                            Tambah Kriteria
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
                                <th>Nama</th>
                                <th>Bobot</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($kriterias as $kriteria)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $kriteria->nama }}</td>
                                <td>{{ $kriteria->bobot }}</td>
                                <td>{{ $kriteria->deskripsi }}</td>
                                <td>
                                    <a href="#" wire:click="edit({{ $kriteria->id }})" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editKriteria">
                                        <i class="fa fa-edit me-1"></i> Edit
                                    </a>
                                    <a href="#" wire:click="delete({{ $kriteria->id }})" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash me-1"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $kriterias->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- add kriteria -->
    <div wire:ignore class="modal fade" id="addKriteria" tabindex="-1" aria-labelledby="addKriteriaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKriteriaLabel">Tambah Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="store">
                        <div class="form-group mb-3">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" wire:model="nama">
                            @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="bobot">Bobot</label>
                            <input type="text" class="form-control" id="bobot" wire:model="bobot">
                            @error('bobot') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" wire:model="deskripsi"></textarea>
                            @error('deskripsi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- edit kriteria -->
    <div wire:ignore class="modal fade" id="editKriteria" tabindex="-1" aria-labelledby="editKriteriaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKriteriaLabel">Edit Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="update">
                        <div class="form-group mb-3">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" wire:model="nama">
                            @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="bobot">Bobot</label>
                            <input type="text" class="form-control" id="bobot" wire:model="bobot">
                            @error('bobot') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" wire:model="deskripsi"></textarea>
                            @error('deskripsi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <livewire:_alert />
</div>