<?php

/**
 * +----------------------------------------------------------------------
 * | LCLPHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2015 All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 罗敏贵 <e-mail:minguiluo@163.com> <QQ:271391233>
 * +----------------------------------------------------------------------
 * | 文件功能：支付
 * +----------------------------------------------------------------------
 */
if (!defined('IN_LCL') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$checktype = $_GET['checktype'];

if ($operation == 'alipay') {
    $settings = C::t('common_setting')->fetch_all(array('ec_alipay_account', 'ec_alipay_contract'));
    if (!empty($checktype)) {
        require_once LCL_ROOT . './api/trade/api_alipay.php';
        if ($checktype == 'credit') {
            ob_end_clean();
            dheader('location: ' . credit_payurl(0.01, $orderid));
        } elseif ($checktype == 'virtualgoods') {
            $pay = array(
                'logistics_type' => 'VIRTUAL'
            );
            $trade = array(
                'subject' => $lang['ec_alipay_check_virtualgoodssubject'],
                'itemtype' => 0.01,
                'account' => $settings['ec_account'],
            );
            $tradelog = array(
                'orderid' => 'TEST' . dgmdate(TIMESTAMP, 'YmdHis') . random(18),
                'baseprice' => 0.01,
                'number' => 1,
                'transportfee' => 0,
            );
            dheader('location: ' . trade_payurl($pay, $trade, $tradelog));
        } elseif ($checktype == 'goods') {
            $pay = array(
                'logistics_type' => 'EMS',
                'transport' => 'SELLER_PAY',
            );
            $trade = array(
                'subject' => $lang['ec_alipay_check_goodssubject'],
                'itemtype' => 1,
                'account' => $settings['ec_account'],
            );
            $tradelog = array(
                'orderid' => 'TEST' . dgmdate(TIMESTAMP, 'YmdHis') . random(18),
                'baseprice' => 0.01,
                'number' => 1,
                'transportfee' => 0,
            );
            dheader('location: ' . trade_payurl($pay, $trade, $tradelog));
        }
        exit;
    }

    list($ec_alipay_contract, $ec_alipay_securitycode, $ec_alipay_partner, $ec_alipay_creditdirectpay) = explode("\t", authcode($settings['ec_alipay_contract'], 'DECODE', $_G['config']['security']['authkey']));
    $ec_alipay_securitycodemask = $ec_alipay_securitycode ? $ec_alipay_securitycode{0} . '********' . substr($ec_alipay_securitycode, -4) : '';

    if (!submitcheck('alipaysubmit')) {

        include cptemplate('global/ec_alipay');
    } else {
        $settingsnew = $_GET['settingsnew'];
        $settingsnew['ec_alipay_contract'] = 0;
        if (!empty($settingsnew['ec_securitycode']) && !empty($settingsnew['ec_alipay_partner'])) {
            $settingsnew['ec_alipay_contract'] = 1;
        }
        if ($settingsnew['ec_alipay_account'] && !$settingsnew['ec_alipay_contract']) {
            cpmsg('alipay_not_contract', 'admin.php?action=ec&operation=alipay', 'error');
        } else {
            $settingsnew['ec_alipay_account'] = trim($settingsnew['ec_alipay_account']);
            $settingsnew['ec_alipay_securitycode'] = trim($settingsnew['ec_alipay_securitycode']);
            C::t('common_setting')->update('ec_alipay_account', $settingsnew['ec_alipay_account']);
            $ec_alipay_securitycodemasknew = $settingsnew['ec_alipay_securitycode'] ? $settingsnew['ec_alipay_securitycode']{0} . '********' . substr($settingsnew['ec_alipay_securitycode'], -4) : '';
            $settingsnew['ec_alipay_securitycode'] = $ec_alipay_securitycodemasknew == $ec_alipay_securitycodemask ? $ec_alipay_securitycode : $settingsnew['ec_alipay_securitycode'];
            $ec_alipay_contract = addslashes(authcode($settingsnew['ec_alipay_contract'] . "\t" . $settingsnew['ec_alipay_securitycode'] . "\t" . $settingsnew['ec_alipay_partner'] . "\t" . $settingsnew['ec_alipay_creditdirectpay'], 'ENCODE', $_G['config']['security']['authkey']));
            C::t('common_setting')->update('ec_alipay_contract', $ec_alipay_contract);
            updatecache('setting');

            cpmsg('alipay_succeed', 'admin.php?action=ec&operation=alipay', 'succeed');
        }
    }
} elseif ($operation == 'tenpay') {
    $settings = C::t('common_setting')->fetch_all(array('ec_tenpay_direct', 'ec_tenpay_account', 'ec_tenpay_bargainor', 'ec_tenpay_key', 'ec_tenpay_opentrans_chnid', 'ec_tenpay_opentrans_key'));
    if (!empty($checktype)) {
        require_once LCL_ROOT . './api/trade/api_tenpay.php';
        if ($checktype == 'credit') {
            dheader('location: ' . credit_payurl(1, $orderid));
        } elseif ($checktype == 'virtualgoods') {
            $pay = array(
                'logistics_type' => 'VIRTUAL'
            );
            $trade = array(
                'subject' => $lang['ec_tenpay_check_virtualgoodssubject'],
                'itemtype' => 1,
                'tenpayaccount' => $settings['ec_tenpay_opentrans_chnid'],
            );
            $tradelog = array(
                'orderid' => 'TEST' . dgmdate(TIMESTAMP, 'YmdHis') . random(18),
                'baseprice' => 1,
                'number' => 1,
                'transportfee' => 0,
            );
            dheader('location: ' . trade_payurl($pay, $trade, $tradelog));
        } elseif ($checktype == 'goods') {
            $pay = array(
                'logistics_type' => 'EMS',
                'transport' => 'SELLER_PAY',
            );
            $trade = array(
                'subject' => $lang['ec_tenpay_check_goodssubject'],
                'itemtype' => 1,
                'tenpayaccount' => $settings['ec_tenpay_opentrans_chnid'],
            );
            $tradelog = array(
                'orderid' => 'TEST' . dgmdate(TIMESTAMP, 'YmdHis') . random(18),
                'baseprice' => 1,
                'number' => 1,
                'transportfee' => 0,
            );
            dheader('location: ' . trade_payurl($pay, $trade, $tradelog));
        }
        exit;
    }

    if (!submitcheck('tenpaysubmit')) {

        include cptemplate('global/ec_tenpay');
    } else {
        $settingsnew = $_GET['settingsnew'];
        $settingsnew['ec_tenpay_bargainor'] = trim($settingsnew['ec_tenpay_bargainor']);
        $settingsnew['ec_tenpay_key'] = trim($settingsnew['ec_tenpay_key']);
        $tenpay_securitycodemask = $settings['ec_tenpay_key'] ? $settings['ec_tenpay_key']{0} . '********' . substr($settings['ec_tenpay_key'], -4) : '';
        $settingsnew['ec_tenpay_key'] = $tenpay_securitycodemask == $settingsnew['ec_tenpay_key'] ? $settings['ec_tenpay_key'] : $settingsnew['ec_tenpay_key'];

        $settingsnew['ec_tenpay_opentrans_key'] = trim($settingsnew['ec_tenpay_opentrans_key']);
        $tenpay_securitycodemask = $settings['ec_tenpay_opentrans_key'] ? $settings['ec_tenpay_opentrans_key']{0} . '********' . substr($settings['ec_tenpay_opentrans_key'], -4) : '';
        $settingsnew['ec_tenpay_opentrans_key'] = $tenpay_securitycodemask == $settingsnew['ec_tenpay_opentrans_key'] ? $settings['ec_tenpay_opentrans_key'] : $settingsnew['ec_tenpay_opentrans_key'];

        if ($settingsnew['ec_tenpay_direct'] && (!empty($settingsnew['ec_tenpay_bargainor']) && !preg_match('/^\d{10}$/', $settingsnew['ec_tenpay_bargainor']))) {
            cpmsg('tenpay_bargainor_invalid', 'action=ec&operation=tenpay', 'error');
        }
        if ($settingsnew['ec_tenpay_direct'] && (empty($settingsnew['ec_tenpay_key']) || !preg_match('/^[a-zA-Z0-9]{32}$/', $settingsnew['ec_tenpay_key']))) {
            cpmsg('tenpay_key_invalid', 'action=ec&operation=tenpay', 'error');
        }

        $data = array('ec_tenpay_direct' => $settingsnew['ec_tenpay_direct'],
            'ec_tenpay_bargainor' => $settingsnew['ec_tenpay_bargainor'],
            'ec_tenpay_key' => $settingsnew['ec_tenpay_key'],
            'ec_tenpay_opentrans_chnid' => $settingsnew['ec_tenpay_opentrans_chnid'],
            'ec_tenpay_opentrans_key' => $settingsnew['ec_tenpay_opentrans_key']);

        C::t('common_setting')->update_batch($data);
        updatecache('setting');

        cpmsg('tenpay_succeed', 'admin.php?action=ec&operation=tenpay', 'succeed');
    }
} elseif ($operation == 'orders') {

} elseif ($operation == 'credit') {

}
?>