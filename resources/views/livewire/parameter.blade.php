<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Parameter;
use Illuminate\Validation\Rule;

new class extends Component {
    use WithPagination;

    public $nama;
    public $deskripsi;
    public $parameterId;
    public $editMode = false;
    public $showModal = false;
    public $showDeleteModal = false;
    public $successMessage;
    public $search = '';
    public $perpage = 10;

    protected $rules = [
        'nama' => 'required|string',
        'deskripsi' => 'required|string',
    ];

    public function resetFields()
    {
        $this->reset(['nama', 'deskripsi', 'parameterId', 'editMode']);
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function saveParameter()
    {
        $this->validate();
        Parameter::create([
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
        ]);
        $this->showModal = false;
        $this->resetFields();
        
        // gunakan _alert
        $this->dispatch('tambahAlertToast');
    }

    public function editParameter($id)
    {
        $parameter = Parameter::findOrFail($id);
        $this->parameterId = $id;
        $this->nama = $parameter->nama;
        $this->deskripsi = $parameter->deskripsi;
        $this->editMode = true;
        $this->openModal();
    }

    public function updateParameter()
    {
        $this->validate();
        $parameter = Parameter::findOrFail($this->parameterId);
        $parameter->update([
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
        ]);
        $this->showModal = false;
        $this->resetFields();
        
        // gunakan _alert
        $this->dispatch('updateAlertToast');
    }

    public function confirmDelete($id)
    {
        $this->parameterId = $id;
    }

    public function deleteParameter()
    {
        $parameter = Parameter::findOrFail($this->parameterId);
        
        if ($parameter->aturans()->count() > 0) {
            $this->dispatch('errorAlertToast', 'Parameter cannot be deleted because it is associated with aturan.');
            return;
        }
        
        $parameter->delete();
        
        // gunakan _alert
        $this->dispatch('deleteAlertToast');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setPerPage($perpage)
    {
        $this->perpage = $perpage;
    }

    // tutup modal
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetFields();
    }

    public function with(): array
    {
        return [
            'parameters' => Parameter::where('nama', 'like', '%' . $this->search . '%')->paginate($this->perpage),
        ];
    }
}; ?>


<div>
    <div class="col-md-12">
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Parameter</div>
                    <div class="card-tools">
                        <a href="#" wire:click="openModal" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#parameterModal">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>
                            Tambah Parameter
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Parameter</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parameters as $parameter)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucfirst($parameter->nama) }}</td>
                                <td>{{ Str::limit($parameter->deskripsi, 50) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" wire:click="editParameter({{ $parameter->id }})" data-bs-toggle="modal" data-bs-target="#parameterModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $parameter->id }})" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="justify-content-between">
                        {{ $parameters->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade" id="parameterModal" tabindex="-1" wire:ignore.self data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $editMode ? 'Edit Parameter' : 'Tambah Parameter' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nama Parameter</label>
                            <input type="text" class="form-control" wire:model="nama">
                            @error('nama')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" rows="3" wire:model="deskripsi"></textarea>
                            @error('deskripsi')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">
                        Batal 
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="{{ $editMode ? 'updateParameter' : 'saveParameter' }}">
                        {{ $editMode ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" wire:ignore.self data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus parameter ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteParameter">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    <!-- alert -->
    <livewire:_alert />
</div>
