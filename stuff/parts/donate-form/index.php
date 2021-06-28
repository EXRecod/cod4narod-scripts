<?php


$bodytag = __DIR__;
$bodytag = str_replace("/donate-form", "", $bodytag);
$bodytag = str_replace("\donate-form", "", $bodytag);

$partsStr = str_replace("/parts", "", $bodytag);
$partsStr = str_replace("\parts", "", $partsStr);


 include_once $partsStr. "/data/settings.php";
 
$guid = "";
$name = "Без VIP (выберите игрока через Статистику)";
if (!empty($_GET["guid"])) {
    $guid = (int) $_GET["guid"];

    if ((strlen($guid) === 17)||(strlen($guid) == 19)) {

        $stats_db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.'', DB_USER, DB_PASSWORD);

        $name_query = $stats_db->query("SELECT s_player FROM db_stats_0 WHERE s_guid = '".$guid."' ORDER by s_lasttime desc limit 1");
        $name2 = $name_query->fetchColumn();

        if (!empty($name2)) {
            $name = mb_convert_encoding($name2, 'UTF-8', 'UTF-8');
        } else {
            $guid = "";
        }

    } else {

        $guid = "";
    }
}
 
 $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if (strpos($_SERVER['REQUEST_URI'], "?guid=") !== false) {
$actual_link = substr($actual_link, 0, strpos($actual_link, "?guid="));
}
$actual_link = str_replace("/parts/donate-form/", "", $actual_link);
$actual_link = str_replace("\parts\donate-form\\", "", $actual_link);


?>
<div style="text-align: center;">
   

<?php if(YANDEX_MONEY_STATUS === 1){?>

   <p style="text-align: center;">
        <font color="#27ae60"><span style="font-size: 26px;"><b>Карта, Я.Деньги, с мобильного</b></span></font>
    </p>


    <link rel="stylesheet" href="<?=$actual_link;?>/visual_elements/css/_money.css">
    <script type="text/javascript" charset="utf-8" src="<?=$actual_link;?>/visual_elements/js/jquery.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?=$actual_link;?>/visual_elements/js/_money.js"></script>
    <script charset="utf-8" src="<?=$actual_link;?>/visual_elements/js/lodash.min.js"></script>

    <script charset="utf-8" src="<?=$actual_link;?>/visual_elements/js/_old-site.ru.js"></script>

    <link rel="Stylesheet" href="<?=$actual_link;?>/visual_elements/css/b-widget-donate.css">

    <div class="i-ua_js_yes i-ua_css_standart utilityfocus i-ua_svg_yes i-ua_inlinesvg_yes i-ua_placeholder_yes b-widget-donate"
         data-content-block="this">
        <div class="b-widget-donate__target">
            GUID и Имя Игрока для установки <span class="form_link">VIP статуса </span>(<span class="vipdays" style="font-weight: bold;color: green;
  text-shadow: 0px 0px 1px black, 0 0 1px white, 0 0 1px darkblue;">+22 дня</span> <span style="color: yellow;
  text-shadow: 1px 1px 2px black, 0 0 25px blue, 0 0 5px darkblue;">VIP</span>)
        </div>

        <form method="POST" target="_blank" class="b-widget-donate__form"
              action="https://money.yandex.ru/quickpay/confirm.xml">
            <input name="label" type="hidden" value="<?= $guid ?>">
            <input name="receiver" type="hidden" value="<?=YU_MONEY_ID;?>">
            <input name="quickpay-form" type="hidden" value="donate">
            <input name="referer" type="hidden" value="<?=DONATE_URL;?>">
            <input name="targets" type="hidden" value="Поддержка <?=PROJECT_NAME;?> (<?= ($guid) ? "VIP для ".$name : 'Без VIP' ?>)">
            <input name="successURL" type="hidden" value="<?=$actual_link;?>/parts/getvip.php">
            <div class="b-widget-donate__comment">
                <label class="b-widget-donate__label"></label>
                <div class="b-textarea b-textarea__textarea-compact">
                    <textarea readonly class="b-textarea__textarea" name="vip" maxlength="60"><?= ($guid) ? $guid." (".$name.")" : $name ?></textarea>

                </div>

                <div class="b-form__field-hint">Для выбора GUID найдите себя в <a class="form_link" target="_blank"
                                                                                  href="<?=DONATE_URL_STATS;?>">Статистике</a>, нажмите на ник, после перехода на страницу -
                    нажмите на <b style="font-weight: bold;color: #650000;
  text-shadow: 0px 0px 1px black, 0 0 1px white, 0 0 1px darkblue;">Получить [VIP] для игрока...</b>
                </div>

                <div class="b-widget-donate__target">
                    <p>Ваш комментарий</a></p>
                </div>


                <div class="b-textarea b-textarea__textarea-compact">

                    <textarea class="b-textarea__textarea" style="height:60px !important" name="comment" maxlength="60"></textarea>
                </div>


            </div>
            <div class="b-input-text b-input-text_1 b-input-text_inline">
                <input class="b-input-text__input" name="sum" style="text-align: right;box-sizing: inherit;" type="text"
                       maxlength="10" value="150"><span class="b-widget-donate__currency">руб.</span></div>

            <script>

                function format_by_count(count) {
                    return format_by_form(count, ' дней ', ' дня ', ' день ');
                }

                function format_by_form(count, f1, f2, f3) {
                    count = Math.abs(count) % 100;
                    lcount = count % 10;

                    if (count >= 11 && count <= 19) return (f1);
                    if (lcount >= 2 && lcount <= 4) return (f2);
                    if (lcount == 1) return (f3);

                    return f1;
                }

                $(document).ready(function () {


                    $('input.b-input-text__input').on('keyup change', function () {

                        var amount = $(this).val();
                        var days = 0;


                         if (amount < 50)
						 {
							 alert('Минимум 50 рублей.');
						 }


                        if (amount < 10)
                            days = Math.round(amount / 20);

                        else if (amount < 20)
                            days = Math.round(amount / 15);

                        else if (amount < 40)
                            days = Math.round(amount / 10);

                        else if (amount < 50)
                            days = Math.round(amount / 8);

                        else if ($(this).val() < 100)
                            days = Math.round(amount / 7);

                        else if ($(this).val() < 300)
                            days = Math.round(amount / 4.8);

                        else if ($(this).val() < 500)
                            days = Math.round(amount / 4.8);

                        else if ($(this).val() < 1000)
                            days = Math.round(amount / 4.0);

                        else if ($(this).val() < 2000)
                            days = Math.round(amount / 2.73);

                        else
                            days = Math.round(amount / 2);


                        $('.vipdays').text("+" + days + format_by_count(days));


                    });
                });
            </script>
            <span class="b-form-radio b-form-radio_size_xl b-form-radio_theme_grey i-bem b-form-radio_js_inited"
                  onclick="return {&#39;b-form-radio&#39;:{name:&#39;b-form-radio&#39;,id:&#39;id1166036927751&#39;}}">

            <label class="b-form-radio__button b-form-radio__button_id_ b-form-radio__button_disabled_no b-form-radio__button_focused_yes b-form-radio__button_checked_yes b-form-radio__button_side_left"
                   for="id1166036927758" title="платеж с карты VISA или MasterCard">

                <span class="b-form-radio__inner">
                    <span class="b-form-radio__content">
                        <input type="radio" name="paymentType" checked="checked" class="b-form-radio__radio"
                               id="id1166036927758" value="AC">
                        <span class="b-form-radio__text"><img class="b-icon b-form-radio__ico"
                                                              src="<?=$actual_link;?>/visual_elements/img/quickpay-widget__any-card.png" alt=""
                                                              title=""></span><i class="b-form-radio__click"></i></span>
        </span>
        </label>
        <label class="b-form-radio__button b-form-radio__button_id_ b-form-radio__button_disabled_no b-form-radio__button_next-for-checked_yes"
               for="id1166036927781" title="платеж с баланса сотового"><span class="b-form-radio__inner">
                    <span class="b-form-radio__content"><input type="radio" name="paymentType"
                                                               class="b-form-radio__radio" id="id1166036927781"
                                                               value="MC">
                        <span class="b-form-radio__text"><img class="b-icon b-form-radio__ico"
                                                              src="<?=$actual_link;?>/visual_elements/img/quickpay-widget__mobile.png" alt=""
                                                              title=""></span><i class="b-form-radio__click"></i></span>
			</span>
		</label>

        <label class="b-form-radio__button b-form-radio__button_id_ b-form-radio__button_disabled_no b-form-radio__button_next-for-checked_yes b-form-radio__button_side_right"
               for="id1166036927798" title="платеж из кошелька в Яндекс.Деньгах"><span class="b-form-radio__inner"><span
                        class="b-form-radio__content"><input type="radio" name="paymentType"
                                                             class="b-form-radio__radio" id="id1166036927798"
                                                             value="PC"><span class="b-form-radio__text"><img
                                class="b-icon b-form-radio__ico" src="<?=$actual_link;?>/visual_elements/img/quickpay-widget__yamoney.png" alt=""
                                title=""></span><i class="b-form-radio__click"></i></span>
			</span>
		</label>

        </span><span class="b-button b-button_1 b-button_orange" data-block="b-button"><span class="b-button__inner">Поддержать</span>
        <input type="submit" value="Поддержать" class="b-button__input" name="submit-button">
        </span>

        </form>
    </div>
<?php }?>


<?php if(WEBMONEY_STATUS === 1){?>
 
    <p style="text-align: center;">
        <span style="font-size:26px;"><span style="color:#27ae60;"><strong>WebMoney, Bitcoin, QIWI&nbsp;и др.</strong></span></span>
    </p>

    <p style="text-align: center;">
        <iframe height="150" scrolling="no"
                src="<?=WEBMONEY;?>"
                style="border:none;" width="468"></iframe>
    </p>
	
<?php } ?>	


<?php if(QIWI_STATUS === 1){?>
  
    <p style="text-align: center;">
        <font color="#27ae60"><span style="font-size: 26px;"><b>Оплата Хостинга (QIWI)</b></span></font>
    </p>

    <p style="text-align: center;">
        Так же, Вы можете помочь самым естественным способом -
    </p>

    <p style="text-align: center;">
        закинуть денег напрямую&nbsp;на <span style="color:#27ae60;"><strong>наш хостинг</strong></span>.
    </p>

    <p style="text-align: center;">
        Для этого пройдите по ссылке:
    </p>

    <p style="text-align: center;">
        &nbsp;<span style="font-size:18px;"><strong><a class="form_link" href="<?=QIWI_URL;?>" ipsnoembed="true" rel="external nofollow"><span style="color:#27ae60;"><?=QIWI_URL;?></span></a></strong>&nbsp;</span><span style="font-size:14px;">(ЗАО Первый)</span>
    </p>

    <p style="text-align: center;">
        <span style="font-size:16px;">и введите номер нашего ЛС:&nbsp;<strong><span style="color:#27ae60;">639002</span></strong><span style="color:#27ae60;"> </span></span>
    </p>

    <p style="text-align: center;">
        <span style="font-size:14px;">(оплата любым способом,<span style="color:#27ae60;"> без комиссии</span>)</span>
    </p>  
	
<?php } ?>		

<?php if(PAYPAL_STATUS === 1){?>

    <p style="text-align: center;margin: 0;">
        <span style="font-size:26px;"><strong><span style="color:#27ae60;">Перевод PayPal -</span>&nbsp;</strong></span><u><span
                    style="font-size:24px;"><strong><a class="form_link" href="<?=PAYPAL_URL;?>" rel="external nofollow"><span
                                style="color:#d35400;">PayPal.me/</span><span style="color:#27ae60;"><?=PAYPAL_USER;?></span></a></strong></span></u>
    </p>

    <p style="margin: 0;text-align: center;">
        <span style="font-size:12px;">(please include your ingame nickname in the payment note)</span>
    </p>

    <p style="text-align: center;">
        <input name="cmd" type="hidden" value="_s-xclick"><input name="encrypted" type="hidden" value="<?=PAYPAL_KEY;?>">
    </p>
	
<?php } ?>		

</div>
