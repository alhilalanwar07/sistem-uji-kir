<?php

use App\Models\Aturan;
use App\Models\Parameter;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {
    public $selectedParameter;
    public $parameters;
    public $aturan;
    public $namaAturan;
    public $cfValue;
    public $editMode = false;
    public $aturanId;

    public function mount()
    {
        $this->parameters = Parameter::all();
        $this->selectedParameter = $this->parameters->last()->id ?? null;
        $this->aturan = Aturan::where('parameter_id', $this->selectedParameter)->get();
    }

    public function updatedSelectedParameter($value)
    {
        $this->selectedParameter = $value;
        $this->aturan = Aturan::where('parameter_id', $value)->get();
    }

    public function with(): array
    {
        return [
            'parameters' => Parameter::all(),
            'aturan' => Aturan::where('parameter_id', $this->selectedParameter)->get()
        ];
    }

    public function tambahAturan()
    {
        $this->resetInputFields();
        $this->editMode = false;
    }

    public function editAturan($id)
    {
        $aturan = Aturan::findOrFail($id);
        $this->aturanId = $id;
        $this->namaAturan = $aturan->nama_aturan;
        $this->cfValue = $aturan->cf_value;
        $this->editMode = true;
    }

    public function simpanAturan()
    {
        $this->validate([
            'namaAturan' => 'required|string|max:255',
            'cfValue' => 'required|numeric|min:0|max:1',
        ]);

        if ($this->editMode) {
            $aturan = Aturan::findOrFail($this->aturanId);
            $aturan->update([
                'nama_aturan' => $this->namaAturan,
                'cf_value' => $this->cfValue,
            ]);
        } else {
            Aturan::create([
                'parameter_id' => $this->selectedParameter,
                'nama_aturan' => $this->namaAturan,
                'cf_value' => $this->cfValue,
            ]);
        }

        $this->aturan = Aturan::where('parameter_id', $this->selectedParameter)->get();
        $this->resetInputFields();
        $this->dispatch('tambahAlertToast');
    }

    public function hapusAturan($id)
    {
        // Hapus semua detail_ujis yang terkait dengan aturan yang akan dihapus
        DB::table('detail_ujis')->where('aturan_id', $id)->delete();

        // Hapus aturan
        Aturan::findOrFail($id)->delete();
        $this->aturan = Aturan::where('parameter_id', $this->selectedParameter)->get();

        $this->dispatch('deleteAlertToast');
    }

    private function resetInputFields()
    {
        $this->namaAturan = '';
        $this->cfValue = '';
    }
}; ?>

<div>
    <div class="card shadow-none">
        <div class="card-header">
            Pilih Parameter
        </div>
        <div class="card-body ">
            <label for="parameterSelect" class="form-label">Pilih Parameter</label>
            <select id="parameterSelect" wire:model.live="selectedParameter" class="form-select">
                <!-- <option value="">Pilih Parameter</option> -->
                @foreach($parameters as $parameter)
                <option value="{{ $parameter->id }}">{{ strtoupper($parameter->nama) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if($selectedParameter)
    <div class="card mt-4 shadow-none">
        <div class="card-header">
            <div class="card-head-row">
                <div class="card-title">Aturan</div>
                <div class="card-tools">
                    <button wire:click="tambahAturan" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <span class="btn-label">
                            <i class="fa fa-plus"></i>
                        </span>
                    Tambah
                </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="mt-2 table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nama Aturan</th>
                            <th scope="col">CF Value</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aturan as $item)
                        <tr>
                            <td>{{ $item->nama_aturan }}</td>
                            <td>{{ $item->cf_value }}</td>
                            <td>
                                <button wire:click="editAturan({{ $item->id }})" class="btn btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalTambah">Edit</button>
                                <button wire:click="hapusAturan({{ $item->id }})" class="btn btn-link text-danger text-decoration-none ms-2">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- modal -->
    <div class="modal fade" id="modalTambah" tabindex="-1" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editMode ? 'Edit Aturan' : 'Tambah Aturan' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="simpanAturan">
                        <div class="mb-3">
                            <label for="namaAturan" class="form-label">Nama Aturan</label>
                            <input type="text" wire:model="namaAturan" class="form-control" id="namaAturan">
                        </div>
                        <div class="mb-3">
                            <label for="cfValue" class="form-label">CF Value</label>
                            <input type="number" wire:model="cfValue" class="form-control" id="cfValue" min="0" max="1" step="0.01">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- _alert -->
    <livewire:_alert />
</div>