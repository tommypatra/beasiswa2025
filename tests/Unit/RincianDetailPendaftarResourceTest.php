<?php

namespace Tests\Unit;

use App\Http\Resources\RincianDetailPendaftarResource;
use App\Models\Mahasiswa;
use App\Models\Pendaftar;
use App\Models\ReferensiPilihan;
use App\Models\Rumah;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class RincianDetailPendaftarResourceTest extends TestCase
{
    public function test_it_maps_rumah_options_to_the_expected_detail_fields(): void
    {
        $kepemilikanRumah = new ReferensiPilihan(['id' => 1, 'nama' => 'Milik sendiri']);
        $listrik = new ReferensiPilihan(['id' => 2, 'nama' => 'Bayar']);
        $mck = new ReferensiPilihan(['id' => 3, 'nama' => 'MCK']);
        $sumberAir = new ReferensiPilihan(['id' => 4, 'nama' => 'PDAM']);
        $sumberListrik = new ReferensiPilihan(['id' => 5, 'nama' => 'PLN']);

        $rumah = new Rumah(['id' => 10]);
        $rumah->setRelation('pilihanKepemilikanRumah', $kepemilikanRumah);
        $rumah->setRelation('pilihanListrik', $listrik);
        $rumah->setRelation('pilihanMck', $mck);
        $rumah->setRelation('pilihanSumberAir', $sumberAir);
        $rumah->setRelation('pilihanSumberListrik', $sumberListrik);

        $user = new User(['id' => 20]);
        $user->setRelation('rumah', $rumah);

        $mahasiswa = new Mahasiswa(['id' => 30]);
        $mahasiswa->setRelation('user', $user);

        $pendaftar = new Pendaftar(['id' => 40]);
        $pendaftar->setRelation('mahasiswa', $mahasiswa);

        $data = (new RincianDetailPendaftarResource($pendaftar))->toArray(Request::create('/'));

        $this->assertSame(1, $data['rumah']['status']['id']);
        $this->assertSame('Milik sendiri', $data['rumah']['status']['nama']);
        $this->assertSame(2, $data['rumah']['bayar_listrik']['id']);
        $this->assertSame('Bayar', $data['rumah']['bayar_listrik']['nama']);
        $this->assertSame(3, $data['rumah']['mck']['id']);
        $this->assertSame('MCK', $data['rumah']['mck']['nama']);
        $this->assertSame(4, $data['rumah']['sumber_air']['id']);
        $this->assertSame('PDAM', $data['rumah']['sumber_air']['nama']);
        $this->assertSame(5, $data['rumah']['sumber_listrik']['id']);
        $this->assertSame('PLN', $data['rumah']['sumber_listrik']['nama']);
    }
}
