<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;
    public $search = '';
    public $perpage = 10;
    public $ujiId;
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate = now()->endOfMonth()->toDateString();
    }

    public function with(): array
    {
        return [
            'ujis' => \App\Models\Uji::with('kendaraan')
                ->whereHas('kendaraan', function ($query) {
                    $query->where('nomor_plat', 'like', '%' . $this->search . '%');
                })
                ->whereBetween('created_at', [$this->startDate, $this->endDate])
                ->paginate($this->perpage),
        ];
    }

    public function setPerPage($perpage)
    {
        $this->perpage = $perpage;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public $kendaraan_id, $hasil_cf, $status;

    public function resetInput()
    {
        $this->kendaraan_id = null;
        $this->hasil_cf = null;
        $this->status = null;
        $this->ujiId = null;
    }
}; ?>

<div>
    <div class="col-md-12">
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Hasil Uji</div>
                    <div class="card-tools d-flex">
                        <input type="date" wire:model="startDate" class="form-control me-2" placeholder="Tanggal Mulai">
                        <div class="d-flex flex-column justify-content-center me-2">
                            <span>s/d</span>
                        </div>
                        <input type="date" wire:model="endDate" class="form-control me-2" placeholder="Tanggal Selesai">
                        <a href="#" class="btn btn-info btn-sm me-2">
                            <span class="btn-label">
                                <i class="fa fa-print"></i>
                            </span>
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
                                <th>Tanggal UJi</th>
                                <th>Kendaraan</th>
                                <th>Hasil CF</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($ujis as $uji)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $uji->created_at->format('d-m-Y') }}</td>
                                <td>{{ $uji->kendaraan->nomor_plat }}</td>
                                <td>{{ $uji->hasil_cf }}</td>
                                <td> <span class="badge badge-{{ $uji->status == 'Lulus' ? 'success' : 'danger' }}">{{ $uji->status }}</span> </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary">
                                        <i class="fa fa-print fa-lg"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="justify-content-between">
                        {{ $ujis->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <livewire:_alert />
</div>