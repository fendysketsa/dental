<style>
    #invoice-POS #top .logo {
        height: 60px;
        width: 60px;
        border-radius: 50%;
        background: url(<?php echo (!empty($dataE->logo) ? asset('s-home/setting/nota/uploads/'. $dataE->logo) : asset('images/noimage.jpg')) ?>) no-repeat;
        background-size: 60px 60px;
    }
</style>

<div id="invoice-POS">

    <center id="top">
        <div class="logo">

        </div>
        <div class="info">
            <h2>{{ $dataE->title }}</h2>
        </div>
    </center>

    <div id="mid">
        <div class="info">
            <div style="height:55px; float:left; width:50%; text-align:left;">
                <h2>Contact Info</h2>
                <div class="point-contact">
                    <?php echo $dataE->contact_info; ?>
                </div>
            </div>
            <div style="font-style:italic; height:55px; float:right; width:50%; text-align:right;">
                <h2>Receipt</h2>
                <p><strong>345345435</strong></p>
            </div>
            <div class="div-lebar">
                <p class="p-left">
                    <small>Date:<br></small>
                    <small>{{ date('l, d M Y H:i:s') }}</small>
                </p>
                <p class="p-right">
                    <small>Cashier:<br></small>
                    <small>Anggun</small>
                </p>
            </div>
        </div>
    </div>

    <div id="bot">
        <div id="table">
            <table>
                <tr class="tabletitle">
                    <td class="item">
                        <h2>Item</h2>
                    </td>
                    <td class="Hours">
                        <h2>Person</h2>
                    </td>
                    <td class="Rate">
                        <h2>Sub Total</h2>
                    </td>
                </tr>

                <tr class="service">
                    <td class="tableitem">
                        <p class="itemtext">Waxing</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">5</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">Rp 450000</p>
                    </td>
                </tr>

                <tr class="service">
                    <td class="tableitem">
                        <p class="itemtext">Bleaching</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">2</p>
                    </td>
                    <td class="tableitem">
                        <p class="itemtext">Rp 120000</p>
                    </td>
                </tr>

                <tr class="tabletitle">
                    <td></td>
                    <td class="Rate">
                        <h2>tax</h2>
                    </td>
                    <td class="payment">
                        <h2>Rp 200000</h2>
                    </td>
                </tr>
                <tr class="tabletitle">
                    <td></td>
                    <td class="Rate">
                        <h2>Total</h2>
                    </td>
                    <td class="payment">
                        <h2>Rp 590000</h2>
                    </td>
                </tr>
            </table>
        </div>
        <div id="legalcopy">
            <p class="legal"><strong>{{ $dataE->salutation }}</strong>
            </p>
        </div>
    </div>
</div>
