<?php

use App\Models\Aturan;
use App\Models\DetailUji;
use App\Models\Kendaraan;
use App\Models\Parameter;
use App\Models\Uji;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component {
    public $kendaraan_id;
    public $availableParameters;
    public $parameters = [];
    public $selectedParameters = [];


    public function with(): array
    {
        return [
            'kendaraans' => \App\Models\Kendaraan::where('status_kir', '!=', 'aktif')->get(),
            'parameter' => Parameter::with('aturans')->get()
        ];
    }

    protected function rules()
    {
        return [
            'kendaraan_id' => 'required',
            'parameters.*.id' => 'required|exists:parameter_uji,parameter_id',
            'parameters.*.hasil' => 'required|in:Lulus,Tidak Lulus',
            'parameters.*.cf_pengguna' => 'required|numeric|min:0|max:1',
        ];
    }

    protected function messages()
    {
        return [
            'kendaraan_id.required' => 'Kendaraan harus dipilih',
            'parameters.*.id.required' => 'Parameter harus dipilih',
            'parameters.*.id.exists' => 'Parameter tidak valid',
            'parameters.*.hasil.required' => 'Hasil pengujian harus dipilih',
            'parameters.*.hasil.in' => 'Hasil pengujian tidak valid',
            'parameters.*.cf_pengguna.required' => 'CF Pengguna harus diisi',
            'parameters.*.cf_pengguna.numeric' => 'CF Pengguna harus berupa angka',
            'parameters.*.cf_pengguna.min' => 'CF Pengguna tidak boleh kurang dari 0',
            'parameters.*.cf_pengguna.max' => 'CF Pengguna tidak boleh lebih dari 1',
        ];
    }

    public function resetInput()
    {
        $this->kendaraan_id = null;
        $this->parameters = [];
        $this->selectedParameters = [];
    }

    public function store()
    {
        $this->validate();
        DB::transaction(function () {
            $CFcombine = 0;
            $uji = Uji::create([
            'kendaraan_id' => $this->kendaraan_id,
            'hasil_cf' => 0,
            'status' => 'Menunggu'
            ]);

            foreach ($this->selectedParameters as $parameter_id => $aturan_id) {
            $aturan = Aturan::find($aturan_id);

            DetailUji::create([
                'uji_id' => $uji->id,
                'parameter_id' => $parameter_id,
                'aturan_id' => $aturan_id,
                'cf_value' => $aturan->cf_value,
            ]);

            if ($CFcombine == 0) {
                $CFcombine = $aturan->cf_value;
            } else {
                $CFcombine = $CFcombine + ($aturan->cf_value * (1 - $CFcombine));
            }
            }

            $uji->hasil_cf = $CFcombine * 100;
            $uji->status = $CFcombine >= 0.7 ? 'Lulus' : 'Tidak Lulus';
            $uji->save();

            if ($uji->status == 'Lulus') {
                $kendaraan = Kendaraan::find($this->kendaraan_id);
                $kendaraan->status_kir = 'aktif';
                $kendaraan->save();
            }
        });

        $this->reset();

        $this->dispatch('tambahAlertToast', 'Pengujian berhasil dilakukan');
        // redirect ke halaman hasil uji
        return redirect()->route('hasiluji');
    }
}; ?>

<div>
    <div class="container">
        <h2 class="mb-4">Form Pengujian Kendaraan</h2>

        <form wire:submit.prevent="store" class="bg-white p-4 rounded shadow-sm">
            <div class="mb-4">
                <label for="kendaraan" class="form-label font-weight-bold">Pilih Kendaraan:</label>
                <select wire:model="kendaraan_id" id="kendaraan" class="form-control custom-select">
                    <option value="">-- Pilih Kendaraan --</option>
                    @foreach($kendaraans as $kendaraan)
                    <option value="{{ $kendaraan->id }}">{{ $kendaraan->nomor_plat }} - {{ $kendaraan->nama_pemilik }}</option>
                    @endforeach
                </select>
                @error('kendaraan_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <h4 class="mb-3">Pilih Parameter Pengujian:</h4>
            @foreach($parameter as $parameter)
            <div class="mb-3 p-3 border rounded">
                <strong class="d-block mb-2">{{ strtoupper($parameter->nama) }}</strong>
                <span class="d-block mb-2 fst-italic badge bg-warning">{{ $parameter->deskripsi }}</span>
                <div class="form-check">
                    @foreach($parameter->aturans as $aturan)
                    <label class="form-check-label me-3">
                        <input type="radio"
                            class="form-check-input"
                            wire:model="selectedParameters.{{ $parameter->id }}"
                            value="{{ $aturan->id }}">
                        {{ $aturan->nama_aturan }} (CF: {{ $aturan->cf_value }})
                    </label>
                    <br>
                    @endforeach
                </div>
                @error("selectedParameters.{$parameter->id}")
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            @endforeach

            <button type="submit" class="btn btn-primary mt-4 w-100">Lakukan Pengujian</button>
        </form>
    </div>

    <livewire:_alert />
</div>