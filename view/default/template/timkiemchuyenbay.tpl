<div class="top-page top-page-detail_tt" xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class=" col-md-12 col-sm-12 col-xs-12 duongdan_tt">
            {tieude}
        </div>
        <div class="book-ticket col-md-12 col-sm-12 col-xs-12">
            <form class="form" action="{SITE-NAME}/tim-kiem-chuyen-bay/" method="post">
                <div class="fields">
                    <input type="radio" name="RoundTrip" value="true" id="ve-khu-hoi" checked />
                    <label for="ve-khu-hoi"><span></span>{vekhuhoi_td}</label>
                    <input type="radio" name="RoundTrip" value="false" id="ve-mot-chieu" />
                    <label for="ve-mot-chieu"><span></span>{vemotchieu_td}</label>
                </div>
                <div class="row row-padding-10">
                    <div class="col-md-2 col-sm-12 chon-dia-diem">
                        <p>{diemdi_td}</p>
                        <input type="text" class="chuyen-bay chieu-di" id="chieu-di" value="Hà Nội" name="TFromPlace"/>
                        <input id="hide-chieu-di" type="hidden" name="FromPlace" value="HAN"/>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <p>{diemden_td}</p>
                        <input type="text" class="chuyen-bay chieu-ve" id="chieu-ve" value="Hồ Chí Minh"
                               name="TToPlace"/>
                        <input id="hide-chieu-ve" type="hidden" name="ToPlace" value="SGN"/>
                    </div>
                    <div class="col-md-2-2 col-sm-12 date ngay">
                        <div class="row row-padding-10">
                            <div class="col-md-6 col-sm-12">
                                <p>{ngaydi_td}</p>
                                <input type="text" class="chuyen-bay" id="ngay-di" value="{three_day}"
                                       name="DepartDate"/>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <p>{ngayve_td}</p>
                                <input type="text" class="chuyen-bay" id="ngay-ve" value="{six_day}" name="ReturnDate"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2-1 col-sm-12 ">
                        <p>{nguoilon_td}</p>
                        <div>
                            <a class="sub" href="#">-</a>
                            <select class="nguoi-lon" id="nguoi-lon" name="adult">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                            </select>
                            <a class="sum" href="#">+</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="col-md-2-1 col-sm-12">
                        <p>{treem_td}</p>
                        <div>
                            <a class="sub" href="#">-</a>
                            <select class="tre-em" id="tre-em" value="0" name="child">
								<option value="0">0</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
                            </select>
                            <a class="sum" href="#">+</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="col-md-2-1 col-sm-12">
                        <p>{sosinh_td}</p>
                        <div>
                            <a class="sub" href="#">-</a>
                            <select class="so-sinh" id="so-sinh" value="0" name="infant">
								<option value="0">0</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
                            </select>
                            <a class="sum" href="#">+</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12 tim-kiem_tt  ">
                        <p><input type="submit" value="{timchuyenbay_td}" name="bntTimKiem" /></p>
                        <input id="hide-noi-dia" type="hidden" name="noi-dia" value="true"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</header>
<script type="text/javascript">
    $(document).ready(function (e) {
        $deferered = Array();
        if ({RoundTrip} == "true"
        )
        {
            $deferered.push(search_noidia('{SITE-NAME}/controller/default/timkiem_aj', {data_post}, "VietJetAir,JetStar,VietnamAirlines", "depart", "Price", "result_depart_html"));
            $deferered.push(search_noidia('{SITE-NAME}/controller/default/timkiem_aj', {data_post}, "VietJetAir,JetStar,VietnamAirlines", "landing", "Price", "result_landing_html"));
        }
        else
        {
            $deferered.push(search_noidia('{SITE-NAME}/controller/default/timkiem_aj', {data_post}, "VietJetAir,JetStar,VietnamAirlines", "", "Price", "result_html"));
        }
        $start = Date.now();
    });
</script>
<section class="content-area container">
<div style="padding-left: 0px;   margin-top: 25px;" class="left_sidebar col-md-9-1 col-sm-9 col-xs-12">
    <div class="main-content">
        <form class="form-ve" action="{SITE-NAME}/dat-ve/" method="post">
            <div class="row row-no-padding list-mot-chieu">
                <div class="result col-md-12 col-xs-12 col-lg-12 col-sm-12">
                    <div class="info-result">
                        <div class="hanh-trinh-title">
                            <span>{hanhtrinh_td}</span>
                        </div>
                        <div class="hanh-trinh-info">
                            <div class="chieu-bay">
                                <p class="chieu-di">{TFromPlace}</p>
                                <p class="chieu-ve">{TToPlace}</p>
                            </div>
                            <p class="loai-ve">{loaive_td}: <span>{vemotchieu_td}</span></p>

                            <p class="ngay-xuat-phat">{ngayxuatphat_td}: <span>{DepartDate}</span></p>

                            <p class="so-hanh-khach">{sohk_td}: <span>{adult} {nguoilon_td}, {child} {treem_td}
                                    , {infant} {sosinh_td}</span></p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!-- <div class="date-change">
                         <p>15/6/2015</p>
                     </div>-->
                    <div class="list-result result-depart">
                        <table class="list-ve" width="100%" cellspacing="0" cellpadding="0">
                            <thead>
                            <tr>
                                <th>{chuyen_bay_td}</th>
                                <th>{khoihanh_td}</th>
                                <th></th>
                                <th>{den_td}</th>
                                <th>{giave_td}</th>
                                <th></th>
                                <th>{chitiet_td}</th>
                            </tr>
                            </thead>
                            <tbody id="result_html"></tbody>
                        </table>
						<img class="ajax-loader" src="{SITE-NAME}/view/default/theme/images/ajax-loader.gif" alt="" />
                    </div>
                </div>
				<div class="bottom-offset"></div>
            </div>
            <div class="bottom-fixed">
                <div class="container">
                    <div class="row">
                        <div class="col-md-9 col-sm-9 col-xs-12 send">
                            <label for="dat-ve"><input name="bntDatVe" id="dat-ve" type="submit"  value="Tiếp tục"/></label>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div style="padding-right: 0px" class="right_sidebar col-md-3-right col-sm-3 col-xs-12">
    <div class="tieude_tt">
        <h3>LỌC TÌM KIẾM</h3>
    </div>
    <div class="sap_xep_tt noidung_tk_tt ">
        <div class="ma-new-product-title">
            <h2>Sắp xếp</h2>
            <script>
                $(document).ready(function () {

                    $("#price_sx").click(function () {
                        var Price = $('#price_sx').val();

                        $deferered = Array();

                        $deferered.push(search_noidia('{SITE-NAME}/controller/default/timkiem_aj', {data_post}, "VietJetAir,JetStar,VietnamAirlines", "", Price, "result_html"));
                    })

                    $("#thoigian_sx").click(function () {
                        var DepartTime = $('#thoigian_sx').val();
                        $deferered = Array();
                        $deferered.push(search_noidia('{SITE-NAME}/controller/default/timkiem_aj', {data_post}, "VietJetAir,JetStar,VietnamAirlines", "", DepartTime, "result_html"));
                    })

                    $("#hang_sx").click(function () {
                        var Hang = "1";
                        $deferered = Array();
                        $deferered.push(search_noidia('{SITE-NAME}/controller/default/timkiem_aj', {data_post}, "VietJetAir,JetStar,VietnamAirlines", "", Hang, "result_html"));
                    })

                    $("#vn_sx").click(function () {
                        var giatri_vn = $('#vn_sx').val();
                        $deferered = Array();
                        $deferered.push(search_noidia('{SITE-NAME}/controller/default/timkiem_aj', {data_post}, giatri_vn, "", "VN", "result_html"));
                    })
                    $("#tatca_sx").click(function () {

                        $deferered = Array();
                        $deferered.push(search_noidia('{SITE-NAME}/controller/default/timkiem_aj', {data_post}, "VietJetAir,JetStar,VietnamAirlines", "", "Price", "result_html"));
                    })
                    $("#vietjet_sx").click(function () {
                        var giatri_vn = $('#vietjet_sx').val();
                        $deferered = Array();
                        $deferered.push(search_noidia('{SITE-NAME}/controller/default/timkiem_aj', {data_post}, giatri_vn, "", "VN", "result_html"));
                    })
                    $("#jet_sx").click(function () {

                        $deferered = Array();
                        $deferered.push(search_noidia('{SITE-NAME}/controller/default/timkiem_aj', {data_post}, "JetStar", "", "VN", "result_html"));
                    })
                });
            </script>

        </div>
        <p>
            <input type="radio" checked value="Price" name="sapxep" id="price_sx"> Giá (thấp tới cao)
        </p>

        <p>
            <input type="radio" value="DepartTime" name="sapxep" id="thoigian_sx"> Thời gian khởi hành
        </p>

        <p>
            <input type="radio" value="" name="sapxep" id="hang_sx"> Hãng hàng không
        </p>

        <div class="ma-new-product-title">
            <h2>Hãng hàng không</h2>
        </div>

        <p>
            <input type="radio" value="1" checked name="hang" id="tatca_sx"> Hiển thị tất cả
        </p>

        <p>
            <input type="radio" name="hang" value="VietnamAirlines" id="vn_sx"> Vietnam Airlines
            <img style="float: right" src="{SITE-NAME}/view/default/theme/images/VietnamAirlines.png">
        </p>

        <p>
            <input type="radio" name="hang" value="VietJetAir" id="vietjet_sx"> VietJet Airlines
            <img style="float: right" src="{SITE-NAME}/view/default/theme/images/VietJetAir.png">
        </p>

        <p>
            <input type="radio" name="hang" value="JetStar" id="jet_sx"> JetStar Airlines
            <img style="float: right" src="{SITE-NAME}/view/default/theme/images/JetStar.png">
        </p>

        <div class="ma-new-product-title">
            <h2>Thời gian khởi hành</h2>
        </div>

        <p>
            <script>
                $(function () {
                    $("#slider-range").slider({
                        range: true,
                        min: 0,
                        max: 24,
                        values: [ 0, 24 ],
                        slide: function (event, ui) {
                            $("#amount").val("" + ui.values[ 0 ] + "-" + ui.values[ 1 ]);
                        }
                    });
                    $("#amount").val("" + $("#slider-range").slider("values", 0) +
                            "-" + $("#slider-range").slider("values", 1));

                    $("#slider-range").slider({
                        change: function (event, ui) {
                            var amu = $('#amount').val();

                            $deferered = Array();
                            $deferered.push(search_noidia('{SITE-NAME}/controller/default/timkiem_aj2', {data_post}, "VietJetAir,JetStar,VietnamAirlines", "", amu, "result_html"));  //time-0-17

                        }
                    });
                });

            </script>
        <div class="price-box">
            <p>Chiều đi: <input type="text" id="amount" readonly style="border:0;">(h)</p>
        <div id="slider-range">
        </div>
        <p class="rule_tt">
            <span class="h-6">0h</span>
            <span class="h-4">6h</span>
            <span class="h-4">10h</span>
            <span class="h-4">14h</span>
            <span class="h-4">18h</span>
            <span class="h-6 last">24h</span>
        </p>
    </div>
    </p>
</div>
<div id="chi-tiet-ve"></div>
<div class="fb-page" data-href="https://www.facebook.com/facebook" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/facebook"><a href="https://www.facebook.com/facebook">Facebook</a></blockquote></div></div>
</div>

