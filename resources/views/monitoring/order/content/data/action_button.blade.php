<div class="dropdown to-change-content-serv">
    <button
        class="btn btn-xs @if($status==1 || $status==4) btn-default btn-disabled @else btn-primary @endif dropdown-toggle"
        type="button" data-toggle="dropdown">
        <em class="fa fa-gear"></em>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:0px !important; left:8px !important;">
        <li>
            <a style="display:-webkit-box;" class="btn detail-hstry btn-xs btn-3d" data-toggle="modal"
                data-id-member="{{ $user_id }}" data-id-trans="{{ $id }}"
                data-route="{{ route('members-history.show', $user_id) }}" data-toggle="modal" data-target="#detMember"
                data-backdrop="static" data-keyboard="false"><em class="fa fa-th-list"></em>
                History
            </a>
        </li>
        <li>
            <a style="display:-webkit-box;"
                class="btn @if($status==4 || $status==1) btn-default btn-disabled @else edit @endif btn-xs btn-3d "
                @if($status>1 && $status<4) data-id-cetak-on="{{ $id }}"
                    data-route-on="{{ route('monitoring.order.printOut') }}" data-toggle="modal"
                    data-target="#formModalMontrgOrder" data-backdrop="static" data-keyboard="false"
                    data-route="{{ route('monitoring.order.update', $id) }}" @endif><em
                        class="fa fa-pencil-square-o"></em> Ubah
            </a>
        </li>
        <li>
            <a style="display:-webkit-box;"
                class="btn @if($status==4 || $status==1) btn-default btn-disabled @else periksa @endif btn-xs btn-3d "
                @if($status>1
                && $status<4) data-toggle="modal" data-target="#formModalMontrgOrderPeriksa" data-backdrop="static"
                    data-keyboard="false" data-routes="{{ route('monitoring.order.saveperiksa') }}"
                    data-route="{{ route('monitoring.order.periksa', $id) }}" @endif><em class="fa fa-search"></em>
                    Periksa</a>
        </li>
        {{-- <li>
            <a style="display:-webkit-box;"
                class="btn @if($status==1 || $status==4) btn-default btn-disabled @else print @endif btn-xs btn-3d"
                @if($status>1 && $status<4) data-id-cetak="{{ $id }}"
        data-route="{{ route('monitoring.order.printOut') }}" @endif>
        <em class="fa fa-print"></em> Cetak
        </a>
        </li> --}}

        @if($print_act >= 0 && $status<4 && $status>1)
            <li>
                <a style="display:-webkit-box;"
                    class="btn @if($print_act >= 0 && $status>1 && $status<4) send-pembayaran @else btn-default btn-disabled @endif btn-xs btn-3d"
                    @if($print_act>= 0) data-id-send-pembayaran="{{ $id }}"
                    data-route-send-pembayaran="{{ route('monitoring.order.sendPembayaran') }}" @endif
                    data-toggle="tooltip"
                    title="Kirim ke Pembayaran!"><em class="fa fa-arrow-right"></em> Send
                </a>
            </li>
            @endif

            @if($status<4 && $status>1)
                <li>
                    <a style="display:-webkit-box;"
                        class="btn @if($status==1 || $status==4) btn-default btn-disabled @else void-pembayaran @endif btn-xs btn-3d"
                        @if($status>1 && $status<4) data-id-void="{{ $id }}"
                            data-route-void="{{ route('monitoring.order.voidPembayaran') }}" @endif title="Void!"><em
                                class="fa fa-user-times"></em> Void
                    </a>
                </li>
                @endif
    </ul>
</div>
