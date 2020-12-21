<table>
    <thead>
        <tr>
            <th colspan="5" style="text-align:center;">
                <h4>Komisi Periode : {{ DATE ('d-m-Y', strtotime($periode['starts'])) }} sampai
                    {{ DATE ('d-m-Y', strtotime($periode['ends'])) }}</h4>
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align:left;">
                <h4>Cabang : {{ $session_cabang }}</h4>
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Cabang</th>
            <th>Percent Komisi</th>
            <th>Total Layanan</th>
            <th>Total Komisi</th>
        </tr>
    </thead>
    <tbody>
        <?php $totalGrand = 0; ?>
        @foreach($export_komisi as $no => $pegawai)
        <tr>
            <td>{{ $no + 1 }}</td>
            <td>{{ $pegawai->nama }}</td>
            <td>{{ $pegawai->cabang }}</td>
            <td>{{ $pegawai->upah }}</td>
            <td>{{ $pegawai->total_layanan }}</td>
            <td style="text-align:right;">{{ rupiahFormatExcel( $pegawai->total_komisi ) }}</td>
        </tr>
        <?php $totalGrand += $pegawai->total_komisi; ?>
        @endforeach
        <tr>
            <td colspan="5" style="text-align:center;">Grand Total</td>
            <td style="text-align:right;">{{ rupiahFormatExcel( $totalGrand ) }}</td>
        </tr>
    </tbody>
</table>
