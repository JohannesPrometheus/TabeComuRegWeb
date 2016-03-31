    // Show a custom confirmation dialog
    //

    function onBodyLoad() {
        document.addEventListener("deviceready", onDeviceReady, false);
    }

    function onDeviceReady() {
        Initialize();
        sizeFitting();
    }


    // スクリプト

    var login_flag      = false;
    
    var checkTimer;

    var shop_id     = ''; // 店舗ID
    var shop_pass   = ''  // 店舗パスワード

    var saved_id    = window.localStorage.getItem('shop_id');
    var saved_pass  = window.localStorage.getItem('shop_pass');


    // 初期化処理
    var SCREEN_WIDTH    = $(window).width();
    var SCREEN_HEIGHT   = $(window).height();

    var heightBuffer = 0;
    heightBuffer = SCREEN_HEIGHT;

    var total           = 0;
    var totalStr        = new String('');
    var totalDispStr    = new String('');

    var tester = '';

    var netresult = null;

    focusfixFlag = false;

    var TC_VERSION = '1.0.2.1sp';
    var latest_version;
    var latest_version_info;

    var PHP_ROOT    = 'http://gtl.jp/asp/tabecomu4sp/';
    
    var frame_width;
    
    var font_size_large_btn;
    var font_size_tenkey;

    var font_size_small;
    var font_size_middle;
    var font_size_large;
    var font_size_table;
    var font_size_strong;

    var btn_radius;

    var icon_size;
    var icon_image_size;
    var icon_radius;

    var icon_bottom_pos;
    var icon_right_pos;


    function BackProc() {
        $('#code_area').height(0);
        $('#result_area').height(0);
        $('#calc_area').show();
        $('#calc_area').height(heightBuffer);
    }

    function ClearBackProc() {
        $('#code_area').height(0);
        $('#result_area').height(0);
        $('#calc_area').show();
        $('#calc_area').height(heightBuffer);
        TenkeyProc('AC');
    }

    function TenkeyProc(c) {
        if (c == 'OK') {
            NetworkCheck();

            if (totalStr < 1) {
                alert("金額をご入力ください");
                return;
            }
            $('#calc_area').hide();
            $('#calc_area').height(0);
            
            $('#code_area').show();
		    $('#code_area').height(heightBuffer);
		    $('#qr_code_area').load(PHP_ROOT+'disp_barcode.php?SHOP_ID=' + shop_id + '&TOTAL=' + totalStr);
		    $('#qr_price').html('ご利用金額　￥' + totalDispStr);

	    }else{
		    if( c == 'AC' ){
			    total = 0;
		    }else{
			    totalStr = totalStr + c;
			    total = Number(totalStr);
		    }
		    totalStr = total.toString();
		    totalDispStr = total.toLocaleString();
		    $('#disp_area').html(totalDispStr);
	    }
    }

    function DoneProc(moode, order_id) {
        NetworkCheck();

        $('#result_area').hide()
        $('#result_area').height(0);
        $('#result_area').load(PHP_ROOT + 'done_proc.php?DONE_MODE=' + moode + '&order_id=' + order_id, sizeFitting);

        $('#result_area').height(heightBuffer);
        $("#result_area").delay(500);
        $('#result_area').fadeIn("slow");
    }

    function showCalc() {
        
        NetworkCheck();

        $('#vsn_box').hide();
        $('#logout_btn').hide();
        $('#mng_btn').hide();
        $('#menu_area').css('overflow', 'hidden');
        $('#menu_area').height(0);
        
        $('#home_btn').show();
        
        $('#calc_area').show();
        $('#calc_area').height(heightBuffer);
     }

    function showHistory() {
        NetworkCheck();

        $('#vsn_box').hide();
        $('#mng_btn').hide();
        var tmp_url;
        $('#home_btn').show();
        $('#menu_area').css('overflow', 'hidden');
        $('#menu_area').height(0);
        $('#history_area').hide();
        $('#history_area').height(heightBuffer);
        tmp_url = PHP_ROOT + 'disp_history.php?shop_code=' + shop_id;
        $('#history_window').attr({ 'src': tmp_url });
        setTimeout(function () {
            if (netresult) {
                loginCheck();
                $('#logout_btn').fadeIn(300);
                $('#history_area').fadeIn(300);
            }
        }, 1000);

    }

    function showScan() {
            NetworkCheck();

		    $('#code_area').css('overflow','hidden');
            $('#code_area').hide();
		    $('#code_area').height(0);
            
            $('#scan_area').show();
		    $('#scan_area').height(heightBuffer);
		    $('#scan_area').load(PHP_ROOT + 'disp_scan.php?shop_code=' + shop_id + '&TOTAL=' + totalStr, sizeFitting);
		    focusfixFlag = true;
        
    }

    function historyBack(){
		    $('#history_area').css('overflow','hidden');
		    $('#history_area').height(0);
		    $('#calc_area').height(heightBuffer);
    }

    function showMenu() {

        loginCheck();

        focusfixFlag = false;

        total = 0;
        totalStr = "";
        totalDispStr = "";

        $('#disp_area').html(totalDispStr);
        
        $('#logout_btn').hide();
        $('#home_btn').hide();
        
        $('#vsn_box').show();
        $('#mng_btn').show();
        
        $('#menu_area').height(heightBuffer);

        $('#calc_area').height(0);
        $('#code_area').height(0);
        $('#check_area').height(0);
        $('#scan_area').height(0);
        $('#result_area').height(0);
        $('#history_area').height(0);
        $('#login_form_area').height(0)　;
    }



    function versionCheck() {

        $('#version_check_area').load(PHP_ROOT + 'version.html?ts='+Math.floor( $.now() ), null, downloadConfirm );

    }

    function downloadConfirm() {
        $('#vsn_box').html('ver.' + TC_VERSION);

        latest_version = $('#version_check_area').text();
        latest_version_info = latest_version.split('|');

        if($.trim(latest_version_info[0])==TC_VERSION ){

        } else {
            if (latest_version_info[0] != 0) {
                if (confirm('新しいバージョン(' + latest_version_info[0] + ')が使用可能です。\n更新しますか？')) {
                    window.open($.trim(latest_version_info[1]) + '?ts=' + Math.floor($.now()), '_system', 'location=yes');
                }
            }
        }
    }

    function loginCheck() {
        login_flag = false;

         $('#login_check_area').html("");
         $('#login_check_area').load(PHP_ROOT + 'login_check.php', null, loginCheckProc);
    }

    function loginCheckProc() {
        var login_result = $('#login_check_area').text();
        login_result = $.trim(login_result);
        if (login_result == 'OK') {
            login_flag = true;
            if (shop_id == '') {
                reLoginProc();
            } else {

            }
        } else if (login_result == 'NG') {
            loginProc();
        } else {
            //alert('ネットワークに接続できません。');
        }
    }

    function loginProc() {

        if (saved_id != "" && saved_id != null) {

            var try_id      = saved_id;
            var try_pass    = saved_pass;

            $('#login_try_area').load(PHP_ROOT + 'login_try.php?username=' + try_id + '&password=' + try_pass, null, tryResultProc);
        } else {
            $('#logout_btn').hide();
            $('#mng_btn').hide();
            $('#menu_area').height(0);

            $('#login_form_area').show();
            $('#login_form_area').height(heightBuffer);


            $('#calc_area').height(0);
            $('#code_area').height(0);
            $('#check_area').height(0);
            $('#scan_area').height(0);
            $('#result_area').height(0);
            $('#history_area').height(0);
        }
    }

    function loginTryProc() {
        var try_id      = $('#username').val();
        var try_pass    = $('#password').val();

        $('#login_try_area').load(PHP_ROOT + 'login_try.php?username=' + try_id + '&password=' + try_pass, null, tryResultProc);
    }

    function tryResultProc() {
        var login_result = $('#login_try_area').html();

        login_result = $.trim(login_result);

        if (login_result == 'ERROR') {
            alert('IDまたはパスワードが間違っています');
            loginProc();
        } else {
            var userInfo = login_result.split('|');
            if (userInfo[0] == 'OK') {
                login_flag = true;

                shop_id     = userInfo[1];
                shop_pass   = userInfo[2];

                window.localStorage.setItem("shop_id", shop_id);
                window.localStorage.setItem("shop_pass", shop_pass);

                saved_id    = shop_id;
                saved_pass  = shop_pass;

                showMenu();
            }
        }
    }

    function logoutProc() {
        $('#logout_area').load(PHP_ROOT + 'logout_proc.php', null, function () { alert('ログアウトしました'); });
        saved_id    = "";
        saved_pass  = "";

        shop_id     = "";
        shop_pass   = "";
        window.localStorage.setItem( "shop_id",      "" );
        window.localStorage.setItem( "shop_pass",    "" );

        loginProc();
    }

    function reLoginProc() {
        $('#login_try_area').load(PHP_ROOT + 'relogin.php', null, tryResultProc);
    }
    
            function Initialize(){
            showMenu();
    
            $('#version_check_area').height(0);
            $('#login_check_area').height(0);
            $('#login_try_area').height(0);
            $('#logout_area').height(0);
            $('#exist_check_area').height(0);
    
    
            versionCheck();
    
            loginCheck();
        }

        function NetworkCheck() {
            netresult = null;
            $('#exist_check_area').html("");
            var date = new Date();
            $('#exist_check_area').load(PHP_ROOT + "exist_check.html?ts=" + date.getTime(), checkTimer = setTimeout(ReadExistCheck, 500));
        }

        function ReadExistCheck() {
            var tmp = $('#exist_check_area').html();
            tmp = tmp.trim();

            if (tmp == "OK") {
                netresult = true;
            } else {
                alert('ネットワークに接続できません。\n誠に恐れ入りますが、\nネットワーク接続を確認後、\n最初からやり直してください。');
                showMenu();
                netresult = false;
            }
            clearTimeout(checkTimer);
        }
        
        function sizeFitting(){
            
            var device_rate = 1;
            if( SCREEN_WIDTH > 500 ){ device_rate = 0.8; }
            
            frame_width         = Math.round( SCREEN_WIDTH * 0.06 );
    
	        font_size_large_btn = Math.round( SCREEN_WIDTH * 0.05 * device_rate );

            font_size_tenkey    = Math.round( SCREEN_WIDTH * 0.08 * device_rate );
	        
	        font_size_small     = Math.round( SCREEN_WIDTH * 0.05 * device_rate );
	        font_size_middle    = Math.round( SCREEN_WIDTH * 0.07 * device_rate );
	        font_size_large     = Math.round( SCREEN_WIDTH * 0.14 * device_rate );
            font_size_table     = Math.round( SCREEN_WIDTH * 0.055 * device_rate );
	        font_size_strong    = Math.round( SCREEN_WIDTH * 0.11 * device_rate );
            
            
	        btn_radius          = Math.round( SCREEN_WIDTH * 0.018 );
            height_large_btn    = Math.round( SCREEN_HEIGHT * 0.12 );

            icon_size           = Math.round( SCREEN_WIDTH * 0.14 * device_rate);
	        icon_image_size     = Math.round( SCREEN_WIDTH * 0.07 * device_rate);
	        icon_radius         = Math.round( SCREEN_WIDTH * 0.018 * device_rate);
	        
	        icon_bottom_pos     = Math.round( SCREEN_HEIGHT - (SCREEN_HEIGHT*0.06 + icon_size)  );
	        icon_right_pos      = Math.round( SCREEN_WIDTH - (SCREEN_HEIGHT*0.06 + icon_size) );
            logout_right_pos    = Math.round( SCREEN_WIDTH * 0.64 );

	        $(".btn").css("border-radius", btn_radius+"px");
	        
	        $(".btn_large").css("font-size", font_size_large_btn+"px");
            $(".btn_large").css("height", height_large_btn+"px");
            
	        $(".btn_num").css("font-size", font_size_tenkey+"px");

	        $(".small_text").css("font-size", font_size_small+"px");
	        $(".middle_text").css("font-size", font_size_middle+"px");
	        $(".large_text").css("font-size", font_size_large+"px");
            $(".table_text").css("font-size", font_size_table+"px");
            $(".strong_text").css("font-size", font_size_strong+"px");
	        
	        $(".small_icon").css("width", icon_size+"px");
	        $(".small_icon").css("height", icon_size+"px");
	        $(".small_icon").css("border-radius", icon_radius+"px");
	        $(".small_icon_image").css("width", icon_image_size+"px");
	        $(".small_icon_image").css("height", icon_image_size+"px");
	        
            $(".management_button").css("width", icon_size +"px");
            $(".management_button").css("height", icon_size +"px");
            $(".management_button").css("border-radius", icon_radius +"px");
	        $(".management_button").css("top", icon_bottom_pos +"px");
            $(".management_button").css("left", icon_right_pos +"px");
            
            $(".home_button").css("width", icon_size +"px");
            $(".home_button").css("height", icon_size +"px");
            $(".home_button").css("border-radius", icon_radius +"px");
            $(".home_button").css("top", frame_width +"px");
            $(".home_button").css("left", frame_width +"px");
            
            $(".logout_button").css("margin-left", logout_right_pos +"px");
	        
	        $("qr_code_area").height($("qr_code_area").width());
	        
	        $(".input_common").css("border-radius", btn_radius+"px");
            
            
        }
