<?php
if(!defined('SITE_NAME'))
{
    require_once '../../config.php';
}

if(isset( $_POST['RoundTrip']) && $_POST['RoundTrip'] == 'true')
    $RoundTrip = "true";
else
    $RoundTrip = "false";

$Source = $_GET['source'];
 $Type = $_GET['type'];
$SapXep = $_GET['sapxep'];
$FromPlace = $_POST['FromPlace'];
$TFromPlace = $_POST['TFromPlace'];
$ToPlace = $_POST['ToPlace'];
$TToPlace = $_POST['TToPlace'];
$Adult = $_POST['Adult'];
$Child = $_POST['Child'];
$Infant = $_POST['Infant'];
$DepartDate = date("Y-m-d", strtotime(str_replace("/","-", $_POST['DepartDate'])));
$ReturnDate = date("Y-m-d", strtotime(str_replace("/","-", $_POST['ReturnDate'])));
$data_post = '{
	"RoundTrip": "'.$RoundTrip.'",
	"FromPlace": "'.$FromPlace.'",
	"ToPlace": "'.$ToPlace.'",
	"DepartDate": "'.$DepartDate.'T00:00:00",
	"ReturnDate": "'.$ReturnDate.'T00:00:00",
	"CurrencyType": "VND",
	"Adult": '.$Adult.',
	"Child": '.$Child.',
	"Infant": '.$Infant.',
	"Sources": "'.$Source.'"
	}';

if(isset($_SESSION['dulieu_tk']))
{


    $data=$_SESSION['dulieu_tk'];

}
else
{
    //"Sources": "VietJetAir,VietnamAirlines,JetStar" -  Muốn tìm bao nhiêu hãng thì thêm vào cách nhau dấu ','
    $username = 'sanve24h.com'; $password = 'sanve@admin';
    $ch = curl_init();
    $url = 'http://api.atvietnam.vn/oapi/airline/Flights/Find?$expand=TicketPriceDetails,TicketOptions,Details';
// expand thêm 3 trường TicketPriceDetails,Details,TicketOptions (có thể chỉ chọn 1 hay nhiều )
// TicketPriceDetails : Chi tiết giá Net , thuế phí của người lớn, trẻ em ...
// Details : Chi tiết các chặng dừng
// TicketOptions : Các hạng mục vé khác ( nếu có ), vd VNAirline có Economy Save, Economy Standard ...
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json' )
    );
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);    		  //  curl authentication
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");		//  curl authentication
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_post);
    $str=  curl_exec($ch);
    curl_close($ch);
//echo $str;
    $data = json_decode($str);			// Dữ liệu trả về là kiểu stdClass Object
    unset($_SESSION['dulieu_tk']);
    $_SESSION['dulieu_tk']=$data;
}

function mySort( $a, $b ) {
    return ( strcmp( $a->DepartTime, $b->DepartTime ) );
}
uasort($data->value, 'mySort');



$temp = 1;

$thoigian=explode('-',$SapXep);
 $thoigian_tr=$thoigian[0];
 $thoigian_sau=$thoigian[1];


foreach($data->value as $val) {
    $departTime = strtotime($val->DepartTime);
    $landingTime = strtotime($val->LandingTime);
    $timeSpan = $landingTime - $departTime;
    $gio = ($timeSpan-$timeSpan%3600)/3600<10?'0'.($timeSpan-$timeSpan%3600)/3600:($timeSpan-$timeSpan%3600)/3600;
    $phut = (($timeSpan%3600)-($timeSpan%3600)%60)/60<10?'0'.(($timeSpan%3600)-($timeSpan%3600)%60)/60:(($timeSpan%3600)-($timeSpan%3600)%60)/60;

    $thoigian_ss=date("H", $departTime);

        if($thoigian_tr<=$thoigian_ss && $thoigian_sau>$thoigian_ss)
        {
            if($RoundTrip == "true") {
                if($Type == "depart") {
                    if(date("d/m/Y", $departTime) == date("d/m/Y", strtotime($_POST['DepartDate']))) { ?>
                        <tr class="i-result">
                            <td class="logo-air"><img src="<?php echo SITE_NAME ?>/view/default/theme/images/<?=$val->AirlineCode?>.png" alt="" /><p><?=$val->FlightNumber?></p></td>
                            <td class="den-di"><p><?php echo date("H:i", strtotime($val->DepartTime)); ?><span>(<?=$val->FromPlace;?>)</span></p></td>
                            <td class="den-di"><p><?php echo date("H:i", strtotime($val->LandingTime)); ?><span>(<?=$val->ToPlace;?>)</span></p></td>
                            <td class="gia"><p><?=number_format($val->Price)?> <sup>vnđ</sup></p>
                                <a href="#">Xem chi tiết</a></td>
                            <td class="check-ve">
                                <input type="radio" id="air-<?php echo $temp; ?>" flightref="<?=$val->FlightNumber?>" name="Block<?=$RoundTrip?>depart" value="<?=$val->FlightNumber?>" recec="0" />
                                <label for="air-<?php echo $temp; ?>"><span></span>&nbsp</label>
                            </td>
                        </tr>
                        <tr style="" class="flight-info-detail">
                            <td class="flight-detail-content" colspan="8">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tbody class="view-detail-flight">
                                    <tr>
                                        <td valign="top">
                                            <h4>Chuyến bay</h4>
                                            <p><span><?=$val->AirlineCode?></span></p>
                                            <p><span><?=$val->FlightNumber?></b></span></p>
                                            <p>Loại vé: <span><?=$val->TicketType?></span></p>
                                        </td>
                                        <td valign="top">
                                            <h4>Khởi hành</h4>
                                            <p>Từ <span class="color-blue"><?php echo $TFromPlace;?>, </span>Việt Nam</p>
                                            <p>Sân bay: <span><?php echo $TFromPlace;?> (<?php echo $FromPlace;?>)</span></p>
                                            <p>Thời gian: <span class="color-blue"><?php echo date("H:i", $departTime); ?></span>, <?php echo date("d/m/Y", $departTime); ?></p>
                                        </td>
                                        <td valign="top">
                                            <h4>Điểm đến</h4>
                                            <p>Tới <span class="color-blue"><?php echo $TToPlace;?>, </span>Việt Nam</p>
                                            <p>Sân bay: <span><?php echo $TToPlace;?> (<?php echo $ToPlace;?>)</span></p>
                                            <p>Thời gian: <span class="color-blue"><?php echo date("H:i", $landingTime); ?></span>, <?php echo date("d/m/Y", $landingTime); ?></p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table width="100%" class="price-break">
                                    <tbody>
                                    <tr class="title-b">
                                        <td nowrap="" align="center" class="header">Hành khách</td>
                                        <td nowrap="" align="center" class="header">Số lượng vé</td>
                                        <td nowrap="" align="center" class="header pb-price">Giá mỗi vé</td>
                                        <td nowrap="" align="center" class="header pb-price">Thuế &amp; Phí</td>
                                        <td nowrap="" align="center" style="display:none;" class="header pb-price">Giảm giá</td>
                                        <td nowrap="" align="center" class="header pb-price">Tổng giá</td>
                                    </tr>

                                    <tr class="total-b">
                                        <td align="right" class="footer" colspan="3"></>
                                        <td align="right" class="footer">
                                            <p>Tổng cộng </p>
                                        <td align="center" class="footer pb-price" colspan="2">
                                            <p>1,503,000 VNĐ</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="dieu-kien" width="90%" cellspacing="0" cellpadding="0">
                                    <colgroup><col width="170">
                                        <col width="450">
                                    </colgroup>
                                    <tbody>
                                    <tr class="title">
                                        <td colspan="2"><h4>Điều kiện hành lý</h4></td>
                                    </tr>
                                    <tr>
                                        <td class="name">Hành Lý Xách Tay</td>
                                        <td>7 kg</td>
                                    </tr>
                                    <tr>
                                        <td class="name">Hành lý ký gửi</td>
                                        <td>Vui lòng chọn ở bước sau</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="dieu-kien" cellspacing="0" cellpadding="0" width="90%">
                                    <colgroup>
                                        <col width="170">
                                        <col width="450">
                                    </colgroup>
                                    <tbody>
                                    <tr class="title">
                                        <td colspan="2"><h4>Điều kiện về vé</h4></td>
                                    </tr>
                                    <tr><td valign="top" class="name">Hoàn Vé</td><td valign="top">Không được phép</td></tr><tr><td valign="top" class="name">Đổi Tên Hành Khách</td><td valign="top">Được phép - Thu phí: 352,000 VND</td></tr><tr><td valign="top" class="name">Đổi Hành Trình</td><td valign="top">Được phép - Thu phí: 352.000 VND + Giá vé chênh lệch (nếu có). Đổi đồng hạng hoặc nâng hạng tương ứng của hành trình mới.</td></tr><tr><td valign="top" class="name">Đổi Ngày Giờ Chuyến Bay</td><td valign="top">Được phép - Thu phí: 352.000 VND + Giá vé chênh lệch (nếu có).</td></tr><tr><td valign="top" class="name">Bảo lưu</td><td valign="top">Không được phép</td></tr><tr><td valign="top" class="name">Thời hạn thay đổi (bao gồm thay đổi tên, ngày/chuyến bay)</td><td valign="top">Trước giờ khởi hành 12 tiếng.</td></tr>
                                    <tr style="display:none;" class="title">
                                        <td colspan="2">Điều kiện chung:</td>
                                    </tr>
                                    <tr style="display:none;">
                                        <td colspan="2">{GeneralRule}</td>
                                    </tr>
                                    </tbody></table>
                            </td>
                        </tr>
                    <?php }
                }
                else if($Type == "landing") {
                    if(date("d/m/Y", $departTime) != date("d/m/Y", strtotime($_POST['DepartDate']))) { ?>
                        <tr class="i-result">
                            <td class="logo-air"><img src="<?php echo SITE_NAME ?>/view/default/theme/images/<?=$val->AirlineCode?>.png" alt="" /><p><?=$val->FlightNumber?></p></td>
                            <td class="den-di"><p><?php echo date("H:i", strtotime($val->DepartTime)); ?><span>(<?=$val->FromPlace;?>)</span></p></td>
                            <td class="den-di"><p><?php echo date("H:i", strtotime($val->LandingTime)); ?><span>(<?=$val->ToPlace;?>)</span></p></td>
                            <td class="gia"><p><?=number_format($val->Price)?> <sup>vnđ</sup></p>
                                <a href="#">Xem chi tiết</a></td>
                            <td class="check-ve">
                                <input type="radio" id="air-<?php echo $temp; ?>" flightref="<?=$val->FlightNumber?>" name="Block<?=$RoundTrip?>landing" value="<?=$val->FlightNumber?>" recec="0" />
                                <label for="air-<?php echo $temp; ?>"><span></span>&nbsp</label>
                            </td>
                        </tr>
                        <tr style="" class="flight-info-detail">
                            <td class="flight-detail-content" colspan="8">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tbody class="view-detail-flight">
                                    <tr>
                                        <td valign="top">
                                            <h4>Chuyến bay</h4>
                                            <p><span><?=$val->AirlineCode?></span></p>
                                            <p><span><?=$val->FlightNumber?></b></span></p>
                                            <p>Loại vé: <span><?=$val->TicketType?></span></p>
                                        </td>
                                        <td valign="top">
                                            <h4>Khởi hành</h4>
                                            <p>Từ <span class="color-blue"><?php echo $TFromPlace;?>, </span>Việt Nam</p>
                                            <p>Sân bay: <span><?php echo $TFromPlace;?> (<?php echo $FromPlace;?>)</span></p>
                                            <p>Thời gian: <span class="color-blue"><?php echo date("H:i", $departTime); ?></span>, <?php echo date("d/m/Y", $departTime); ?></p>
                                        </td>
                                        <td valign="top">
                                            <h4>Điểm đến</h4>
                                            <p>Tới <span class="color-blue"><?php echo $TToPlace;?>, </span>Việt Nam</p>
                                            <p>Sân bay: <span><?php echo $TToPlace;?> (<?php echo $ToPlace;?>)</span></p>
                                            <p>Thời gian: <span class="color-blue"><?php echo date("H:i", $landingTime); ?></span>, <?php echo date("d/m/Y", $landingTime); ?></p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table width="100%" class="price-break">
                                    <tbody>
                                    <tr class="title-b">
                                        <td nowrap="" align="center" class="header">Hành khách</td>
                                        <td nowrap="" align="center" class="header">Số lượng vé</td>
                                        <td nowrap="" align="center" class="header pb-price">Giá mỗi vé</td>
                                        <td nowrap="" align="center" class="header pb-price">Thuế &amp; Phí</td>
                                        <td nowrap="" align="center" style="display:none;" class="header pb-price">Giảm giá</td>
                                        <td nowrap="" align="center" class="header pb-price">Tổng giá</td>
                                    </tr>
                                    <?php
                                    if($val->AirlineCode == "VietJetAir") {
                                        if($Adult) {
                                            $Price = $val->Price;
                                            $price_tax = ($Price*110/100) + 190;
                                            ?>
                                            <tr>
                                                <td align="center" class="pax">Người lớn</td>
                                                <td align="center" class="pax"><?php echo $Adult; ?></td>
                                                <td align="center" class="pax pb-price"><?php echo $Price; ?> VNĐ</td>
                                                <td align="center" class="pax pb-price"><?php echo $price_tax; ?></td>
                                                <td align="center" style="display:none;" class="pax pb-price">0 VNĐ</td>
                                                <td align="center" class="pax pb-price"><?php echo ($Price + $price_tax)*$Adult; ?></td>
                                            </tr>
                                        <?php }
                                    }
                                    ?>
                                    <tr class="total-b">
                                        <td align="right" class="footer" colspan="3"></>
                                        <td align="right" class="footer">
                                            <p>Tổng cộng </p>
                                        <td align="center" class="footer pb-price" colspan="2">
                                            <p>1,503,000 VNĐ</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="dieu-kien" width="90%" cellspacing="0" cellpadding="0">
                                    <colgroup><col width="170">
                                        <col width="450">
                                    </colgroup>
                                    <tbody>
                                    <tr class="title">
                                        <td colspan="2"><h4>Điều kiện hành lý</h4></td>
                                    </tr>
                                    <tr>
                                        <td class="name">Hành Lý Xách Tay</td>
                                        <td>7 kg</td>
                                    </tr>
                                    <tr>
                                        <td class="name">Hành lý ký gửi</td>
                                        <td>Vui lòng chọn ở bước sau</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="dieu-kien" cellspacing="0" cellpadding="0" width="90%">
                                    <colgroup>
                                        <col width="170">
                                        <col width="450">
                                    </colgroup>
                                    <tbody>
                                    <tr class="title">
                                        <td colspan="2"><h4>Điều kiện về vé</h4></td>
                                    </tr>
                                    <tr><td valign="top" class="name">Hoàn Vé</td><td valign="top">Không được phép</td></tr><tr><td valign="top" class="name">Đổi Tên Hành Khách</td><td valign="top">Được phép - Thu phí: 352,000 VND</td></tr><tr><td valign="top" class="name">Đổi Hành Trình</td><td valign="top">Được phép - Thu phí: 352.000 VND + Giá vé chênh lệch (nếu có). Đổi đồng hạng hoặc nâng hạng tương ứng của hành trình mới.</td></tr><tr><td valign="top" class="name">Đổi Ngày Giờ Chuyến Bay</td><td valign="top">Được phép - Thu phí: 352.000 VND + Giá vé chênh lệch (nếu có).</td></tr><tr><td valign="top" class="name">Bảo lưu</td><td valign="top">Không được phép</td></tr><tr><td valign="top" class="name">Thời hạn thay đổi (bao gồm thay đổi tên, ngày/chuyến bay)</td><td valign="top">Trước giờ khởi hành 12 tiếng.</td></tr>
                                    <tr style="display:none;" class="title">
                                        <td colspan="2">Điều kiện chung:</td>
                                    </tr>
                                    <tr style="display:none;">
                                        <td colspan="2">{GeneralRule}</td>
                                    </tr>
                                    </tbody></table>
                            </td>
                        </tr>
                    <?php }
                }
                $temp++;}
            else { ?>
                <tr class="i-result">
                    <td class="logo-air"><img src="<?php echo SITE_NAME ?>/view/default/theme/images/<?=$val->AirlineCode?>.png" alt="" /><p><?=$val->FlightNumber?></p></td>
                    <td class="den-di"><p><?php echo date("H:i", strtotime($val->DepartTime)); ?><span>(<?=$val->FromPlace;?>)</span></p></td>
                    <td class="thoi-gian"><span><?php echo $gio.":".$phut ?></span></td>
                    <td class="den-di"><p><?php echo date("H:i", strtotime($val->LandingTime)); ?><span>(<?=$val->ToPlace;?>)</span></p></td>
                    <td class="gia"><p><?=number_format($val->Price)?> <sup>vnđ</sup></p><div class="TicketPrice" style="display: none; "><?=$val->Price; ?></div></td>
                    <td class="check-ve">
                        <input type="radio" class="check-ve-radio" id="air-<?php echo $temp; ?>" flightref="<?=$val->FlightNumber?>" name="Block<?=$RoundTrip?>" value="<?=$val->FlightNumber?>" recec="0" />
                        <label for="air-<?php echo $temp; ?>"><span></span>&nbsp</label>
                    </td>
                    <td class="chi-tiet"><a href="#">Xem chi tiết</a></td>
                </tr>
                <tr style="" class="flight-info-detail">
                    <td class="flight-detail-content" colspan="8">
                        <table width="100%" cellspacing="0" cellpadding="0">
                            <tbody class="view-detail-flight">
                            <tr>
                                <td valign="top">
                                    <h4>Chuyến bay</h4>
                                    <p><span><?=$val->AirlineCode?></span></p>
                                    <p><span><?=$val->FlightNumber?></b></span></p>
                                    <p>Loại vé: <span><?=$val->TicketType?></span></p>
                                </td>
                                <td valign="top">
                                    <h4>Khởi hành</h4>
                                    <p>Từ <span class="color-blue"><?php echo $TFromPlace;?>, </span>Việt Nam</p>
                                    <p>Sân bay: <span><?php echo $TFromPlace;?> (<?php echo $FromPlace;?>)</span></p>
                                    <p>Thời gian: <span class="color-blue"><?php echo date("H:i", $departTime); ?></span>, <?php echo date("d/m/Y", $departTime); ?></p>
                                </td>
                                <td valign="top">
                                    <h4>Điểm đến</h4>
                                    <p>Tới <span class="color-blue"><?php echo $TToPlace;?>, </span>Việt Nam</p>
                                    <p>Sân bay: <span><?php echo $TToPlace;?> (<?php echo $ToPlace;?>)</span></p>
                                    <p>Thời gian: <span class="color-blue"><?php echo date("H:i", $landingTime); ?></span>, <?php echo date("d/m/Y", $landingTime); ?></p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table width="100%" class="price-break">
                            <tbody>
                            <tr class="title-b">
                                <td nowrap="" align="center" class="header">Hành khách</td>
                                <td nowrap="" align="center" class="header">Số lượng vé</td>
                                <td nowrap="" align="center" class="header pb-price">Giá mỗi vé</td>
                                <td nowrap="" align="center" class="header pb-price">Thuế &amp; Phí</td>
                                <td nowrap="" align="center" style="display:none;" class="header pb-price">Giảm giá</td>
                                <td nowrap="" align="center" class="header pb-price">Tổng giá</td>
                            </tr>
                            <?php
                            if($val->AirlineCode == "VietJetAir" || $val->AirlineCode == "JetStar") {
                                $price_total1 = 0;
                                $price_total2 = 0;
                                $price_total3 = 0;
                                if($Adult) {
                                    $Price = $val->Price;
                                    $price_tax = ($Price*10/100)*1 + 190000*1;
                                    $price_total1 = ($Price + $price_tax)*$Adult;
                                    ?>
                                    <tr>
                                        <td align="center" class="pax">Người lớn</td>
                                        <td align="center" class="pax"><?php echo $Adult; ?></td>
                                        <td align="center" class="pax pb-price"><?php echo $Price; ?> VNĐ</td>
                                        <td align="center" class="pax pb-price"><?php echo $price_tax; ?></td>
                                        <td align="center" style="display:none;" class="pax pb-price">0 VNĐ</td>
                                        <td align="center" class="pax pb-price"><?php echo $price_total1; ?></td>
                                    </tr>
                                <?php }
                                if($Child) {
                                    $Price = $val->Price;
                                    $price_tax = ($Price*10/100)*1 + 140000*1;
                                    $price_total2 = ($Price + $price_tax)*$Child;
                                    ?>
                                    <tr>
                                        <td align="center" class="pax">Trẻ em</td>
                                        <td align="center" class="pax"><?php echo $Child; ?></td>
                                        <td align="center" class="pax pb-price"><?php echo $Price; ?> VNĐ</td>
                                        <td align="center" class="pax pb-price"><?php echo $price_tax; ?></td>
                                        <td align="center" style="display:none;" class="pax pb-price">0 VNĐ</td>
                                        <td align="center" class="pax pb-price"><?php echo $price_total2; ?></td>
                                    </tr>
                                <?php } ?>
                                <tr class="total-b">
                                    <td align="right" class="footer" colspan="3"></>
                                    <td align="right" class="footer">
                                        <p>Tổng cộng </p>
                                    <td align="center" class="footer pb-price" colspan="2">
                                        <p><?php echo $price_total1 + $price_total2 + $price_total3; ?> VNĐ</p>
                                    </td>
                                </tr>
                            <?php }
                            if($val->AirlineCode == "VietnamAirlines") {
                                $price_total1 = 0;
                                $price_total2 = 0;
                                $price_total3 = 0;
                                if($Adult) {
                                    $Price = $val->Price;
                                    $price_tax = ($Price*10/100)*1 + 190000*1;
                                    $price_total1 = ($Price + $price_tax)*$Adult;
                                    ?>
                                    <tr>
                                        <td align="center" class="pax">Người lớn</td>
                                        <td align="center" class="pax"><?php echo $Adult; ?></td>
                                        <td align="center" class="pax pb-price"><?php echo $Price; ?> VNĐ</td>
                                        <td align="center" class="pax pb-price"><?php echo $price_tax; ?></td>
                                        <td align="center" style="display:none;" class="pax pb-price">0 VNĐ</td>
                                        <td align="center" class="pax pb-price"><?php echo $price_total1; ?></td>
                                    </tr>
                                <?php }
                                if($Child) {
                                    $Price = $val->Price;
                                    $price_tax = ($Price*10/100)*1 + 140000*1;
                                    $price_total2 = ($Price + $price_tax)*$Child;
                                    ?>
                                    <tr>
                                        <td align="center" class="pax">Trẻ em</td>
                                        <td align="center" class="pax"><?php echo $Child; ?></td>
                                        <td align="center" class="pax pb-price"><?php echo $Price; ?> VNĐ</td>
                                        <td align="center" class="pax pb-price"><?php echo $price_tax; ?></td>
                                        <td align="center" style="display:none;" class="pax pb-price">0 VNĐ</td>
                                        <td align="center" class="pax pb-price"><?php echo $price_total2; ?></td>
                                    </tr>
                                <?php }
                                if($Infant) {
                                    $Price = $val->Price;
                                    $price_tax = ($Price*10/100)*1 + 140000*1;
                                    $price_total3 = ($Price + $price_tax)*$Child;
                                    ?>
                                    <tr>
                                        <td align="center" class="pax">Sơ sinh</td>
                                        <td align="center" class="pax"><?php echo $Child; ?></td>
                                        <td align="center" class="pax pb-price"><?php echo $Price; ?> VNĐ</td>
                                        <td align="center" class="pax pb-price"><?php echo $price_tax; ?></td>
                                        <td align="center" style="display:none;" class="pax pb-price">0 VNĐ</td>
                                        <td align="center" class="pax pb-price"><?php echo $price_total2; ?></td>
                                    </tr>
                                <?php }?>
                                <tr class="total-b">
                                    <td align="right" class="footer" colspan="3"></>
                                    <td align="right" class="footer">
                                        <p>Tổng cộng </p>
                                    <td align="center" class="footer pb-price" colspan="2">
                                        <p><?php echo $price_total1 + $price_total2 + $price_total3; ?> VNĐ</p>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <table class="dieu-kien" width="90%" cellspacing="0" cellpadding="0">
                            <colgroup><col width="170">
                                <col width="450">
                            </colgroup>
                            <tbody>
                            <tr class="title">
                                <td colspan="2"><h4>Điều kiện hành lý</h4></td>
                            </tr>
                            <tr>
                                <td class="name">Hành Lý Xách Tay</td>
                                <td>7 kg</td>
                            </tr>
                            <tr>
                                <td class="name">Hành lý ký gửi</td>
                                <td>Vui lòng chọn ở bước sau</td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="dieu-kien" cellspacing="0" cellpadding="0" width="90%">
                            <colgroup>
                                <col width="170">
                                <col width="450">
                            </colgroup>
                            <tbody>
                            <tr class="title">
                                <td colspan="2"><h4>Điều kiện về vé</h4></td>
                            </tr>
                            <tr><td valign="top" class="name">Hoàn Vé</td><td valign="top">Không được phép</td></tr><tr><td valign="top" class="name">Đổi Tên Hành Khách</td><td valign="top">Được phép - Thu phí: 352,000 VND</td></tr><tr><td valign="top" class="name">Đổi Hành Trình</td><td valign="top">Được phép - Thu phí: 352.000 VND + Giá vé chênh lệch (nếu có). Đổi đồng hạng hoặc nâng hạng tương ứng của hành trình mới.</td></tr><tr><td valign="top" class="name">Đổi Ngày Giờ Chuyến Bay</td><td valign="top">Được phép - Thu phí: 352.000 VND + Giá vé chênh lệch (nếu có).</td></tr><tr><td valign="top" class="name">Bảo lưu</td><td valign="top">Không được phép</td></tr><tr><td valign="top" class="name">Thời hạn thay đổi (bao gồm thay đổi tên, ngày/chuyến bay)</td><td valign="top">Trước giờ khởi hành 12 tiếng.</td></tr>
                            <tr style="display:none;" class="title">
                                <td colspan="2">Điều kiện chung:</td>
                            </tr>
                            <tr style="display:none;">
                                <td colspan="2">{GeneralRule}</td>
                            </tr>
                            </tbody></table>
                    </td>
                </tr>
            <?php }
        }



} ?>

