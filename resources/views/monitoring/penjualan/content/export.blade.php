<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align:center;">
                <h4>Penjualan Periode : {{ DATE ('d-m-Y', strtotime($periode['starts'])) }} sampai
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
            <th>Tanggal Bayar</th>
            <th>Nama</th>
            <th>Tagihan</th>
            <th>Transaksi</th>
            <th>Cara Bayar</th>
        </tr>
    </thead>
    <tbody>
        <?php $totalGrand = 0; ?>
        @foreach($export_sales as $no => $penjualan)
        <tr>
            <td>{{ $no + 1 }}</td>
            <td>{{ $penjualan->tanggal }}</td>
            <td>{{ $penjualan->nama }}</td>
            <td style="text-align:right;">{{ rupiahFormatExcel( $penjualan->tagihan ) }}</td>
            <td>{{ $penjualan->transaksi }}</td>
            <td>{{ $penjualan->cara_bayar }}</td>
        </tr>
        <?php $totalGrand += $penjualan->tagihan; ?>
        @endforeach
        <tr>
            <td colspan="5" style="text-align:center;">Grand Total</td>
            <td style="text-align:right;">{{ rupiahFormatExcel( $totalGrand ) }}</td>
        </tr>
    </tbody>
</table>
